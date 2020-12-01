<?php

    use Rakit\Validation\Validator;

    /**
     * @package Galaxy Admin Plugin
     */

    class PurchaseHeaderValidator {

        private static $purchaseHeaderValidator;
        private static $validator;

        private final function __construct() {
            self::$validator = new Validator();
        }

        public static function getInstance() {
            if (!isset(self::$purchaseHeaderValidator)) {
                self::$purchaseHeaderValidator = new PurchaseHeaderValidator();
            }

            return self::$purchaseHeaderValidator;
        }

        public static function validate(PurchaseHeader $entity) : array {
            $entityAsArray = $entity->toArray();

            $validation = PurchaseHeaderValidator::$validator->make([
                // 'studentId' => $entityAsArray["studentId"],
                'emailAddress' => $entityAsArray["emailAddress"],
                'total' => $entityAsArray["total"]
            ], [
                // 'studentId' => 'required|integer',
                'emailAddress' => 'required|email',
                'total' => 'required|numeric'
            ]);

            $validation->setAliases([
                // 'studentId' => 'Student',
                'emailAddress' => 'Email address',
                'total' => 'Purchase total'
            ]);

            $validation->setMessage('required', ":attribute is required");
            
            $validation->validate();

            if ($validation->fails())
                return $validation->errors()->toArray();

            return [];
        }
    }