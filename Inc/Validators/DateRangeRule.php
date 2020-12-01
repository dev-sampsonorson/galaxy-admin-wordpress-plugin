<?php

    use Rakit\Validation\Rule;

    class DateRangeRule extends Rule {

        protected $message = ":attribute :value is not in range";
        protected $fillableParams = ['format', 'min', 'max'];

        public function __constructor() {

        }

        public function check($value): bool {
            try {
                // make sure required parameters exists
                $this->requireParameters(['format', 'min', 'max']);
    
                // getting parameters
                $format = $this->parameter('format');
                $min = $this->parameter('min');
                $max = $this->parameter('max');
    
                $todayObject = new DateTime("now", new DateTimeZone(WB_CURRENT_TIMEZONE));
                $today = DateTime::createFromFormat($format, $todayObject->format($format), new DateTimeZone(WB_CURRENT_TIMEZONE));

                $entryDate = DateTime::createFromFormat($format, $value, new DateTimeZone(WB_CURRENT_TIMEZONE));
                $minDate = DateTime::createFromFormat($format, $min, new DateTimeZone(WB_CURRENT_TIMEZONE));
                $maxDate = DateTime::createFromFormat($format, $max, new DateTimeZone(WB_CURRENT_TIMEZONE));

                if (empty($min) && empty($max))
                    return false;

                if (empty($min) && $entryDate > $maxDate)
                    return false;

                if (empty($max) && $entryDate < $minDate)
                    return false;
    
                if ((!empty($min) && !empty($max)) && ($entryDate < $minDate || $entryDate > $maxDate))
                    return false;
    
                return true;
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }

    }