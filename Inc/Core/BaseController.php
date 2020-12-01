<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class BaseController {        
        protected $plugin_version;
        protected $plugin_path;
        protected $plugin_url;
        protected $plugin;

        
        protected $modules_path;
        protected $helper;
        
        public function __construct() {
            $this->plugin_version = '1.0';
            $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
            $this->modules_path = plugin_dir_path(dirname(__FILE__, 2)) . 'Inc/Modules/';
            $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
            $this->plugin_script_base_url = plugin_dir_url(dirname(__FILE__, 2)) . '/assets/js';
            $this->plugin_style_base_url = plugin_dir_url(dirname(__FILE__, 2)) . '/assets/css';
            $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/galaxy-admin-plugin.php';

            $this->helper = new Helper();
        }

        protected function getPostVar($varName) {
            return isset($_POST[$varName]) && !empty($_POST[$varName]) ? $_POST[$varName] : null;
        }

        public function getTableName($table) {
            global $wpdb;

            return $wpdb->prefix . $table;
        }
        
        protected static function toEntityArray(array $queryResult): array {
            $result;
            foreach($queryResult as $record) {
                $result[] = $record->toArray();
            }

            return $result;
        }

        public function return_json($statusCode, $result = null)
        {
            $isSuccess = ($statusCode >= 200 && $statusCode <= 208) || $statusCode === 226;

            $return = array(
                'status' => $isSuccess ? 'success' : 'error',
                'status-code' => $statusCode,
                'message' => '',
                'result' => $result
            );

            if (!$isSuccess) {        
                wp_send_json_error($return, $statusCode);
            } else {
                wp_send_json_success($return, $statusCode);
            }

            wp_die();
            return false;
        }

        public function return_json_error($statusCode, $responseMessage, $result = null)
        {
            if ($statusCode < 400)
                throw new Exception("Invalid status code");

            $return = array(
                'status' => 'error',
                'status-code' => $statusCode,
                'message' => $responseMessage,
                'result' => $result
            );

            wp_send_json_error($return, $statusCode);
            wp_die();
            return false;
        }
    }