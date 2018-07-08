<?php

namespace ktaris\assets;

use Yii;
use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $css = [
        'font-awesome/web-fonts-with-css/css/fontawesome-all.min.css'
    ];
}
