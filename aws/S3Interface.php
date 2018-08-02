<?php

namespace ktaris\aws;

/**
 * Interfaz a ser utilizada por modelos que se conectan con S3 para
 * almacenar o obtener archivos.
 */
interface S3Interface
{
    /**
     * Regresa el nombre del bucket al que se pretende acceder en AWS S3.
     *
     * @return string nombre del bucket en AWS S3.
     */
    public function getS3Bucket();

    /**
     * Regresa el folder al que se subirán los archivos.
     *
     * Aunque propiamente dicho, el nombre de la carpeta es parte de "Key",
     * se ve mejor separado como otro parámetro, separando el nombre de archivo
     * de su ubicación.
     *
     * @return string nombre de la carpeta donde se guardan los archivos.
     */
    public function getS3Folder();

    /**
     * Regresa el nombre de la carpeta que se antepone a todo folder en entorno de pruebas.
     *
     * Dado que en entorno de pruebas se utiliza un solo bucket, y en ese bucket pueden coexistir
     * varios proyectos, cada uno de ellos hace su desastre contenido en su folder propio, razón
     * por la cual existe esta función, que regresa la carpeta raíz del proyecto en el bucket.
     *
     * @return string carpeta prefijo en entorno de pruebas.
     */
    public function getS3TestFolderPrefix();
}
