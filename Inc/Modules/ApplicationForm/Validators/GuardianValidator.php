<?php

    use Rakit\Validation\Validator;

    /**
     * @package Galaxy Admin Plugin
     */

    class GuardianValidator {

        private static $guardianValidator;
        private static $validator;

        private final function __construct() {
            self::$validator = new Validator();
        }

        public static function getInstance() {
            if (!isset(self::$guardianValidator)) {
                self::$guardianValidator = new GuardianValidator();
            }

            return self::$guardianValidator;
        }

        public static function validate(Guardian $entity) : array {
            $entityAsArray = $entity->toArray();

            $validation = GuardianValidator::$validator->make([
                // 'studentId' => $entityAsArray["studentId"],
                'lastName' => $entityAsArray["lastName"],
                'firstName' => $entityAsArray["firstName"],
                'country' => $entityAsArray["country"],
                'state' => $entityAsArray["state"],
                'educationalBackground' => $entityAsArray["educationalBackground"],
                'occupation' => $entityAsArray["occupation"],
                // 'currentPosition' => $entityAsArray["currentPosition"],
                'officeAddress' => $entityAsArray["officeAddress"],
                'emailAddress' => $entityAsArray["emailAddress"],
                'phoneNumber' => $entityAsArray["phoneNumber"]
            ], [
                // 'studentId' => 'required|integer',
                'lastName' => 'required',
                'firstName' => 'required',
                'country' => 'required',
                'state' => 'required',
                'educationalBackground' => 'required',
                'occupation' => 'required',
                // 'currentPosition' => 'required',
                'officeAddress' => 'required',
                'emailAddress' => 'required|email',
                'phoneNumber' => 'required'
            ]);

            $validation->setAliases([
                // 'studentId' => 'Student',
                'lastName' => 'Guardian last name',
                'firstName' => 'Guardian first name',
                'country' => 'Guardian country',
                'state' => 'Guardian state',
                'educationalBackground' => 'Guardian educational background',
                'occupation' => 'Guardian occupation',
                // 'currentPosition' => 'Guardian current position',
                'officeAddress' => 'Guardian office address',
                'emailAddress' => 'Guardian email address',
                'phoneNumber' => 'Guardian phone number'
            ]);

            $validation->setMessage('required', ":attribute is required");
            
            $validation->validate();

            if (!$validation->fails())
                return [];

            return $validation->errors()->toArray();
        }
    }