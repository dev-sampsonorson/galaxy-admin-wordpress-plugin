<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class Result {
        
        private $status;
        private $message;
        private $successResult;
        private $errorResult;
        
        private function __construct(bool $status, $successResult, string $key, $errorResult, string $message) {
            $this->status = $status;
            $this->message = $message;
            $this->successResult = $successResult;
            $this->errorResult = $errorResult;
            $this->key = $key;
        }

        public static function fromSuccess($successResult = null, string $message = '') {
            return new Result(true, $successResult, '', null, $message);
        }

        public static function fromError(string $key, string $message = '') {
            return new Result(false, null, $key, null, $message);
        }

        public static function fromErrorWithResult(string $key, $errorResult, string $message = '') {
            return new Result(false, null, $key, $errorResult, $message);
        }

        public function isSuccessful() {
            return $this->status;
        }

        public function getMessage() {
            return $this->message;
        }

        public function getResult() {
            if ($this->status)
                return $this->successResult;

            return $this->errorResult;
        }

        public function getSuccessResult() {
            return $this->successResult;
        }

        public function getErrorResult() {
            return $this->errorResult;
        }

        public function toArray() : array {
            return array(
                "success" => $this->status,
                "key" => $this->key,
                "message" => $this->message,
                "successResult" => $this->successResult,
                "errorResult" => $this->errorResult
            );
        }
    }