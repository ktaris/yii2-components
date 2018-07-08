<?php

namespace ktaris\helpers;

use yii\helpers\ArrayHelper;

class ModelHelper
{
    /**
     * Este método sirve para cargar datos a un modelo, a través del arreglo general de datos recibidos,
     * tomando como fuente única, de momento, el frontend.
     *
     * @param  ActiveRecord $model    modelo que recibirá los datos
     * @param  array $data     arreglo de datos recibidos
     * @param  string $formName cadena que contiene los datos, generalmente mismo nombre del modelo
     */
    public static function cargar($model, $data, $formName = null)
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }

        if (is_null($formName)) {
            $formName = $model->formName();
        }

        if (ArrayHelper::keyExists($formName, $data)) {
            $model->attributes = $data[$formName];

            return true;
        }

        return false;
    }

    /**
     * Se encarga de reducir un arreglo de valores booleanos, para determinar si todos ellos son
     * verdaderos o al menos hay uno falso (&&).
     *
     * @param  array   $arreglo      arreglo de valores booleanos
     * @param  boolean $valorInicial valor inicial para reducir
     *
     * @return boolean determina si todo es true o si hay al menos un false.
     */
    public static function reduce($arreglo, $valorInicial = true)
    {
        return array_reduce($arreglo, function ($carry, $item) {
            return ($carry && $item);
        }, $valorInicial);
    }
}
