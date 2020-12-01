<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class Enqueue extends BaseController {
        public function register() {
            add_action('wp_enqueue_scripts', array($this, 'enqueueFrontendScripts'));
            add_action('admin_enqueue_scripts', array($this, 'enqueueBackendScripts'));
        }
    
        public function enqueueBackendScripts() {
            wp_enqueue_style( 'bootstrap-css', $this->plugin_url . 'dist/css/bootstrap.min.css', array(), false, false);
            wp_enqueue_style( 'galaxy-plugin-backend-css', $this->plugin_url . 'dist/css/backend.css', array('bootstrap-datepicker3-css'), microtime(), false);
            
            wp_enqueue_script( 'runtime-js', $this->plugin_url . 'dist/js/runtime.plugin-bundle.js', array(), false, true);
            wp_enqueue_script( 'vendor-js', $this->plugin_url . 'dist/js/vendor.plugin-bundle.js', array('runtime-js'), false, true);
            // wp_enqueue_script( 'modernizr-js', $this->plugin_url . 'dist/js/modernizr-custom.js', array('vendor-js'), false, true);
            wp_enqueue_script( 'backend-js', $this->plugin_url . 'dist/js/backend-index.plugin-bundle.js', array('vendor-js'), microtime(), true);
        }

        public function enqueueFrontendScripts() {


            wp_enqueue_style( 'bootstrap-css', $this->plugin_url . 'dist/css/bootstrap.min.css', array(), false, false);
            wp_enqueue_style( 'bootstrap-datepicker3-css', $this->plugin_url . 'dist/css/bootstrap-datepicker3.min.css', array('bootstrap-css'), false, false);
            wp_enqueue_style( 'galaxy-plugin-frontend-css', $this->plugin_url . 'dist/css/frontend.css', array('bootstrap-datepicker3-css'), microtime(), false);
            
            wp_enqueue_script( 'runtime-js', $this->plugin_url . 'dist/js/runtime.plugin-bundle.js', array(), false, true);
            wp_enqueue_script( 'vendor-js', $this->plugin_url . 'dist/js/vendor.plugin-bundle.js', array('runtime-js'), false, true);
            // wp_enqueue_script( 'modernizr-js', $this->plugin_url . 'dist/js/modernizr-custom.js', array('vendor-js'), false, true);
            // wp_enqueue_script( 'frontend-js', $this->plugin_url . 'dist/js/frontend-index.plugin-bundle.js', array('modernizr-js'), microtime(), true);

            wp_register_script( 'frontend-js', $this->plugin_url . 'dist/js/frontend-index.plugin-bundle.js', array('vendor-js'), microtime(), true);
            $frontend_script_config = array(
                'ajaxRequestUrl' => admin_url('admin-ajax.php'),
                'getExamListNonce' => wp_create_nonce("getExamList_nonce"),
                'saveApplicationNonce' => wp_create_nonce("saveApplication_nonce")
            );
            wp_localize_script( 'frontend-js', 'frontend_script_config', $frontend_script_config );
            wp_enqueue_script( 'frontend-js' );
        }
    }