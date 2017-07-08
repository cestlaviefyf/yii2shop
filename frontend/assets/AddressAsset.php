<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class AddressAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/home.css',
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/address.css',
        'style/bottomnav.css',
        'style/footer.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];

}