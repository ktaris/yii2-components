<?php

namespace ktaris\assets;

use Yii;
use yii\web\AssetBundle;

class MarionetteAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $js = [
        'underscore/underscore-min.js',
        'backbone/backbone-min.js',
        'marionette/lib/backbone.marionette.js',
        'handlebars/handlebars.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
