<?php

namespace ktaris\widgets;

use yii\bootstrap\ActiveForm as BaseActiveForm;

class ActiveForm extends BaseActiveForm
{
    public $fieldConfig = [
        'template' => "{label}\n{input}\n{error}",
    ];
}
