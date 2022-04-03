<?php

namespace zzbajie\sweetalert2\assets;

use yii\web\AssetBundle;

/**
 * Class SweetAlert2Asset
 * @package zzbajie\sweetalert2\assets
 */
class SweetAlert2Asset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@npm/sweetalert2/dist';

    /**
     * @var string
     */
    public $css = [];

    /**
     * @var array
     */
    public $js = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $min = YII_ENV_DEV ? '' : '.min';
        $this->css[0] = 'sweetalert2' . $min . '.css';
        $this->js[0] = 'sweetalert2' . $min . '.js';
    }

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}