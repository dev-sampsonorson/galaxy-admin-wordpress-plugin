<?php

    use Rakit\Validation\Validator;

    /**
     * @package Galaxy Admin Plugin
     */

    class ManagementCenterController extends BaseController {

        public $settings;
        public $callbacks;
        private $validator;
    
        public function register() {      
            /* $this->settings = new SettingsApi();    
            $this->callbacks = new ManagementCenterCallbacks($this);

            $this->validator = new Validator();
    
            $this->settings
                 ->addPages($this->getPages())
                 ->withSubPage('Management Center')
                 ->addSubPages($this->getSubPages())
                 ->register(); */
        }

        private function getPages() {
            return array(
                array(
                    'page_title' => 'Management Center', 
                    'menu_title' => 'Galaxy Admin', 
                    'capability' => 'manage_options', 
                    'menu_slug' => 'galaxy-admin-plugin', 
                    'callback' => array($this->callbacks, 'dashboardView'), 
                    'icon_url' => 'dashicons-admin-generic', 
                    'position' => 110
                )
            );
        }        

        private function getSubPages() {
            return array(
                array(
                    'parent_slug' => 'galaxy-admin-plugin', 
                    'page_title' => 'Short Codes', 
                    'menu_title' => 'Short Codes', 
                    'capability' => 'manage_options', 
                    'menu_slug' => 'galaxy-admin-shortcode', 
                    'callback' => array($this->callbacks, 'shortcodeView')
                )
            );
        }
    }