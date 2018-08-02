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
     * @param  array        $data     arreglo de datos recibidos
     * @param  string       $formName cadena que contiene los datos, generalmente mismo nombre del modelo
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
     * Su función principal es saber si todo un conjunto de modelos fueron validos, o guardados.
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

    // ==================================================================
    // ==================================================================
    // ==================================================================
    //
    // Funciones relacionadas a arreglos de modelos.
    //
    // ------------------------------------------------------------------
    // ------------------------------------------------------------------
    // ------------------------------------------------------------------



    // ==================================================================
    //
    // Almacenamiento.
    //
    // ------------------------------------------------------------------

    public static function saveModels($models, $atributosAdicionales, $saved = true)
    {
        return array_reduce($models, function ($carry, $m) use ($atributosAdicionales) {
            $m->attributes = $atributosAdicionales;

            return $m->save() && $carry;
        }, $saved);
    }

    // ==================================================================
    //
    // Validación.
    //
    // ------------------------------------------------------------------

    /**
     * Agrega los errores de todo un arreglo de modelos a los errores del modelo
     * principal, ante el cual reportan.
     *
     * @param yii\base\Model $model  modelo principal
     * @param array          $models arreglo de modelos
     */
    public static function agregarErroresDeArregloAModelo($model, $models)
    {
        $model->addErrors(self::unirErroresDeValidacionDeModelos($models));
    }

    /**
     * Une los errores de validación de modelos en arreglo, para poder evaluar un arreglo unidimensional
     * de errores.
     *
     * @param  array $models arreglo de modelos
     *
     * @return array         arreglo de errores aplanados de varios modelos, con índice agregado.
     */
    public static function unirErroresDeValidacionDeModelos($models)
    {
        $out = [];

        foreach ($models as $i => $model) {
            $errores = self::cambiarLlavesDeErroresDeValidacion($model->getErrors(), $i, strtolower($model->formName()));
            $out = ArrayHelper::merge($out, $errores);
        }

        return $out;
    }

    /**
     * A cada campo validado dentro de un arreglo de modelos se le asigna una llave con un índice,
     * para poder aplanar el arreglo de errores, sin que se eliminen los campos duplicados que tienen
     * error en varios modelos.
     *
     * @param array   $arregloDeErrores arreglo de errores de un modelo.
     * @param integer $indice           índice del modelo dentro del arreglo, base cero.
     * @param string  $prepend          texto que se agrega al inicio del error.
     *
     * @return array arreglo de errores con nuevas llaves, con _# al final.
     */
    private static function cambiarLlavesDeErroresDeValidacion($arregloDeErrores, $indice, $prepend = '')
    {
        $out = [];

        if (!empty($prepend) && strcmp('_', substr($prepend, -1)) !== 0) {
            $prepend .= '_';
        }

        foreach ($arregloDeErrores as $index => $errors) {
            $nuevoIndice = $prepend.$index.'_'.$indice;
            $out[$nuevoIndice] = $errors;
        }

        return $out;
    }
}
