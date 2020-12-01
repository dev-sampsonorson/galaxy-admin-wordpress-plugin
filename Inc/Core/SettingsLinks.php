<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class SettingsLinks extends BaseController {

        public function register() {
            add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));
        }
    
        public function settings_link($links) {
            $settings_link = '<a href="admin.php?page=galaxy-admin-plugin">Admin</a>';
            array_push($links, $settings_link);
            return $links;
        }
    }