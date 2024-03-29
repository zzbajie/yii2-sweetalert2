<?php

namespace zzbajie\sweetalert2;

use Yii;
use yii\bootstrap4\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use zzbajie\sweetalert2\assets\SweetAlert2Asset;

/**
 * Class Alert
 * @package zzbajie\yii2Sweetalert2
 */
class Alert extends Widget
{
    // Modal Type
    const TYPE_INFO = 'info';
    const TYPE_ERROR = 'error';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_QUESTION = 'question';

    // Input Type
    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_EMAIL = 'email';
    const INPUT_TYPE_PASSWORD = 'password';
    const INPUT_TYPE_NUMBER = 'number';
    const INPUT_TYPE_RANGE = 'range';
    const INPUT_TYPE_TEXTAREA = 'textarea';
    const INPUT_TYPE_SELECT = 'select';
    const INPUT_TYPE_RADIO = 'radio';
    const INPUT_TYPE_CHECKBOX = 'checkbox';
    const INPUT_TYPE_FILE = 'file';

    /**
     * All the flash messages stored for the session are displayed and removed from the session
     * Defaults to false.
     * @var bool
     */
    public $useSessionFlash = false;

    /**
     * @var string alert callback
     */
    public $callback = 'function() {}';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        SweetAlert2Asset::register($this->view);
    }

    /**
     * @param array $steps
     */
    public function initFlashWidget($steps = [])
    {
        if (!empty($steps)) {
            if (isset($steps[0]['text']) && !is_array($steps[0]['text'])) {
                $this->initSwalQueue($steps);
            } else {
                $this->processFlashWidget($steps);
            }
        }
    }

    /**
     * @param array $steps
     */
    public function initSwalQueue($steps = [])
    {
        $view = $this->getView();
        $js = "swal.queue(" . Json::encode($steps) . ");";
        $this->view->registerJs($js, $view::POS_END);
    }

    /**
     * @param string $options
     * @param string $callback
     */
    public function initSwal($options = '', $callback = '')
    {
        if ($this->hasTitle()) {
            $view = $this->getView();
            $js = "Swal.fire({$options}).then({$callback}).catch(swal.noop);";
            $this->view->registerJs($js, $view::POS_END);
        } else {
            //throw new InvalidConfigException("The 'title' option is required.");
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($session = $this->getSession()) {
            $this->initFlashWidget($this->processFlashSession($session));
        } else {
            $this->initSwal($this->getOptions(), $this->callback);
        }
    }

    /**
     * @param $session bool|mixed|\yii\web\Session
     * @return array
     */
    public function processFlashSession($session)
    {
        $flashes = $session->getAllFlashes();
        $steps = [];
        foreach ($flashes as $icon => $data) {
            $data = (array)$data;
            foreach ($data as $message) {
                array_push($steps, ['icon' => $icon, 'text' => $message]);
            }
            $session->removeFlash($icon);
        }
        return $steps;
    }

    /**
     * @param array $steps
     */
    public function processFlashWidget($steps = [])
    {
        $params = [];
        if ($params['options'] = $steps[0]['text']) {
            $params['options']['icon'] = isset($params['options']['icon']) ? $params['options']['icon'] : $steps[0]['icon'];
            $params['callback'] = isset($steps[1]['text']['callback']) ? $steps[1]['text']['callback'] : $this->callback;
            $this->options = $params['options'];
            $this->callback = $params['callback'];
            $this->initSwal($this->getOptions(), $this->callback);
        }
    }

    /**
     * Get widget options
     * @return string
     */
    public function getOptions()
    {
        if (isset($this->options['id']))
            unset($this->options['id']);

        if (ArrayHelper::isIndexed($this->options)) {
            $str = '';
            foreach ($this->options as $value) {
                $str .= '"' . $value . '",';
            }
            return chop($str, ' ,');
        }
        return Json::encode($this->options);
    }

    /**
     * @return bool|mixed|object|\yii\web\Session|null
     */
    private function getSession()
    {
        return $this->useSessionFlash ? Yii::$app->session : false;
    }

    /**
     * @return bool
     */
    private function hasTitle()
    {
        $title = ArrayHelper::getValue($this->options, 'title');
        return !empty($title);
    }
}