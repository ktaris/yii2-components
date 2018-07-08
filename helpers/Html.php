<?php

namespace ktaris\helpers;

use Yii;
use yii\helpers\Html as BaseHtml;

class Html extends BaseHtml
{
    public static function createOrUpdateButton($isNewRecord = true)
    {
        $content = $isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update');
        $class = $isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block';

        return static::submitButton('<i class="fas fa-save"></i> '.$content, ['class' => $class]);
    }

    public static function updateButton($urlData)
    {
        return static::a('<i class="fas fa-pencil-alt"></i> '.Yii::t('app', 'Update'), $urlData, ['class' => 'btn btn-primary mb-1']);
    }

    public static function deleteButton($urlData)
    {
        return static::a('<i class="fas fa-trash"></i> '.Yii::t('app', 'Delete'), $urlData, [
            'class' => 'btn btn-danger mb-1 ml-1',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]);
    }
}
