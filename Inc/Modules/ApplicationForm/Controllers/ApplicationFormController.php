<?php
/* if (!session_id())
    session_start(); */

/**
 * @package Galaxy Admin Plugin
 */

use Rakit\Validation\Validator;

class ApplicationFormController extends BaseController
{
    public $settings;

    private $post_id;
    private $validator;
    private $examRepository; // ExamRepository
    private $studentValidator; // StudentValidator
    private $guardianValidator; // GuardianValidator
    private $purchaseHeaderValidator; // PurchaseHeaderValidator
    private $purchaseItemValidator; // PurchaseItemValidator

    private const EXAM_REGISTRATION = 'exam-registration';
    private const BOOK_PURCHASE = 'book-purchase';
    private const LESSON_ENROLMENT = 'lesson-enrolment';

    public function register()
    {
        $post = get_page_by_title('Application Form');
        $this->post_id = isset($post)? $post->ID : 0;

        $this->validator = new Validator();
        $this->studentValidator = StudentValidator::getInstance();
        $this->guardianValidator = GuardianValidator::getInstance();
        $this->purchaseHeaderValidator = PurchaseHeaderValidator::getInstance();
        $this->purchaseItemValidator = PurchaseItemValidator::getInstance();

        $this->settings = new SettingsApi();
        $this->callbacks = new ApplicationFormCallbacks($this);

        $this->examRepository = new ExamRepository();
        $this->guardianRepository = new GuardianRepository();
        $this->purchaseHeaderRepository = new PurchaseHeaderRepository();
        $this->purchaseItemRepository = new PurchaseItemRepository();
        $this->studentRepository = new StudentRepository();

        add_shortcode('galaxy-application-form', array($this->callbacks, 'renderApplicationForm'));

        if ( is_admin() ) {
            add_action('wp_ajax_getExamList', array($this, 'getExamList'));
            add_action('wp_ajax_nopriv_getExamList', array($this, 'getExamList'));
            
            add_action('wp_ajax_saveApplication', array($this, 'saveApplication'));
            add_action('wp_ajax_nopriv_saveApplication', array($this, 'saveApplication'));
            
            add_action('wp_ajax_generatePaymentRef', array($this, 'generatePaymentRef'));
            add_action('wp_ajax_nopriv_generatePaymentRef', array($this, 'generatePaymentRef'));
            
            add_action('wp_ajax_verifyTransaction', array($this, 'verifyTransaction'));
            add_action('wp_ajax_nopriv_verifyTransaction', array($this, 'verifyTransaction'));
        } else {
            // Add non-Ajax front-end action hooks here
        }
    }

    public function getExamList() {
        try {
            if (!DOING_AJAX || !check_ajax_referer('getExamList_nonce', 'nonce', false)) {
                return $this->return_json_error(500, 'Invalid request');
            }

            $result = BaseController::toEntityArray($this->examRepository->getAll());
            
            return $this->return_json(200, $result);
        } catch(Exception $e) {
            return return_json_error(404, 'Exams Not Found');
        }
    }    

    public function generatePaymentRef() {
        try {
            if (!DOING_AJAX || !check_ajax_referer('generatePaymentRef_nonce', 'nonce', false)) {
                return $this->return_json_error(500, 'Invalid request');
            }

            $now = new DateTime('now');
            $ref = $now->format('Y') . uniqid() . $now->format('m') . $now->format('d') .
                   $now->format('H') . $now->format('i') . $now->format('s') .
                   strval(microtime(true) * 10000);
                
            return $this->return_json(200, array( "ref" => $ref));
        } catch(Exception $e) {
            return return_json_error(404, 'Unable to process payment');
        }

    }

    public function verifyTransaction() {
        try {
            if (!DOING_AJAX || !check_ajax_referer('verifyTransaction_nonce', 'nonce', false)) {
                return $this->return_json_error(500, 'Invalid request');
            }

            $paymentReference = isset($_GET["reference"]) ? $_GET["reference"] : "";

            $curl = curl_init();
  
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.paystack.co/transaction/verify/". $paymentReference,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . GALAXY_SECRET_KEY,
                "Cache-Control: no-cache",
              ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            
            if ($err) {
              echo "cURL Error #:" . $err;
              throw new Exception('CURL error');
            }
            
            $responseAsArray = json_decode($response, true);

            return $this->return_json(200, $responseAsArray);
        } catch(Exception $e) {
            return return_json_error(404, 'Unable to process payment');
        }

    }

    public function saveApplication() {
        try {
            if (!DOING_AJAX || !check_ajax_referer('saveApplication_nonce', 'nonce', false)) {
                return $this->return_json_error(500, 'Invalid request');
            }
            
            $validationResultList = [];
            $applicationData = json_decode(stripslashes($_POST['data']));

            // Get Entities
            $studentEntity = $this->getStudentEntity($applicationData[1]);
            $guardianEntity = $this->getGuardianEntity($applicationData[2]);
            $purchaseEntity = $this->getPurchaseEntity( $applicationData[0]);

            // Validate Entities
            $studentValidationResult = $this->validateStudentData($studentEntity);
            $guardianValidationResult = $this->validateGuardianData($guardianEntity);
            $purchaseValidationResult = $this->validatePurchaseData($purchaseEntity);

            if (!$purchaseValidationResult->isSuccessful())
                $validationResultList[0] = $purchaseValidationResult->getErrorResult();

            if (!$studentValidationResult->isSuccessful())
                $validationResultList[1] = $studentValidationResult->getErrorResult();

            if (!$guardianValidationResult->isSuccessful())
                $validationResultList[2] = $guardianValidationResult->getErrorResult();

            if (count($validationResultList) > 0) {
                $mergedValidationResult = Result::fromErrorWithResult(
                    AppError::ERROR_VALIDATION, 
                    $validationResultList, 
                    "Invalid information provided in application"
                );

                return $this->return_json_error(400, $mergedValidationResult->getMessage(), $mergedValidationResult->toArray());
            }

            
            // Save Application
            $studentResult = $this->saveStudentData($studentEntity);

            if (!$studentResult->isSuccessful())
                return $this->return_json_error(400, $studentResult->getMessage(), $studentResult->toArray());

            $studentId = $studentResult->getSuccessResult()->getId();

            $guardianResult = $this->saveGuardianData($studentId, $guardianEntity);

            if (!$guardianResult->isSuccessful())
                return $this->return_json_error(400, $guardianResult->getMessage(), $guardianResult->toArray());

            $purchaseHeaderResult = $this->savePurchaseData($studentId, $purchaseEntity);

            if (!$purchaseHeaderResult->isSuccessful())
                return $this->return_json_error(400, $purchaseHeaderResult->getMessage(), $purchaseHeaderResult->toArray());
            
            return $this->return_json(200, array(
                $purchaseHeaderResult->getResult()->toArray(), 
                $studentResult->getResult()->toArray(),
                $guardianResult->getResult()->toArray()
            ));
        } catch(Exception $e) {
            // echo 'Message: ' .$e->getMessage();
            /* $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
    .': <b>'.$this->getMessage().'</b> is not a valid E-Mail address'; */
            return $this->return_json_error(400, 'Unable to save application');
        }
    }

    private function getPurchaseEntity(object $data): PurchaseHeader {
        $entity = new PurchaseHeader(array(
            // "studentId" => $studentId,
            "emailAddress" => $data->emailAddress,
            "isPaid" => true,
            "total" => floatval($data->total)
        ));

        foreach($data->purchases as $key => $value) {
            $examEntity = $this->examRepository->getById($value->examId);
            $examEntityAsArray = $examEntity->toArray();

            $item = new PurchaseItem(array(
                "examId" => $value->examId,
                "examRegistration" => in_array(self::EXAM_REGISTRATION, $value->selectedServices, true),
                "bookPurchase" => in_array(self::BOOK_PURCHASE, $value->selectedServices, true),
                "lessonEnrolment" => in_array(self::LESSON_ENROLMENT, $value->selectedServices, true),
                "preferredExamDate" => Helper::toDateTimeFromString($value->preferredExamDate),
                "alternativeExamDate" => Helper::toDateTimeFromString($value->alternativeExamDate),
                "preferredExamLocation" => $value->preferredExamLocation,
                "alternativeExamLocation" => $value->alternativeExamLocation,
                "examRegistrationPrice" => $examEntityAsArray["examRegistrationPrice"],
                "bookPurchasePrice" => $examEntityAsArray["bookPurchasePrice"],
                "lessonEnrolmentPrice" => $examEntityAsArray["lessonEnrolmentPrice"],
                "itemTotal" => floatval($value->itemTotal),
            ));

            $entity->addItem($item);
        }

        return $entity;
    }

    private function getStudentEntity(object $data): Student {
        return new Student(array(
            "title" => $data->title,
            "lastName" => $data->lastName,
            "firstName" => $data->firstName,
            "otherNames" => $data->otherName,
            "gender" => $data->gender,
            "birthDate" => Helper::toDateTimeFromString($data->birthDate, WB_DATE_FORMAT),
            "firstLanguage" => $data->firstLanguage,
            "country" => $data->country,
            "state" => $data->state,
            "phoneNumber" => $data->phoneNumber,
            "passportNumber" => $data->passportNumber,
            "expiryDate" => Helper::toDateTimeFromString($data->passportExpiryDate, WB_DATE_FORMAT),
            "permanentAddress" => $data->permanentAddress,
            "currentLevelOfStudy" => $data->currentLevelOfStudy,
            "nextLevelOfStudy" => $data->nextLevelOfStudy
        ));
    }

    private function getGuardianEntity(object $data): Guardian {
        return new Guardian(array(
            // "studentId" => $studentId,
            "lastName" => $data->lastName,
            "firstName" => $data->firstName,
            "country" => $data->country,
            "state" => $data->state,
            "educationalBackground" => $data->educationalBackground,
            "occupation" => $data->occupation,
            "currentPosition" => $data->currentPosition,
            "officeAddress" => $data->officeAddress,
            "emailAddress" => $data->emailAddress,
            "phoneNumber" => $data->phoneNumber
        ));        
    }

    private function validateStudentData(Student $entity): Result {
        $result = $this->studentValidator->validate($entity);
        
        if (count($result) > 0)
            return Result::fromErrorWithResult(AppError::ERROR_VALIDATION, $result, "Invalid personal information");

        return Result::fromSuccess();
    }

    private function validatePurchaseData(PurchaseHeader $entity): Result {
        $result = $this->purchaseHeaderValidator->validate($entity);
        
        if (count($result) > 0)
            return Result::fromErrorWithResult(AppError::ERROR_VALIDATION, $result, "Invalid purchase information");

        $purchaseItems = $entity->getItems();

        foreach($purchaseItems as $item) {
            $purchaseItemValResult = $this->purchaseItemValidator->validate($item);

            if (count($purchaseItemValResult) > 0)
                return Result::fromErrorWithResult(AppError::ERROR_VALIDATION, $purchaseItemValResult, "Invalid purchase item information");
        }

        return Result::fromSuccess();        
    }

    private function validateGuardianData(Guardian $entity): Result {        
        $result = $this->guardianValidator->validate($entity);
        
        if (count($result) > 0)
            return Result::fromErrorWithResult(AppError::ERROR_VALIDATION, $result, "Invalid guardian information");

        return Result::fromSuccess();
    }

    private function saveStudentData(Student $entity): Result {
        $student = $this->studentRepository->insert($entity);        
        
        if ($student == null)
            return Result::fromError(AppError::ERROR_GENERAL, "Unable to save student information");

        return Result::fromSuccess($student);
    }

    private function savePurchaseData(int $studentId, PurchaseHeader $entity): Result {
        $entity->setStudentId($studentId);
        
        $purchaseHeader = $this->purchaseHeaderRepository->insert($entity);
        
        if ($purchaseHeader == null)
            return Result::fromError(AppError::ERROR_GENERAL, "Unable to save purchase information");

        $purchaseItems = $purchaseHeader->getItems();

        foreach($purchaseItems as $item) {
            $item->setPurchaseId($purchaseHeader->getId());
            $purchaseItem = $this->purchaseItemRepository->insert($item);
        
            if ($purchaseItem == null)
                return Result::fromError(AppError::ERROR_GENERAL, "Unable to save purchase item information");
        }

        return Result::fromSuccess($purchaseHeader);
    }

    private function saveGuardianData(int $studentId, Guardian $entity): Result {
        $entity->setStudentId($studentId);

        $guardian = $this->guardianRepository->insert($entity);  
        
        if ($guardian == null)
            return Result::fromError(AppError::ERROR_GENERAL, "Unable to save guardian information");

        return Result::fromSuccess($guardian);      
    }
}