<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class ExamRepository extends BaseRepository {

        public function getTableName() {
            return BaseRepository::EXAM_TABLE_NAME;
        }

        public function getAll(): ?array {
            try {
                $rows = array();
                $result = $this->wpdb->get_results("SELECT * FROM {$this->getTableName()}");
                
                foreach($result as $row) {           
                    $rows[] = new Exam([
                        'id' => $row->id,
                        'examName' => $row->examName, 
                        'examRegistrationPrice' => $row->examRegistrationPrice, 
                        'bookPurchasePrice' => $row->bookPurchasePrice, 
                        'lessonEnrolmentPrice' => $row->lessonEnrolmentPrice
                    ]);
                }

                if ($result === null)
                    return null;
    
                return $rows;
            } catch (Exception $e) {
                return null;
            } finally {
                $this->wpdb->flush();
            }
        }

        public function getById(int $id): ?Exam {
            try {

                if ($id <= 0)
                    return null;

                $query = "SELECT * FROM {$this->getTableName()} WHERE `id` = %d";
                $row = $this->wpdb->get_row($this->wpdb->prepare($query, $id));

                if ($row === null)
                    return null;
                    
                return new Exam([
                        'id' => $row->id,
                        'examName' => $row->examName, 
                        'examRegistrationPrice' => $row->examRegistrationPrice, 
                        'bookPurchasePrice' => $row->bookPurchasePrice, 
                        'lessonEnrolmentPrice' => $row->lessonEnrolmentPrice
                    ]);
            } catch (Exception $e) {
                return null;
            } finally {
                $this->wpdb->flush();
            }
        }

    }