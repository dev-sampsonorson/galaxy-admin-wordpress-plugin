<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class Exam extends BaseEntity {

        protected string $examName;
        protected float $examRegistrationPrice;
        protected float $bookPurchasePrice;
        protected float $lessonEnrolmentPrice;

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