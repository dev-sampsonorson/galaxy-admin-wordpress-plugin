<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class PurchaseItem extends BaseEntity {

        protected int $purchaseId;
        protected int $examId;
        protected bool $examRegistration;
        protected bool $bookPurchase;
        protected bool $lessonEnrolment;
        protected DateTime $preferredExamDate;
        protected DateTime $alternativeExamDate;
        protected string $preferredExamLocation;
        protected string $alternativeExamLocation;
        protected float $examRegistrationPrice;
        protected float $bookPurchasePrice;
        protected float $lessonEnrolmentPrice;
        protected float $itemTotal;

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