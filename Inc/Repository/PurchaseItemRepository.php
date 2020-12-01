<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class PurchaseItemRepository extends BaseRepository {

        public function getTableName() {
            return BaseRepository::PURCHASE_ITEM_TABLE_NAME;
        }

        public function getAll(): ?array {
            try {
                $rows = array();
                $result = $this->wpdb->get_results("SELECT a.* FROM {$this->getTableName()} AS a 
                                                    INNER JOIN `galaxy_purchase_header` AS b ON b.`id` = a.`purchaseId` 
                                                    INNER JOIN `galaxy_student` AS c ON c.`id` = b.`studentId` 
                                                    WHERE c.`deleted` = 0");
                
                foreach($result as $row) {           
                    $rows[] = new PurchaseItem([
                        "id" => $row->id,
                        "purchaseId" => $row->purchaseId,
                        "examId" => $row->examId,
                        "examRegistration" => $row->examRegistration,
                        "bookPurchase" => $row->bookPurchase,
                        "lessonEnrolment" => $row->lessonEnrolment,
                        "preferredExamDate" => Helper::toDateTimeFromString($row->preferredExamDate),
                        "alternativeExamDate" => Helper::toDateTimeFromString($row->alternativeExamDate),
                        "preferredExamLocation" => $row->preferredExamLocation,
                        "alternativeExamLocation" => $row->alternativeExamLocation,
                        "examRegistrationPrice" => $row->examRegistrationPrice,
                        "bookPurchasePrice" => $row->bookPurchasePrice,
                        "lessonEnrolmentPrice" => $row->lessonEnrolmentPrice,
                        "itemTotal" => $row->itemTotal
                    ]);
                }

                if ($result === null)
                    return null;
    
                return $rows;
            } catch (Exception $e) {
                throw new Exception("Purchase items could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function getById(int $id): ?PurchaseItem {
            try {

                if ($id <= 0)
                    return null;

                $query = "SELECT a.* FROM {$this->getTableName()} AS a
                          INNER JOIN `galaxy_purchase_header` AS b ON b.`id` = a.`purchaseId` 
                          INNER JOIN `galaxy_student` AS c ON c.`id` = b.`studentId` 
                          WHERE c.`deleted` = 0 AND a.`id` = %d";
                $row = $this->wpdb->get_row($this->wpdb->prepare($query, $id));

                if ($row === null)
                    return null;
                    
                return new PurchaseItem([
                        "id" => $row->id,
                        "purchaseId" => $row->purchaseId,
                        "examId" => $row->examId,
                        "examRegistration" => $row->examRegistration,
                        "bookPurchase" => $row->bookPurchase,
                        "lessonEnrolment" => $row->lessonEnrolment,
                        "preferredExamDate" => Helper::toDateTimeFromString($row->preferredExamDate),
                        "alternativeExamDate" => Helper::toDateTimeFromString($row->alternativeExamDate),
                        "preferredExamLocation" => $row->preferredExamLocation,
                        "alternativeExamLocation" => $row->alternativeExamLocation,
                        "examRegistrationPrice" => $row->examRegistrationPrice,
                        "bookPurchasePrice" => $row->bookPurchasePrice,
                        "lessonEnrolmentPrice" => $row->lessonEnrolmentPrice,
                        "itemTotal" => $row->itemTotal
                    ]);
            } catch (Exception $e) {
                throw new Exception("Purchase item could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function insert(PurchaseItem $data): ?PurchaseItem {
            try {
                $video = $audio = null;
                
                // $data->setInsertDate(Helper::toDateTimeFromString(current_time('mysql', 1)));

                $dataAsArray = $data->toArray();
                unset($dataAsArray["id"]);

                $result = $this->wpdb->insert(
                    $this->getTableName(), 
                    $dataAsArray,
                    array('%d', '%d', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%f')
                );

                if ($result === false)
                    return null;

                $data->setId($this->wpdb->insert_id);

                return $data;
            } catch (Exception $e) {
                throw new Exception("Unable to create purchase item");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function update(PurchaseItem $data): ?PurchaseItem {
            try {
                $video = $audio = null;
                
                $dataAsArray = $data->toArray();
                unset($dataAsArray["id"]);

                $result = $this->wpdb->update(
                    $this->getTableName(), 
                    $dataAsArray,
                    array(
                        'id' => $data->getId()
                    ),
                    array(
                        'purchaseId' => '%d', 
                        'examId' => '%d', 
                        'examRegistration' => '%d', 
                        'bookPurchase' => '%d', 
                        'lessonEnrolment' => '%d', 
                        'preferredExamDate' => '%s', 
                        'alternativeExamDate' => '%s', 
                        'preferredExamLocation' => '%s', 
                        'alternativeExamLocation' => '%s', 
                        'examRegistrationPrice' => '%f', 
                        'bookPurchasePrice' => '%f', 
                        'lessonEnrolmentPrice' => '%f', 
                        'itemTotal' => '%f'
                    ),
                    array('%d')
                );

                if ($result === false)
                    return null;

                return $data;
            } catch (Exception $e) {
                throw new Exception("Unable to update purchase item");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function getByStudentId(int $studentId): ?array {
            try {
                $rows = array();
                $query = "SELECT a.* FROM {$this->getTableName()} AS a 
                          INNER JOIN `galaxy_purchase_header` AS b ON b.`id` = a.`purchaseId` 
                          INNER JOIN `galaxy_student` AS c ON c.`id` = b.`studentId` 
                          WHERE c.`deleted` = %d AND c.`id` = %d";
                $result = $this->wpdb->get_results($this->wpdb->prepare($query, 0, $studentId));
                
                foreach($result as $row) {           
                    $rows[] = new PurchaseItem([
                        "id" => $row->id,
                        "purchaseId" => $row->purchaseId,
                        "examId" => $row->examId,
                        "examRegistration" => $row->examRegistration,
                        "bookPurchase" => $row->bookPurchase,
                        "lessonEnrolment" => $row->lessonEnrolment,
                        "preferredExamDate" => Helper::toDateTimeFromString($row->preferredExamDate),
                        "alternativeExamDate" => Helper::toDateTimeFromString($row->alternativeExamDate),
                        "preferredExamLocation" => $row->preferredExamLocation,
                        "alternativeExamLocation" => $row->alternativeExamLocation,
                        "examRegistrationPrice" => $row->examRegistrationPrice,
                        "bookPurchasePrice" => $row->bookPurchasePrice,
                        "lessonEnrolmentPrice" => $row->lessonEnrolmentPrice,
                        "itemTotal" => $row->itemTotal
                    ]);
                }

                if ($result === null)
                    return null;
    
                return $rows;
            } catch (Exception $e) {
                throw new Exception("Purchase items could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function getByPurchaseId(int $purchaseHeaderId): ?array {
            try {
                $rows = array();
                $query = "SELECT a.* FROM {$this->getTableName()} AS a 
                          INNER JOIN `galaxy_purchase_header` AS b ON b.`id` = a.`purchaseId` 
                          INNER JOIN `galaxy_student` AS c ON c.`id` = b.`studentId` 
                          WHERE c.`deleted` = %d AND a.`purchaseId` = %d";
                $result = $this->wpdb->get_results($this->wpdb->prepare($query, 0, $purchaseHeaderId));
                
                foreach($result as $row) {           
                    $rows[] = new PurchaseItem([
                        "id" => $row->id,
                        "purchaseId" => $row->purchaseId,
                        "examId" => $row->examId,
                        "examRegistration" => $row->examRegistration,
                        "bookPurchase" => $row->bookPurchase,
                        "lessonEnrolment" => $row->lessonEnrolment,
                        "preferredExamDate" => Helper::toDateTimeFromString($row->preferredExamDate),
                        "alternativeExamDate" => Helper::toDateTimeFromString($row->alternativeExamDate),
                        "preferredExamLocation" => $row->preferredExamLocation,
                        "alternativeExamLocation" => $row->alternativeExamLocation,
                        "examRegistrationPrice" => $row->examRegistrationPrice,
                        "bookPurchasePrice" => $row->bookPurchasePrice,
                        "lessonEnrolmentPrice" => $row->lessonEnrolmentPrice,
                        "itemTotal" => $row->itemTotal
                    ]);
                }

                if ($result === null)
                    return null;
    
                return $rows;
            } catch (Exception $e) {
                throw new Exception("Purchase items could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

    }