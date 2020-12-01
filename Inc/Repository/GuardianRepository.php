<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class GuardianRepository extends BaseRepository {

        public function getTableName() {
            return BaseRepository::GUARDIAN_TABLE_NAME;
        }

        public function getAll(): ?array {
            try {
                $rows = array();
                $result = $this->wpdb->get_results("SELECT a.* FROM {$this->getTableName()} AS a INNER JOIN `galaxy_student` AS b ON b.`id` = a.`studentId` WHERE b.`deleted` = 0");
                
                foreach($result as $row) {           
                    $rows[] = new Guardian([
                        "id" => $row->id,
                        "studentId" => $row->studentId,
                        "lastName" => $row->lastName,
                        "firstName" => $row->firstName,
                        "country" => $row->country,
                        "state" => $row->state,
                        "educationalBackground" => $row->educationalBackground,
                        "occupation" => $row->occupation,
                        "currentPosition" => $row->currentPosition,
                        "officeAddress" => $row->officeAddress,
                        "emailAddress" => $row->emailAddress,
                        "phoneNumber" => $row->phoneNumber,
                        "insertDate" => Helper::toDateTimeFromString($row->insertDate)
                    ]);
                }

                if ($result === null)
                    return null;
    
                return $rows;
            } catch (Exception $e) {
                throw new Exception("Guardians could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function getById(int $id): ?Guardian {
            try {

                if ($id <= 0)
                    return null;

                $query = "SELECT a.* FROM {$this->getTableName()} AS a INNER JOIN `galaxy_student` AS b ON b.`id` = a.`studentId` WHERE b.`deleted` = %d AND a.`id` = %d";
                $row = $this->wpdb->get_row($this->wpdb->prepare($query, 0, $id));

                if ($row === null)
                    return null;
                    
                return new Guardian([
                        "id" => $row->id,
                        "studentId" => $row->studentId,
                        "lastName" => $row->lastName,
                        "firstName" => $row->firstName,
                        "country" => $row->country,
                        "state" => $row->state,
                        "educationalBackground" => $row->educationalBackground,
                        "occupation" => $row->occupation,
                        "currentPosition" => $row->currentPosition,
                        "officeAddress" => $row->officeAddress,
                        "emailAddress" => $row->emailAddress,
                        "phoneNumber" => $row->phoneNumber,
                        "insertDate" => Helper::toDateTimeFromString($row->insertDate)
                    ]);
            } catch (Exception $e) {
                throw new Exception("Guardian could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function insert(Guardian $data): ?Guardian {
            try {
                $video = $audio = null;
                
                $data->setInsertDate(Helper::toDateTimeFromString(current_time('mysql', 1)));

                $dataAsArray = $data->toArray();
                unset($dataAsArray["id"]);

                $result = $this->wpdb->insert(
                    $this->getTableName(), 
                    $dataAsArray,
                    array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
                );

                if ($result === false)
                    return null;

                $data->setId($this->wpdb->insert_id);

                return $data;
            } catch (Exception $e) {
                throw new Exception("Unable to create guardian");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function update(Guardian $data): ?Guardian {
            try {
                $video = $audio = null;
                
                $dataAsArray = $data->toArray();
                unset($dataAsArray["id"]);
                unset($dataAsArray["insertDate"]);

                $result = $this->wpdb->update(
                    $this->getTableName(), 
                    $dataAsArray,
                    array(
                        'id' => $data->getId()
                    ),
                    array(
                        'studentId' => '%d', 
                        'lastName' => '%s', 
                        'firstName' => '%s', 
                        'country' => '%s', 
                        'state' => '%s', 
                        'educationalBackground' => '%s', 
                        'occupation' => '%s', 
                        'currentPosition' => '%s', 
                        'officeAddress' => '%s', 
                        'emailAddress' => '%s', 
                        'phoneNumber' => '%s'
                    ),
                    array('%d')
                );

                if ($result === false)
                    return null;

                return $data;
            } catch (Exception $e) {
                throw new Exception("Unable to update guardian");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function getByStudentId(int $studentId): ?array {
            try {
                $rows = array();
                $query = "SELECT a.* FROM {$this->getTableName()} AS a INNER JOIN `galaxy_student` AS b ON b.`id` = a.`studentId` WHERE b.`deleted` = %d AND b.`id` = %d";
                $result = $this->wpdb->get_results($this->wpdb->prepare($query, 0, $studentId));
                
                foreach($result as $row) {           
                    $rows[] = new Guardian([
                        "id" => $row->id,
                        "studentId" => $row->studentId,
                        "lastName" => $row->lastName,
                        "firstName" => $row->firstName,
                        "country" => $row->country,
                        "state" => $row->state,
                        "educationalBackground" => $row->educationalBackground,
                        "occupation" => $row->occupation,
                        "currentPosition" => $row->currentPosition,
                        "officeAddress" => $row->officeAddress,
                        "emailAddress" => $row->emailAddress,
                        "phoneNumber" => $row->phoneNumber,
                        "insertDate" => Helper::toDateTimeFromString($row->insertDate)
                    ]);
                }

                if ($result === null)
                    return null;
    
                return $rows;
            } catch (Exception $e) {
                throw new Exception("Guardian could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

    }