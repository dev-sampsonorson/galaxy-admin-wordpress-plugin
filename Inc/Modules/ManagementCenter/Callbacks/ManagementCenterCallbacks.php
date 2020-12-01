<?php

/**
 * @package Galaxy Admin Plugin
 */

class ManagementCenterCallbacks extends BaseController {

    protected $controller;

    public function __construct($controller)
    {
        parent::__construct();

        $this->controller = $controller;
    }

    public function dashboardView() {
        $controller = $this->controller;
        
        return require_once($this->modules_path . "ManagementCenter/Views/ManagementCenterView.php");
    }

    public function shortcodeView() {
        return require_once($this->modules_path . "ManagementCenter/Views/ShortcodeView.php");
    }
}