<?php

    /**
     * @package Galaxy Admin Plugin
     */

    abstract class BaseRepository {
        
        public const EXAM_TABLE_NAME = 'galaxy_exam';
        public const PURCHASE_HEADER_TABLE_NAME = 'galaxy_purchase_header';
        public const PURCHASE_ITEM_TABLE_NAME = 'galaxy_purchase_item';
        public const STUDENT_TABLE_NAME = 'galaxy_student';
        public const GUARDIAN_TABLE_NAME = 'galaxy_guardian';

        protected $wpdb;

        public function __construct() {
            global $wpdb;

            $this->wpdb = $wpdb;
        }

        public abstract function getTableName();

    }