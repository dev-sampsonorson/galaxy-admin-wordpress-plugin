<?php
    /**
     * @package Galaxy Admin Plugin
     */

    class Manager extends BaseController {

        private const ADD = 1;
        private const DELETE = 1;

        private static $tables = array(
            BaseRepository::EXAM_TABLE_NAME => array(
                'primary_key' => 'id',
                'columns' => array(
                    'id' => 'int(11) NOT NULL AUTO_INCREMENT',
                    'examName' => 'tinytext NOT NULL',
                    'examRegistrationPrice' => 'double(12,2) NOT NULL',
                    'bookPurchasePrice' => 'double(12,2) NOT NULL',
                    'lessonEnrolmentPrice' => 'double(12,2) NOT NULL'
                    // 'update_date' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
                ),
                'data' => array(
                    array('id' => 1, 'examName' => 'TOEFL', 'examRegistrationPrice' => 100000.00, 'bookPurchasePrice' => 10000.00, 'lessonEnrolmentPrice' => 65000.00),
                    array('id' => 2, 'examName' => 'IELTS', 'examRegistrationPrice' => 85000.00, 'bookPurchasePrice' => 10000.00, 'lessonEnrolmentPrice' => 65000.00),
                    array('id' => 3, 'examName' => 'IELTS/UKVI', 'examRegistrationPrice' => 65000.00, 'bookPurchasePrice' => 10000.00, 'lessonEnrolmentPrice' => 65000.00),
                    //array('id' => 4, 'examName' => 'PEARSON', 'examRegistrationPrice' => 1000.00, 'bookPurchasePrice' => 1000.00, 'lessonEnrolmentPrice' => 1000.00), // TODO
                    array('id' => 4, 'examName' => 'SAT 1 or 2', 'examRegistrationPrice' => 65000.00, 'bookPurchasePrice' => 15000.00, 'lessonEnrolmentPrice' => 65000.00),
                    array('id' => 5, 'examName' => 'ACT', 'examRegistrationPrice' => 85000.00, 'bookPurchasePrice' => 15000.00, 'lessonEnrolmentPrice' => 100000.00),
                    array('id' => 6, 'examName' => 'GMAT', 'examRegistrationPrice' => 130000.00, 'bookPurchasePrice' => 20000.00, 'lessonEnrolmentPrice' => 135000.00),
                    array('id' => 7, 'examName' => 'GRE', 'examRegistrationPrice' => 120000.00, 'bookPurchasePrice' => 15000.00, 'lessonEnrolmentPrice' => 65000.00),
                    //array('id' => 9, 'examName' => 'ICDL', 'examRegistrationPrice' => 1000.00, 'bookPurchasePrice' => 1000.00, 'lessonEnrolmentPrice' => 1000.00), // TODO
                )
            ),
            BaseRepository::PURCHASE_HEADER_TABLE_NAME => array(
                'primary_key' => 'id',
                'columns' => array(
                    'id' => 'int(11) NOT NULL AUTO_INCREMENT',
                    'studentId' => 'int(11) NOT NULL',
                    'emailAddress' => 'tinytext NOT NULL',
                    'isPaid' => 'boolean NOT NULL DEFAULT false',
                    'total' => 'double(12,2) NOT NULL',
                    'insertDate' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
                )
            ),
            BaseRepository::PURCHASE_ITEM_TABLE_NAME => array(
                'primary_key' => 'id',
                'columns' => array(
                    'id' => 'int(11) NOT NULL AUTO_INCREMENT',
                    'purchaseId' => 'int(11) NOT NULL',
                    'examId' => 'int(11) NOT NULL',
                    'examRegistration' => 'boolean NOT NULL DEFAULT false',
                    'bookPurchase' => 'boolean NOT NULL DEFAULT false',
                    'lessonEnrolment' => 'boolean NOT NULL DEFAULT false',
                    'preferredExamDate' => 'DATETIME NOT NULL',
                    'alternativeExamDate' => 'DATETIME NOT NULL',
                    'preferredExamLocation' => 'tinytext NOT NULL',
                    'alternativeExamLocation' => 'tinytext NOT NULL',                    
                    'examRegistrationPrice' => 'double(12,2) NOT NULL',
                    'bookPurchasePrice' => 'double(12,2) NOT NULL',
                    'lessonEnrolmentPrice' => 'double(12,2) NOT NULL',
                    'itemTotal' => 'double(12,2) NOT NULL'
                )
            ),
            BaseRepository::STUDENT_TABLE_NAME => array(
                'primary_key' => 'id',
                'columns' => array(
                    'id' => 'int(11) NOT NULL AUTO_INCREMENT',
                    'title' => 'tinytext NOT NULL',
                    'lastName' => 'tinytext NOT NULL',
                    'firstName' => 'tinytext NOT NULL',
                    'otherNames' => 'tinytext NOT NULL',
                    'gender' => 'boolean NOT NULL',
                    'birthDate' => 'DATETIME NOT NULL',
                    'firstLanguage' => 'varchar(255) NOT NULL',
                    'country' => 'tinytext NOT NULL',
                    'state' => 'tinytext NOT NULL',
                    'phoneNumber' => 'tinytext NOT NULL',
                    'passportNumber' => 'tinytext NOT NULL',
                    'expiryDate' => 'DATETIME NOT NULL',
                    'permanentAddress' => 'varchar(2000) NOT NULL',
                    'currentLevelOfStudy' => 'varchar(1000) NOT NULL',
                    'nextLevelOfStudy' => 'varchar(1000) NOT NULL',                    
                    'insertDate' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP',
                    'deleted' => 'boolean NOT NULL DEFAULT false'
                )
            ),
            BaseRepository::GUARDIAN_TABLE_NAME => array(
                'primary_key' => 'id',
                'columns' => array(
                    'id' => 'int(11) NOT NULL AUTO_INCREMENT',
                    'studentId' => 'int(11) NOT NULL',
                    'lastName' => 'tinytext NOT NULL',
                    'firstName' => 'tinytext NOT NULL',
                    'country' => 'tinytext NOT NULL',
                    'state' => 'tinytext NOT NULL',
                    'educationalBackground' => 'varchar(2000) NOT NULL',
                    'occupation' => 'varchar(2000) NOT NULL',
                    'currentPosition' => 'varchar(2000) NOT NULL',
                    'officeAddress' => 'varchar(2000) NOT NULL',
                    'emailAddress' => 'tinytext NOT NULL',
                    'phoneNumber' => 'tinytext NOT NULL',
                    'insertDate' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
                )
            )
        );

        public static function activate() {
            flush_rewrite_rules();

            foreach(Manager::$tables as $table_name => $value) {
                if (!Manager::createTable($table_name, Manager::$tables[$table_name])) 
                    continue;

                if(!array_key_exists('data', Manager::$tables[$table_name]) || count($value['data']) == 0)
                    continue;
                
                if(!Manager::loadDefaultData($table_name, Manager::$tables[$table_name]))
                    exit();
            }
            
            // when creating tables
            // add_option('galaxy_admin_db_version', GALAXY_ADMIN_DB_VERSION);
            Manager::addOrRemoveGalaxyAdminDbVersion(self::ADD);
        }

        private static function addOrRemoveGalaxyAdminDbVersion($operation) {
            if ($operation == self::ADD) {
                add_option('galaxy_admin_db_version', GALAXY_ADMIN_DB_VERSION);
            } 
            
            if ($operation == self::DELETE) {
                delete_option('galaxy_admin_db_version');
            }
        }

        private static function createTable($table_name, $table_cols) {
            
            try {
                if (Manager::tableExists($table_name)) return false;
    
                $sql = "CREATE TABLE $table_name (";
                foreach($table_cols['columns'] as $key => $value) {
                    $sql .= "$key $value, ";
                }
    
                $sql .= "PRIMARY KEY (" . $table_cols['primary_key'] . ")";
                $sql .= ")";            
    
                if (!isset($sql)) return;
    
                dbDelta($sql);
    
                // add_option('galaxy_admin_db_version', GALAXY_ADMIN_DB_VERSION);
                Manager::addOrRemoveGalaxyAdminDbVersion(self::ADD);
            }
            catch(Exception $e) {
                return false;
            }

            return true;
        }

        private static function tableExists($table_name) {
            global $wpdb;
            return $wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") == $table_name;
        }

        private static function hasData($table_name) {
            global $wpdb;            
            return $wpdb->get_var("SELECT COUNT(*) FROM " . $table_name) > 0;
        }

        private static function removeTable($table_name) {
            global $wpdb;

            $sql = "DROP TABLE IF EXISTS " . $table_name;

            return $wpdb->query($sql);
        }

        private static function loadDefaultData($table_name, $table_cols) {            
            global $wpdb; 

            try {

                if(!array_key_exists('data', Manager::$tables[$table_name]))
                    return false;  
                
                if (!Manager::tableExists($table_name)) return false;
                if (Manager::hasData($table_name)) return false;
    
                // $query = "INSERT INTO $table_name (`review_cost_type_id`, `review_cost_type`) VALUES (%d, %s)";
    
                foreach($table_cols['data'] as $row) {
                    $query_template = "";
                    $value_template = "";
                    $query_template = "INSERT INTO $table_name (";

                    foreach($row as $key => $value) {
                        $isString = in_array(trim(gettype($value)), array("string"));
                        $isNumber = in_array(trim(gettype($value)), array("integer", "double", "float"));
                        $query_template .= "`{$key}`,";
                        $value_template .= $isString == true ? "%s," : "";
                        $value_template .= $isNumber == true  ? "%d," : "";
                    }
    
                    $query_template = substr(trim($query_template), 0, -1);
                    $value_template = substr(trim($value_template), 0, -1);
    
                    $query_template .= ") VALUES (";
                    $query_template .= $value_template;
                    $query_template .= ")";

                    $prepare_query = $wpdb->prepare($query_template, array_values($row));
                    
                    $result = $wpdb->query($prepare_query);
                }

            } catch(Exception $e) {
                return false;
            }
            
            return $result ? true : false;
        }

        public static function deactivate() {
            flush_rewrite_rules();
        }

        public static function uninstall() {
            // when deleting tables
            // delete_option('galaxy_admin_db_version');
            Manager::addOrRemoveGalaxyAdminDbVersion(self::DELETE);

            foreach(Manager::$tables as $table_name => $value) {
                $result = Manager::removeTable($table_name, Manager::$tables[$table_name]);
            }
        }

    }