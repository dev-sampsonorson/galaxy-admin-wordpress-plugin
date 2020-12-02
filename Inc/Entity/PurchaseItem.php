<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class PurchaseItem extends BaseEntity {

        protected $purchaseId; // int
        protected $examId; // int
        protected $examRegistration; // bool
        protected $bookPurchase; // bool
        protected $lessonEnrolment; // bool
        protected $preferredExamDate; // DateTime
        protected $alternativeExamDate; // DateTime
        protected $preferredExamLocation; // string
        protected $alternativeExamLocation; // string
        protected $examRegistrationPrice; // float
        protected $bookPurchasePrice; // float
        protected $lessonEnrolmentPrice; // float
        protected $itemTotal; // float

        public function __construct(array $config) {
            parent::__construct($config);

            $this->purchaseId = 0;
        }

        public function setPurchaseId(int $purchaseId) {
            $this->purchaseId = $purchaseId;
        }

        public function validate() : bool {
            return true;
        }

        public function toArray() : array {
            return array(
                "id" => $this->id,
                "purchaseId" => $this->purchaseId,
                "examId" => $this->examId,
                "examRegistration" => $this->examRegistration,
                "bookPurchase" => $this->bookPurchase,
                "lessonEnrolment" => $this->lessonEnrolment,
                "preferredExamDate" => $this->preferredExamDate->format(WB_DATE_FORMAT),
                "alternativeExamDate" => $this->alternativeExamDate->format(WB_DATE_FORMAT),
                "preferredExamLocation" => $this->preferredExamLocation,
                "alternativeExamLocation" => $this->alternativeExamLocation,
                "examRegistrationPrice" => floatval($this->examRegistrationPrice),
                "bookPurchasePrice" => floatval($this->bookPurchasePrice),
                "lessonEnrolmentPrice" => floatval($this->lessonEnrolmentPrice),
                "itemTotal" => floatval($this->itemTotal)
            );
        }

    }