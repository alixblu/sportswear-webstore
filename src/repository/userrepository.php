<?php
    require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';

    class UserRepository{
        /**
         * Find a user by their username (email)
         * @param string $userName The username (email) to search for
         * @return array|null User data if found, null otherwise
         * @throws Exception If database error occurs
         */
        public function findUserByUsername($userName) {
            $conn = null;
            $stmt = null;
            try {
                $mysql = new configMysqli();
                $conn = $mysql->connectDatabase();
            
                $stmt = $conn->prepare("SELECT * FROM useraccount ua 
                                        INNER JOIN user u ON ua.userID = u.ID 
                                        WHERE ua.username = ?");
                if (!$stmt) {
                    throw new Exception("Database error: " . $conn->error);
                }
                
                $stmt->bind_param("s", $userName);
                $stmt->execute();
            
                $result = $stmt->get_result();
                return $result->fetch_assoc();
            } catch (Exception $e) {
                error_log("Database error in findUserByUsername: " . $e->getMessage());
                throw new Exception("Database error: " . $e->getMessage());
            } finally {
                if ($stmt) $stmt->close();
                if ($conn) $conn->close();
            }
        }
        public function findUserById($id) {
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();

            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->bind_param("s", $id);
            $stmt->execute();

            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            $stmt->close(); 
            $conn->close();

            return $user;

        }
        /**
         * Find a user by their email
         * @param string $email The email to search for
         * @return array|null User data if found, null otherwise
         * @throws Exception If database error occurs
         */
        public function findUserByEmail($email) {
            $conn = null;
            $stmt = null;
            try {
                $mysql = new configMysqli();
                $conn = $mysql->connectDatabase();
            
                $stmt = $conn->prepare("SELECT * FROM useraccount ua 
                                        INNER JOIN user u ON ua.userID = u.ID 
                                        WHERE u.email = ?");
                if (!$stmt) {
                    throw new Exception("Database error: " . $conn->error);
                }
                
                $stmt->bind_param("s", $email);
                $stmt->execute();
            
                $result = $stmt->get_result();
                return $result->fetch_assoc();
            } catch (Exception $e) {
                error_log("Database error in findUserByEmail: " . $e->getMessage());
                throw new Exception("Database error: " . $e->getMessage());
            } finally {
                if ($stmt) $stmt->close();
                if ($conn) $conn->close();
            }
        }
        /**
         * Save a new user to the database
         * @param string $name Full name of the user
         * @param string $email Email address
         * @param string $passWord Password
         * @param string $phone Phone number
         * @param int $gender Gender (0 for male, 1 for female)
         * @param int $roleID Role ID
         * @return array User data
         * @throws Exception If database error occurs
         */
        public function save($name, $email, $passWord, $phone, $gender, $roleID) {
            $conn = null;
            $stmt = null;
            try {
                $mysql = new configMysqli();
                $conn = $mysql->connectDatabase();
                
                if (!$conn) {
                    throw new Exception("Failed to connect to database");
                }
                
                // Start transaction
                if (!$conn->begin_transaction()) {
                    throw new Exception("Failed to start transaction");
                }
                
                // Insert into user table first
                $stmt = $conn->prepare("INSERT INTO user (fullname, email, phone, gender, roleID) VALUES (?, ?, ?, ?, ?)");
                if (!$stmt) {
                    throw new Exception("Failed to prepare user insert: " . $conn->error);
                }
                
                $stmt->bind_param("sssii", $name, $email, $phone, $gender, $roleID);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert user: " . $stmt->error);
                }

                $userId = $conn->insert_id;

                // Then insert into useraccount table with email as username
                $stmt = $conn->prepare("INSERT INTO useraccount (username, password, userID, status) VALUES (?, ?, ?, 'active')");
                if (!$stmt) {
                    throw new Exception("Failed to prepare useraccount insert: " . $conn->error);
                }
                
                $stmt->bind_param("ssi", $email, $passWord, $userId);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert useraccount: " . $stmt->error);
                }

                // Commit transaction
                if (!$conn->commit()) {
                    throw new Exception("Failed to commit transaction");
                }

                return [
                    'userID' => $userId,
                    'username' => $email,
                    'email' => $email,
                    'phone' => $phone,
                    'gender' => $gender,
                    'roleID' => $roleID
                ];
            } catch (Exception $e) {
                // Rollback transaction on error
                if ($conn) {
                    $conn->rollback();
                }
                error_log("Registration error: " . $e->getMessage());
                throw $e;
            } finally {
                if ($stmt) $stmt->close();
                if ($conn) $conn->close();
            }
        }
        public function userUpdate($id,$address){
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();
        
            $sql = "UPDATE users 
                    SET address = ?
                    WHERE id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $address,$id);
        
            $stmt->execute();
            $user = null;
            if ($stmt->affected_rows > 0) {
                $user = [
                    'id' => $id,
                    'address' => $address,
                ];
            }

            $stmt->close(); 
            $conn->close();

            return $user;
           
        }
        public function userDelete($id){
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();
        
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
        
            $stmt->execute();
            $user = null;
            if ($stmt->affected_rows > 0) {
                $user = [
                    'id' => $id,
                ];
            }

            $stmt->close(); 
            $conn->close();

            return $user;
        }
        public function userFindAll(){
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();

            $sql = "SELECT id, username ,password ,address ,gender ,status FROM users";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $result = $stmt->get_result();
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $userId = $row['id'];
                
                if (!isset($users[$userId])) {
                    $users[$userId] = [
                        'id' => $row['id'],
                        'username' => $row['username'],
                        'password' => $row['password'],
                        'address' => $row['address'],
                        'gender' => $row['gender'],
                        'status' => $row['status'],
                    ];
                }
            }

            $stmt->close();
            $conn->close();

            return array_values($users); 
        }
        public function changePassword($userId,$newPassWord){
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();
        
            $sql = "UPDATE users 
                    SET password = ?
                    WHERE id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $newPassWord,$userId);
        
            $stmt->execute();
            $user = null;
            if ($stmt->affected_rows > 0) {
                $user = [
                    'id' => $userId,
                    'newPassWord' => $newPassWord,
                ];
            }

            $stmt->close(); 
            $conn->close();

            return $user;
        }
    }

?>