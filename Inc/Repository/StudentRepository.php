<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class StudentRepository extends BaseRepository {

        public function getTableName() {
            return BaseRepository::STUDENT_TABLE_NAME;
        }

        public function getAll(): ?array {
            try {
                $rows = array();
                $result = $this->wpdb->get_results("SELECT * FROM {$this->getTableName()} WHERE `deleted` = 0");
                
                foreach($result as $row) {           
                    $rows[] = new Student([
                        "id" => $row->id,
                        "title" => $row->title,
                        "lastName" => $row->lastName,
                        "firstName" => $row->firstName,
                        "otherNames" => $row->otherNames,
                        "gender" => $row->gender,
                        "birthDate" => Helper::toDateTimeFromString($row->birthDate),
                        "firstLanguage" => $row->firstLanguage,
                        "country" => $row->country,
                        "state" => $row->state,
                        "phoneNumber" => $row->phoneNumber,
                        "passportNumber" => $row->passportNumber,
                        "expiryDate" => Helper::toDateTimeFromString($row->expiryDate),
                        "permanentAddress" => $row->permanentAddress,
                        "currentLevelOfStudy" => $row->currentLevelOfStudy,
                        "nextLevelOfStudy" => $row->nextLevelOfStudy,
                        "insertDate" => Helper::toDateTimeFromString($row->insertDate)
                    ]);
                }

                if ($result === null)
                    return null;
    
                return $rows;
            } catch (Exception $e) {
                throw new Exception("Students could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function getById(int $id): ?Student {
            try {

                if ($id <= 0)
                    return null;

                $query = "SELECT * FROM {$this->getTableName()} AS a WHERE `deleted` = 0 AND a.`id` = %d";
                $row = $this->wpdb->get_row($this->wpdb->prepare($query, $id));

                if ($row === null)
                    return null;
                    
                return new Student([
                        "id" => $row->id,
                        "title" => $row->title,
                        "lastName" => $row->lastName,
                        "firstName" => $row->firstName,
                        "otherNames" => $row->otherNames,
                        "gender" => $row->gender,
                        "birthDate" => Helper::toDateTimeFromString($row->birthDate),
                        "firstLanguage" => $row->firstLanguage,
                        "country" => $row->country,
                        "state" => $row->state,
                        "phoneNumber" => $row->phoneNumber,
                        "passportNumber" => $row->passportNumber,
                        "expiryDate" => Helper::toDateTimeFromString($row->expiryDate),
                        "permanentAddress" => $row->permanentAddress,
                        "currentLevelOfStudy" => $row->currentLevelOfStudy,
                        "nextLevelOfStudy" => $row->nextLevelOfStudy,
                        "insertDate" => Helper::toDateTimeFromString($row->insertDate)
                    ]);
            } catch (Exception $e) {
                throw new Exception("Student could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function insert(Student $data): ?Student {
            try {
                $video = $audio = null;
                
                $data->setInsertDate(Helper::toDateTimeFromString(current_time('mysql', 1)));

                $dataAsArray = $data->toArray();
                unset($dataAsArray["id"]);
                unset($dataAsArray["deleted"]);

                $result = $this->wpdb->insert(
                    $this->getTableName(), 
                    $dataAsArray,
                    array('%s', '%s', '%s', '%s', '%d', 
                    '%s', 
                    '%s', '%s', '%s', '%s', '%s', 
                    '%s', 
                    '%s', '%s', '%s', 
                    '%s')
                );

                if ($result === false)
                    return null;

                $data->setId($this->wpdb->insert_id);

                return $data;
            } catch (Exception $e) {
                throw new Exception("Student could not be created");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function update(Student $data): ?Student {
            try {
                $video = $audio = null;
                
                $dataAsArray = $data->toArray();
                unset($dataAsArray["id"]);
                unset($dataAsArray["insertDate"]);
                unset($dataAsArray["deleted"]);

                $result = $this->wpdb->update(
                    $this->getTableName(), 
                    $dataAsArray,
                    array(
                        'id' => $data->getId()
                    ),
                    array(
                        'title' => '%s', 
                        'lastName' => '%s', 
                        'firstName' => '%s', 
                        'otherNames' => '%s', 
                        'gender' => '%d', 
                        'birthDate' => '%s', 
                        'firstLanguage' => '%s', 
                        'country' => '%s', 
                        'state' => '%s', 
                        'phoneNumber' => '%s', 
                        'passportNumber' => '%s', 
                        'expiryDate' => '%s', 
                        'permanentAddress' => '%s', 
                        'currentLevelOfStudy' => '%s', 
                        'nextLevelOfStudy' => '%s'
                    ),
                    array('%d')
                );

                if ($result === false)
                    return null;

                return $data;
            } catch (Exception $e) {
                throw new Exception("Student could not be updated");
            } finally {
                $this->wpdb->flush();
            }
        }

    }