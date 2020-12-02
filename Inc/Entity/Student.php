<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class Student extends BaseEntity {

        protected $title; // string 
        protected $lastName; // string 
        protected $firstName; // string 
        protected $otherNames; // string 
        protected $gender; // string 
        protected $birthDate; // DateTime
        protected $firstLanguage; // string 
        protected $country; // string 
        protected $state; // string 
        protected $phoneNumber; // string 
        protected $passportNumber; // string 
        protected $expiryDate; // DateTime
        protected $permanentAddress; // string 
        protected $currentLevelOfStudy; // string 
        protected $nextLevelOfStudy; // string 
        protected $deleted; // bool

        public function __construct(array $config) {
            parent::__construct($config);

            $this->deleted = false;
        }

        public function validate() : bool {
            return true;
        }

        public function toArray() : array {
            return array(
                "id" => $this->id,
                "title" => $this->title,
                "lastName" => $this->lastName,
                "firstName" => $this->firstName,
                "otherNames" => $this->otherNames,
                "gender" => $this->gender,
                "birthDate" => $this->birthDate->format(WB_DATE_FORMAT),
                "firstLanguage" => $this->firstLanguage,
                "country" => $this->country,
                "state" => $this->state,
                "phoneNumber" => $this->phoneNumber,
                "passportNumber" => $this->passportNumber,
                "expiryDate" => $this->expiryDate->format(WB_DATE_FORMAT),
                "permanentAddress" => $this->permanentAddress,
                "currentLevelOfStudy" => $this->currentLevelOfStudy,
                "nextLevelOfStudy" => $this->nextLevelOfStudy,
                "insertDate" => !is_null($this->insertDate) ? $this->insertDate->format(WB_DATETIME_FORMAT) : '',
                "deleted" => $this->deleted
            );
        }

    }