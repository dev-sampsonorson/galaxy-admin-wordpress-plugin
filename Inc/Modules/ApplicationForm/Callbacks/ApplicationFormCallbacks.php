<?php
/**
 * @package Galaxy Admin Plugin
 */

class ApplicationFormCallbacks extends BaseController
{

    protected $controller;

    public function __construct($controller)
    {
        parent::__construct();

        $this->controller = $controller;
    }

    public function renderApplicationForm() {
        if(!is_page(array(GALAXY_APPLICATION_FORM_SLUG))) return;

        // enqueue scripts here

        $controller = $this->controller;

        ob_start();
        require_once($this->modules_path . "ApplicationForm/Views/ApplicationForm.php");
        return ob_get_clean();
    }

}



