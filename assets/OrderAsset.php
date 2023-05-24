<?php

namespace app\assets;

use yii\web\AssetBundle;

class OrderAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/ord/web/themes';
    public $css = [
        'css/custom.css',
        'css/bootstrap.min.css'
    ];
    public $js = [
        'js/bootstrap.min.js',
        'js/jquery.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap4\BootstrapAsset',
    ];

}