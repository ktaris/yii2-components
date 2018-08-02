<?php

namespace ktaris\aws;

use Yii;
use Underscore\Types\Arrays;

/**
 * Diseñada como un Trait para modelos, se puede utilizar para controladores que
 * obtienen los archivos en nombre de los modelos, estableciendo la conexión con
 * AWS S3 en base a un arreglo de parámetros de conexión.
 */
trait S3Trait
{
    /**
     * Objeto referencia al servicio AWS S3.
     *
     * @var  Aws\S3\S3Client
     */
    private $_s3;

    /**
     * Regresa un objeto con acceso a las funciones de S3 en AWS.
     *
     * @return Aws\S3\S3Client
     */
    private function getS3()
    {
        if (empty($this->_s3)) {
            $this->_s3 = Yii::$app->awssdk->getAwsSdk()->createS3();
        }

        return $this->_s3;
    }

    /**
     * Manda a llamar el método de obtener archivo de S3, donde el único
     * elemento necesario en el arreglo es "Key".
     *
     * @param  array $params arreglo de parámetros para S3.
     *
     * @return Aws\Result
     */
    public function getS3File($params)
    {
        return $this->getS3()->getObject($this->addBucketToParams($params));
    }

    /**
     * Manda a llamar el método de subir archivo de S3, donde se esperan los
     * elementos "Key" y "Body".
     *
     * @param  array $params arreglo de parámetros para S3.
     *
     * @return Aws\Result
     */
    public function putS3File($params)
    {
        return $this->getS3()->putObject($this->addBucketToParams($params));
    }

    /**
     * Manda a llamar el método de eliminar archivo de S3, donde el único
     * elemento necesario en el arreglo es "Key".
     *
     * @param  array $params arreglo de parámetros para S3.
     *
     * @return Aws\Result
     */
    public function deleteS3File($params)
    {
        return $this->getS3()->deleteObject($this->addBucketToParams($params));
    }

    /**
     * Agrega la información del bucket de S3 a los parámetros de
     * la petición.
     *
     * @param array $params arreglo de datos a ser enviado a AWS S3
     */
    private function addBucketToParams($params)
    {
        if (Arrays::has($params, 'Bucket')) {
            return $params;
        }

        return Arrays::merge($params, [
            'Bucket' => $this->getS3Bucket(),
        ]);
    }

    // ==================================================================
    //
    // Funciones utilizadas como puente entre Yii2 y AWS S3.
    //
    // ------------------------------------------------------------------

    /**
     * Recibe un yii\web\UploadedFile como argumento, del cual se obtiene el contenido
     * del archivo a ser subido a S3.
     *
     * Y, si el nombre de archivo no lleva extensión, esta función también se encarga de agregarlo.
     *
     * @param  UploadedFile $uploadedFileObject objeto con archivo a ser subido
     * @param  array        $params             arreglo de datos
     * @return Aws\Result
     */
    public function putS3FileFromUploadedFile($uploadedFileObject, $params)
    {
        $params['Key'] = self::agregarExtensionAKey($params['Key'], $uploadedFileObject);

        return $this->putS3File(Arrays::merge($params, [
            'Body' => file_get_contents($uploadedFileObject->tempName),
        ]));
    }

    // ==================================================================
    //
    // Funciones estáticas auxiliares.
    //
    // ------------------------------------------------------------------

    public static function agregarExtensionAKey($key, $uploadedFileObject)
    {
        if (empty(pathinfo($key, PATHINFO_EXTENSION))) {
            $key .= '.'.pathinfo($uploadedFileObject->name, PATHINFO_EXTENSION);
        }

        return $key;
    }

    public static function getNombreDeArchivoDeKey($awsResultObject)
    {
        $key = self::getKeyDeURLDeAWS($awsResultObject);

        $stringFromLastSlash = strrchr($key, '/');

        return substr($stringFromLastSlash, 1);
    }

    public static function getKeyDeURLDeAWS($awsResultObject)
    {
        $posSlashBucket = strpos($awsResultObject['ObjectURL'], '.amazonaws.com/');

        return substr($awsResultObject['ObjectURL'], $posSlashBucket + 15);
    }

    /**
     * Crea una ruta de directorio utilizando un arreglo donde cada elemento es un directorio,
     * o subdirectorio, de la ruta final.
     *
     * @param  array $arreglo arreglo con directorios
     *
     * @return string
     */
    public static function crearDirectorioDesdeArreglo($arreglo)
    {
        return implode(DIRECTORY_SEPARATOR, $arreglo).DIRECTORY_SEPARATOR;
    }

    // ==================================================================
    //
    // Funciones privadas auxiliares para soporte a las demás
    // funciones públicas.
    //
    // ------------------------------------------------------------------

    /**
     * Este método se utiliza al recibir un archivo con getObject.
     * @param  array $params arreglo de datos para AWS S3.
     * @return array arreglo de datos con ResponseContentType agregado.
     */
    private function establecerResponseContentTypeHeader($params)
    {
        if (Arrays::has($params, 'ResponseContentType')) {
            return $params;
        }

        $params = Arrays::merge($params, [
            'ResponseContentType' => $this->getHeaderFromExtension($this->getFileExtension($params['Key'])),
        ]);

        return $params;
    }

    private function getFileExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    private function getHeaderFromExtension($extension)
    {
        $headers = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'xml' => 'application/xml',
        ];

        if (Arrays::has($headers, $extension)) {
            return $headers[$extension];
        } else {
            return 'application/octet-stream';
        }
    }
}
