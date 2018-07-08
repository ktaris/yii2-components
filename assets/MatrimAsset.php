<?php

namespace ktaris\assets;

use Yii;
use yii\web\AssetBundle;

class MatrimAsset extends AssetBundle
{
    public $js = [
        'js/matrim.js',
    ];

    public $depends = [
        'ktaris\assets\DecimalAsset',
        'ktaris\assets\MarionetteAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__;
    }
}
