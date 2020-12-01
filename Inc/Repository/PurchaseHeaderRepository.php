<?php

    /**
     * @package Galaxy Admin Plugin
     */

    class PurchaseHeaderRepository extends BaseRepository {

        public function getTableName() {
            return BaseRepository::PURCHASE_HEADER_TABLE_NAME;
        }

        public function getAll(): ?array {
            try {
                $rows = array();
                $result = $this->wpdb->get_results("SELECT a.* FROM {$this->getTableName()} AS a INNER JOIN `galaxy_student` AS b ON b.id = a.`studentId` WHERE b.`deleted` = 0");
                
                foreach($result as $row) {           
                    $rows[] = new PurchaseHeader([
                        "id" => $row->id,
                        "studentId" => $row->studentId,
                        "emailAddress" => $row->emailAddress,
                        "total" => $row->total,
                        "isPaid" => $row->isPaid,
                        Helper::toDateTimeFromString($row->insertDate)
                    ]);
                }

                if ($result === null)
                    return null;
    
                return $rows;
            } catch (Exception $e) {
                throw new Exception("Purchases could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function getById(int $id): ?PurchaseHeader {
            try {

                if ($id <= 0)
                    return null;

                $query = "SELECT a.* FROM {$this->getTableName()} AS a INNER JOIN `galaxy_student` AS b ON b.`id` = a.`studentId` WHERE b.`deleted` = %d AND a.`id` = %d";
                $row = $this->wpdb->get_row($this->wpdb->prepare($query, 0, $id));

                if ($row === null)
                    return null;
                    
                return new PurchaseHeader([
                        "id" => $row->id,
                        "studentId" => $row->studentId,
                        "emailAddress" => $row->emailAddress,
                        "total" => $row->total,
                        "isPaid" => $row->isPaid,
                        "insertDate" => Helper::toDateTimeFromString($row->insertDate)
                    ]);
            } catch (Exception $e) {
                throw new Exception("Purchase could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function insert(PurchaseHeader $data): ?PurchaseHeader {
            try {
                $video = $audio = null;
                
                $data->setInsertDate(Helper::toDateTimeFromString(current_time('mysql', 1)));

                $dataAsArray = $data->toArray();
                unset($dataAsArray["id"]);

                $result = $this->wpdb->insert(
                    $this->getTableName(), 
                    $dataAsArray,
                    array('%d', '%s', '%d', '%f', '%s')
                );

                if ($result === false)
                    return null;

                $data->setId($this->wpdb->insert_id);

                return $data;
            } catch (Exception $e) {
                throw new Exception("Unable to create purchase");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function update(PurchaseHeader $data): ?PurchaseHeader {
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
                        'emailAddress' => '%s',
                        'isPaid' => '%d',
                        'total' => '%f'
                    ),
                    array('%d')
                );

                if ($result === false)
                    return null;

                return $data;
            } catch (Exception $e) {
                throw new Exception("Unable to update purchase");
            } finally {
                $this->wpdb->flush();
            }
        }

        public function getByStudentId(int $studentId): ?array {
            try {
                $rows = array();
                $query = "SELECT a.* FROM {$this->getTableName()} AS a INNER JOIN `galaxy_student` AS b ON b.id = a.`studentId` WHERE b.`deleted` = %d AND b.`id` = %d";
                $result = $this->wpdb->get_results($this->wpdb->prepare($query, 0, $studentId));
                
                foreach($result as $row) {           
                    $rows[] = new PurchaseHeader([
                        "id" => $row->id,
                        "studentId" => $row->studentId,
                        "emailAddress" => $row->emailAddress,
                        "total" => $row->total,
                        "isPaid" => $row->isPaid,
                        Helper::toDateTimeFromString($row->insertDate)
                    ]);
                }

                if ($result === null)
                    return null;
    
                return $rows;
            } catch (Exception $e) {
                throw new Exception("Purchase could not be found");
            } finally {
                $this->wpdb->flush();
            }
        }

    }