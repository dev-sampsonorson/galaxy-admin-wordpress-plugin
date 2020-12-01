<?php
    if (!session_id())
        session_start();

    /**
     * @package Galaxy Admin Plugin
     */

    /**
     * Plugin Name: Galaxy Admin Plugin
     * Description: Use this plugin to manage the Galaxy website
     * Version: 1.0.0
     * Author: Sampson Orson Jackson
     * Text Domain: galaxy-admin-plugin
     */

    // Make sure we don't expose any info if called directly
    defined('ABSPATH') or die('Hey, you can\'t access this file');
    define ('WB_PLUGIN_PATH', plugin_dir_path(__FILE__));
    define ('WB_MODULES_PATH', plugin_dir_path(__FILE__). 'Inc/Modules/');
    define ('WB_MAX_UPLOAD_SIZE', 31457280);
    define ('WB_CURRENT_TIMEZONE', "Africa/Lagos");
    define ('WB_DATE_FORMAT', "Y-m-d");
    define ('WB_DATETIME_FORMAT', "Y-m-d H:i:s");

    define ('GALAXY_ADMIN_VERSION', '1.0' );
    define ('GALAXY_ADMIN_DB_VERSION', '1.0' );
    define ('GALAXY_APPLICATION_FORM_SLUG', 'application-form' );

    date_default_timezone_set(WB_CURRENT_TIMEZONE);


    // print_r($_SERVER['REQUEST_URI']);


    /* echo "before";
    echo defined('EXAMPLE');
    define ('EXAMPLE', 'ddd' );
    echo "after";
    echo defined('EXAMPLE'); */

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Composer
    require('vendor/autoload.php');

    // Helper
    require_once(WB_PLUGIN_PATH . 'Inc/Core/Helper.php');

    // Functions
    require_once(WB_PLUGIN_PATH . 'Inc/Functions.php');
    
    // Libraries
    
    // Core
    require_once(WB_PLUGIN_PATH . 'Inc/Core/AppError.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Core/Result.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Core/BaseEntity.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Core/BaseRepository.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Core/BaseController.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Core/Enqueue.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Core/SettingsLinks.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Core/Manager.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Core/SettingsApi.php');

    /*
     * Entities
     */
    require_once(WB_PLUGIN_PATH . 'Inc/Entity/Exam.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Entity/Student.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Entity/Guardian.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Entity/PurchaseHeader.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Entity/PurchaseItem.php');

    /*
     * Repository
     */
    require_once(WB_PLUGIN_PATH . 'Inc/Repository/ExamRepository.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Repository/StudentRepository.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Repository/GuardianRepository.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Repository/PurchaseHeaderRepository.php');
    require_once(WB_PLUGIN_PATH . 'Inc/Repository/PurchaseItemRepository.php');

    /*
     * Validators
     */
    require_once(WB_PLUGIN_PATH . 'Inc/Validators/DateRangeRule.php');
    
    /*
     * Modules
     */

    // Management Center Module
    require_once(WB_MODULES_PATH . 'ManagementCenter/Callbacks/ManagementCenterCallbacks.php');
    require_once(WB_MODULES_PATH . 'ManagementCenter/Controllers/ManagementCenterController.php');

    // Application Form Module
    require_once(WB_MODULES_PATH . 'ApplicationForm/Validators/StudentValidator.php');
    require_once(WB_MODULES_PATH . 'ApplicationForm/Validators/PurchaseHeaderValidator.php');
    require_once(WB_MODULES_PATH . 'ApplicationForm/Validators/PurchaseItemValidator.php');
    require_once(WB_MODULES_PATH . 'ApplicationForm/Validators/GuardianValidator.php');
    require_once(WB_MODULES_PATH . 'ApplicationForm/Callbacks/ApplicationFormCallbacks.php');
    require_once(WB_MODULES_PATH . 'ApplicationForm/Controllers/ApplicationFormController.php');

    // Initialization
    require_once(WB_PLUGIN_PATH . 'Inc/Init.php');

    register_activation_hook(__FILE__, array('Manager', 'activate'));
    register_deactivation_hook(__FILE__, array('Manager', 'deactivate'));
    register_uninstall_hook(__FILE__, array('Manager', 'uninstall'));

    
    add_action( 'init', function() {
        if (class_exists('Init')) {
            Init::register_services();
        }
    });