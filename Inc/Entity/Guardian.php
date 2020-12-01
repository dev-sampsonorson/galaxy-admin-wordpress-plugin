<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class Guardian extends BaseEntity {

        protected string $studentId;
        protected string $lastName;
        protected string $firstName;
        protected string $country;
        protected string $state;
        protected string $educationalBackground;
        protected string $occupation;
        protected string $currentPosition;
        protected string $officeAddress;
        protected string $emailAddress;
        protected string $phoneNumber;

        public function __construct(array $config) {
            parent::__construct($config);

            $this->studentId = 0;
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
                "lastName" => $this->lastName,
                "firstName" => $this->firstName,
                "country" => $this->country,
                "state" => $this->state,
                "educationalBackground" => $this->educationalBackground,
                "occupation" => $this->occupation,
                "currentPosition" => $this->currentPosition,
                "officeAddress" => $this->officeAddress,
                "emailAddress" => $this->emailAddress,
                "phoneNumber" => $this->phoneNumber,
                "insertDate" => !is_null($this->insertDate) ? $this->insertDate->format(WB_DATETIME_FORMAT) : ''
            );
        }

    }