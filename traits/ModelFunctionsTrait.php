<?php

namespace ktaris\traits;

use yii\helpers\ArrayHelper;

trait ModelFunctionsTrait
{
    // ==================================================================
    //
    // Funciones privadas.
    //
    // ------------------------------------------------------------------

    /**
     * Carga un modelo de la base de datos, si tenemos un id, o crea un nuevo modelo y vacía los atributos.
     *
     * @param  string $className nombre de la clase
     * @param  array  $condition condición de búsqueda, generalmente id.
     * @param  array  $data      arreglo de datos recibidos
     *
     * @return mixed            modelo con clase $className instanciado.
     */
    private function cargarModelo($className, $condition, $data = [])
    {
        $model = null;

        // Crear condición en un formato legible.
        $condition = $this->determinarCondicionParaFiltrado($condition, $data);

        // Si tenemos datos y el campo de id, ya hay un modelo existente que debemos cargar.
        $model = $className::findOne($condition);

        // Si no cargamos un modelo previamente, creamos uno nuevo.
        if (empty($model)) {
            $model = new $className;
        }

        // Tras tener un modelo, cargado o nuevo, vaciamos los datos a los atributos.
        $model->attributes = $data;

        return $model;
    }

    /**
     * Sirve para crear la condición de filtrado que se utiliza en la búsqueda para encontrar o crear
     * un nuevo modelo, en base a si es cadena, arreglo asociativo, o arreglo simple.
     *
     * @param  array $condition arreglo con datos, o una cadena.
     * @param  array $data      datos que se cargarán al modelo.
     *
     * @return array
     */
    private function determinarCondicionParaFiltrado($condition, $data)
    {
        // Si es un entero o una cadena, la regresamos directo como la condición.
        if (is_string($condition) || is_integer($condition)) {
            return $condition;
        }

        // Si es arreglo es donde empezamos con cosas medio raras, para saber si viene como arreglo asociativo
        // o como arreglo numérico.
        // Si es numérico, es un arreglo de campos que se deben obtener del arreglo de datos.
        // Si es associativo, ya no hay nada que hacer.
        if (ArrayHelper::isIndexed($condition)) {
            return $this->obtenerDatosDeId($condition, $data);
        }

        // Si llegó acá, es un array asociativo, con sus datos bien puestos, o sabrá Dios qué es.
        return $condition;
    }

    private function obtenerDatosDeId($idFields, $data)
    {
        $id_data = [];

        // Si no hay campo de id, se regresa inmediatamente.
        if (empty($idFields)) {
            return $id_data;
        }

        if (is_array($idFields)) {
            // Se obtiene el valor de los datos por cada campo que compone el id.
            $id_data = array_reduce($idFields, function ($carry, $m) use ($data) {
                if (ArrayHelper::keyExists($m, $data)) {
                    $carry[$m] = $data[$m];
                }

                return $carry;
            }, $id_data);

            // Si la cantidad de campos en el resultado es menor a la cantidad de campos
            // que componen una id, no obtendremos un modelos único, con llave de dos campos,
            // por lo que mejor removemos el id.
            if (count($id_data) != count($idFields)) {
                $id_data = [];
            }
        } elseif (ArrayHelper::keyExists($idFields, $data)) {
            // Se obtiene el dato del único campo que conforma el id;
            $id_data[$idFields] = $data[$idFields];
        }

        return $id_data;
    }

    // ==================================================================
    //
    // Funciones estáticas.
    //
    // ------------------------------------------------------------------

    /**
     * Creado para cuando se quiere obtener un modelo con id conocida, o regresar un nuevo
     * modelo con dicha id, pero siempre tener disponible un modelo.
     *
     * @param  mixed $condition atributo o atributos que inicializan el modelo o su búsqueda.
     *
     * @return mixed modelo existente o nuevo, con los atributos de identificación.
     */
    public static function findOneOrCreate($condition)
    {
        $model = self::findOne($condition);

        if (empty($model)) {
            $model = new self();

            $model->attributes = $condition;
        }

        return $model;
    }
}
