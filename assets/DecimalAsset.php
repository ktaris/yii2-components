<?php

namespace ktaris\assets;

use Yii;
use yii\web\AssetBundle;

class DecimalAsset extends AssetBundle
{
    public $sourcePath = '@npm';

    public $js = [
        'decimal.js/decimal.min.js'
    ];
}
