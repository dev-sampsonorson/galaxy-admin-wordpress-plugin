<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class Student extends BaseEntity {

        protected string $title;
        protected string $lastName;
        protected string $firstName;
        protected string $otherNames;
        protected string $gender;
        protected DateTime $birthDate;
        protected string $firstLanguage;
        protected string $country;
        protected string $state;
        protected string $phoneNumber;
        protected string $passportNumber;
        protected DateTime $expiryDate;
        protected string $permanentAddress;
        protected string $currentLevelOfStudy;
        protected string $nextLevelOfStudy;
        protected bool $deleted;

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