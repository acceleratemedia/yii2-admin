<?php

namespace bvb\admin\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $css = [
        'css/site.css',
        'https://use.fontawesome.com/releases/v5.4.1/css/all.css'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
