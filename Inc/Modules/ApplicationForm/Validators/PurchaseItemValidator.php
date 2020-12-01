<?php

    use Rakit\Validation\Validator;

    /**
     * @package Galaxy Admin Plugin
     */

    class PurchaseItemValidator {

        private static $purchaseItemValidator;
        private static $validator;

        private final function __construct() {
            self::$validator = new Validator();

            self::$validator->addValidator('date_range', new DateRangeRule());
        }

        public static function getInstance() {
            if (!isset(self::$purchaseItemValidator)) {
                self::$purchaseItemValidator = new PurchaseItemValidator();
            }

            return self::$purchaseItemValidator;
        }


        public static function validate(PurchaseItem $entity) : array {
            $entityAsArray = $entity->toArray();
            $today = new DateTime("now", new DateTimeZone(WB_CURRENT_TIMEZONE));

            $validation = PurchaseItemValidator::$validator->make([
                // 'purchaseId' => $entityAsArray["purchaseId"],
                'examId' => $entityAsArray["examId"],
                'examRegistration' => $entityAsArray["examRegistration"],
                'bookPurchase' => $entityAsArray["bookPurchase"],
                'lessonEnrolment' => $entityAsArray["lessonEnrolment"],
                'preferredExamDate' => $entityAsArray["preferredExamDate"],
                'alternativeExamDate' => $entityAsArray["alternativeExamDate"],
                'preferredExamLocation' => $entityAsArray["preferredExamLocation"],
                'alternativeExamLocation' => $entityAsArray["alternativeExamLocation"],
                'examRegistrationPrice' => $entityAsArray["examRegistrationPrice"],
                'bookPurchasePrice' => $entityAsArray["bookPurchasePrice"],
                'lessonEnrolmentPrice' => $entityAsArray["lessonEnrolmentPrice"],
                'itemTotal' => $entityAsArray["itemTotal"]
            ], [
                // 'purchaseId' => 'required',
                'examId' => 'required',
                'examRegistration' => 'boolean',
                'bookPurchase' => 'boolean',
                'lessonEnrolment' => 'boolean',
                'preferredExamDate' => !$entityAsArray["examRegistration"] ? '' : "required|date:Y-m-d|date_range:Y-m-d," . $today->format(WB_DATE_FORMAT) . ",",
                'alternativeExamDate' => !$entityAsArray["examRegistration"] ? '' : "required|date:Y-m-d|date_range:Y-m-d," . $today->format(WB_DATE_FORMAT) . ",",
                'preferredExamLocation' => !$entityAsArray["examRegistration"] ? '' : 'required',
                'alternativeExamLocation' => !$entityAsArray["examRegistration"] ? '' : 'required',
                'examRegistrationPrice' => 'required|numeric',
                'bookPurchasePrice' => 'required|numeric',
                'lessonEnrolmentPrice' => 'required|numeric',
                'itemTotal' => 'required|numeric'
            ]);

            $validation->setAliases([
                // 'purchaseId' => 'Purchase',
                'examId' => 'Exam',
                'examRegistration' => 'Exam registration service',
                'bookPurchase' => 'Book purchase service',
                'lessonEnrolment' => 'Lesson enrolment service',
                'preferredExamDate' => 'Preferred exam date',
                'alternativeExamDate' => 'Alternative exam date',
                'preferredExamLocation' => 'Preferred exam location',
                'alternativeExamLocation' => 'Alternative exam location',
                'examRegistrationPrice' => 'Exam registration price',
                'bookPurchasePrice' => 'Book purchase price',
                'lessonEnrolmentPrice' => 'Lesson enrolment price',
                'itemTotal' => 'Purchase item total'
            ]);

            $validation->setMessage('required', ":attribute is required");
            
            $validation->validate();

            if (!$validation->fails())
                return [];

            return $validation->errors()->toArray();
        }
    }