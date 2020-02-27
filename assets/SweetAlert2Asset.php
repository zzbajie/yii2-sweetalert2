<?php

namespace zzbajie\sweetalert2\assets;

use yii\web\AssetBundle;

/**
 * Class SweetAlert2Asset
 * @package zzbajie\yii2Sweetalert2\assets
 */
class SweetAlert2Asset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@npm/sweetalert2/dist';

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
        $this->js[] = 'sweetalert2.all' . $min . '.js';
    }
}