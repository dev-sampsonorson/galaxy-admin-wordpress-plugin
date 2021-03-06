<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class PurchaseHeader extends BaseEntity {

        protected $studentId; // int 
        protected $emailAddress; // string
        protected $total; // float
        protected $isPaid = false; // bool
        protected $items = []; // array

        public function __construct(array $config) {
            parent::__construct($config);

            $this->studentId = 0;
        }

        public function addItem(PurchaseItem $item) {
            array_push($this->items, $item);
        }

        public function getItems() {
            return $this->items;
        }

        public function setStudentId($studentId) {
            $this->studentId = $studentId;
        }

        public function validate() : bool {
            return true;
        }

        public function toArray() : array {
            return array(
                "id" => $this->id,
                "studentId" => $this->studentId,
                "emailAddress" => $this->emailAddress,
                "isPaid" => $this->isPaid,
                "total" => floatval($this->total),
                "insertDate" => !is_null($this->insertDate) ? $this->insertDate->format(WB_DATETIME_FORMAT) : ''
            );
        }

    }