<?php

    /**
     * @package Galaxy Admin Plugin
     */

    final class Init extends BaseController {

        private static $initiated = false;

        /**
         * Store all the classes inside an array
         * @return array Full list of classes
         */
        public static function get_services() {
            return [       
                Enqueue::class,
                SettingsLinks::class, 
                ManagementCenterController::class,
                ApplicationFormController::class
            ];
        }

        /**
         * Loop through the classes, initialized them, 
         * and call the register() method if it exists
         * @return
         */
        public static function register_services() {
            if ( ! self::$initiated ) {
                self::init_services();
            }
        }

        private static function init_services() {            
            self::$initiated = true;
            
            foreach (self::get_services() as $class) {
                $service = self::instantiate($class);

                if (method_exists($service, 'register')) {
                    $service->register();
                }
            }
        }

        /**
         * Initialize the class
         * @param class $class class from the services array
         * @return class instance new instance of the class
         */
        private static function instantiate($class) {
            $service = new $class();

            return $service;
        }
    }