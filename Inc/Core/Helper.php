<?php

    use Westsworld\TimeAgo;

    /**
     * @package CarpentryReviewPlugin
     */

    class Helper {     
        
        public function __construct() {
            
        }

        public static function timeAgo(?DateTime $date): string {
            if (empty($date) || $date === null) return '';

            $timeAgo = new TimeAgo();
            return $timeAgo->inWords($date);
        }

        public static function toDateTimeFromString(string $value, string $format = null): ?DateTime {
            if (empty($value)) return null;

            if ($format == null) {
                return new DateTime($value, new DateTimeZone(WB_CURRENT_TIMEZONE));
            }
        
            return DateTime::createFromFormat($format, $value, new DateTimeZone(WB_CURRENT_TIMEZONE));
        }

        /**
         *
         * Sanatize a single var according to $type.
         * Allows for static calling to allow simple sanatization
         */
        public static function sanitize($var, $type)
        {
            $flags = NULL;
            switch($type)
            {
                    case 'url':
                            $filter = FILTER_SANITIZE_URL;
                    break;
                    case 'int':
                            $filter = FILTER_SANITIZE_NUMBER_INT;
                    break;
                    case 'float':
                            $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                            $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
                    break;
                    case 'email':
                            $var = substr($var, 0, 254);
                            $filter = FILTER_SANITIZE_EMAIL;
                    break;
                    case 'string':
                    default:
                            $filter = FILTER_SANITIZE_STRING;
                            $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
                    break;
        
            }
            $output = filter_var($var, $filter, $flags);            
            return($output);
        }
    }