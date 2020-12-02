<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class Exam extends BaseEntity {

        protected $examName; // string 
        protected $examRegistrationPrice; // float 
        protected $bookPurchasePrice; // float
        protected $lessonEnrolmentPrice; // float

        public function __construct(array $config) {
            parent::__construct($config);
        }

        public function validate() : bool {
            return true;
        }

        public function toArray() : array {
            return array(
                "id" => $this->id,
                "examName" => $this->examName,
                "examRegistrationPrice" => $this->examRegistrationPrice,
                "bookPurchasePrice" => $this->bookPurchasePrice,
                "lessonEnrolmentPrice" => $this->lessonEnrolmentPrice
            );
        }

    }