<?php

class Controller {

    public $data;

    public function __construct() {
        $this->data = array();
    }

    public function loadTemplates($template, $pageData = array(), $model_data = array()) {
        extract($pageData);
        extract($model_data);
        require __DIR__ . '/../Views/Templates/' . $template . '.php';
    }

    public function loadViewOnTemplate($view, $pageData = array(), $model_data = array()) {
        extract($pageData);
        extract($model_data);
        require realpath(__DIR__ . '/../Views/' . $view . '.php');
    }

}