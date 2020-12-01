<?php

    use Rakit\Validation\Validator;

    /**
     * @package Galaxy Admin Plugin
     */

    class StudentValidator {

        private static $studentValidator;
        private static $validator;

        private final function __construct() {
            self::$validator = new Validator();

            self::$validator->addValidator('date_range', new DateRangeRule());
        }

        public static function getInstance() {
            if (!isset(self::$studentValidator)) {
                self::$studentValidator = new StudentValidator();
            }

            return self::$studentValidator;
        }

        public function validate(Student $entity) : array {
            $entityAsArray = $entity->toArray();
            $today = new DateTime("now", new DateTimeZone(WB_CURRENT_TIMEZONE));

            $validation = StudentValidator::$validator->make([
                'title' => $entityAsArray["title"],
                'lastName' => $entityAsArray["lastName"],
                'firstName' => $entityAsArray["firstName"],
                'otherNames' => $entityAsArray["otherNames"],
                'gender' => $entityAsArray["gender"],
                'birthDate' => $entityAsArray["birthDate"],
                'firstLanguage' => $entityAsArray["firstLanguage"],
                'country' => $entityAsArray["country"],
                'state' => $entityAsArray["state"],
                'phoneNumber' => $entityAsArray["phoneNumber"],
                'passportNumber' => $entityAsArray["passportNumber"],
                'expiryDate' => $entityAsArray["expiryDate"],
                'permanentAddress' => $entityAsArray["permanentAddress"],
                'currentLevelOfStudy' => $entityAsArray["currentLevelOfStudy"],
                'nextLevelOfStudy' => $entityAsArray["nextLevelOfStudy"]
            ], [
                'title' => 'required',
                'lastName' => 'required',
                'firstName' => 'required',
                'otherNames' => '',
                'gender' => 'required',
                'birthDate' => "required|date:Y-m-d|date_range:Y-m-d,," . $today->format(WB_DATE_FORMAT),
                'firstLanguage' => 'required',
                'country' => 'required',
                'state' => 'required',
                'phoneNumber' => 'required',
                'passportNumber' => 'required',
                'expiryDate' => "required|date:Y-m-d|date_range:Y-m-d," . $today->format(WB_DATE_FORMAT) . ",",
                'permanentAddress' => 'required',
                'currentLevelOfStudy' => 'required',
                'nextLevelOfStudy' => 'required'
            ]);

            $validation->setAliases([
                'title' => 'Student title',
                'lastName' => 'Student last name',
                'firstName' => 'Student first name',
                'otherNames' => 'Student other names',
                'gender' => 'Student gender',
                'birthDate' => 'Student birth date',
                'firstLanguage' => 'Student first language',
                'country' => 'Student country',
                'state' => 'Student state',
                'phoneNumber' => 'Student phone number',
                'passportNumber' => 'Student passport number',
                'expiryDate' => 'Student expiry date',
                'permanentAddress' => 'Student permanent address',
                'currentLevelOfStudy' => 'Student current level of study',
                'nextLevelOfStudy' => 'Student next level of study'
            ]);

            $validation->setMessage('required', ":attribute is required");
            
            $validation->validate();

            if (!$validation->fails())
                return [];

            return $validation->errors()->toArray();
        }
    }