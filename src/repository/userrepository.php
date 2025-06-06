<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once dirname(__FILE__) . '/../enums/UserStatus.php';
require_once  dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';
class UserRepository
{
    /**
     * Find a user by their username (email)
     * @param string $userName The username (email) to search for
     * @return array|null User data if found, null otherwise
     * @throws Exception If database error occurs
     */
    public function findUserByUsername($userName)
    {
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

    public function findAccountByUserId($id)
    {
        $conn = null;
        $stmt = null;
        try {
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();

            $stmt = $conn->prepare("SELECT * FROM useraccount WHERE id = ? LIMIT 1");

            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }

            $stmt->bind_param("s", $id);
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

    public function findAllUsers()
    {
        $conn = null;
        $stmt = null;
        try {
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();

            $status = UserStatus::Active->value;

            $stmt = $conn->prepare("
                    SELECT 
                        u.id, 
                        u.fullname, 
                        u.email, 
                        u.phone,
                        u.address,
                        u.gender,
                        r.name AS roleName,
                        u.createdAt,
                        u.dateOfBirth,
                        d.status,
                        u.roleID
                    FROM user u
                    INNER JOIN useraccount d ON u.id = d.id
                    INNER JOIN role r ON u.roleID = r.id
                    WHERE d.status = ?
                ");

            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }

            $stmt->bind_param("s", $status);
            $stmt->execute();

            $result = $stmt->get_result();

            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            return $users;
        } catch (Exception $e) {
            error_log("Database error in findAllUsers: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            if ($conn) $conn->close();
        }
    }
    public function getAllRoles()
    {
        $conn = null;
        $stmt = null;
        try {
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();

            $status = UserStatus::Active->value;

            $stmt = $conn->prepare("
                    SELECT id,name
                    FROM role
                ");

            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }

            $stmt->execute();

            $result = $stmt->get_result();

            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            return $users;
        } catch (Exception $e) {
            error_log("Database error in findAllUsers: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            if ($conn) $conn->close();
        }
    }

    public function deleteByUserID($id)
    {
        $conn = null;
        $stmt = null;
        try {
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();

            $status = UserStatus::Banned->value;

            $stmt = $conn->prepare("UPDATE useraccount d
                    JOIN user u ON u.id = d.id
                    SET d.status = ?
                    WHERE u.id = ?");

            $stmt->bind_param("si", $status, $id);

            $stmt->execute();

            $user = null;
            if ($stmt->affected_rows > 0) {
                $user = [
                    'id' => $id,
                ];
            }
            return $user;
        } catch (Exception $e) {
            error_log("Database error in findAllUsers: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            if ($conn) $conn->close();
        }
    }
    /**
     * Find a user by their email
     * @param string $email The email to search for
     * @return array|null User data if found, null otherwise
     * @throws Exception If database error occurs
     */
    public function findUserByEmail($email)
    {
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
    public function save($name, $email, $passWord, $phone, $gender, $roleID, $address = null, $birthday = null)
    {
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
            $stmt = $conn->prepare("INSERT INTO user (fullname, email, phone, gender, roleID,dateOfBirth,address) VALUES (?, ?, ?, ?, ?,?,?)");
            if (!$stmt) {
                throw new Exception("Failed to prepare user insert: " . $conn->error);
            }

            $stmt->bind_param("sssiiss", $name, $email, $phone, $gender, $roleID, $birthday, $address);
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
                'roleID' => $roleID,
                'birthday' => $birthday,
                'address' => $address

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

    public function getPasswordByUserID($userID) {
        $conn = null;
        $stmt = null;
        try {
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();
            
            if (!$conn) {
                throw new Exception("Failed to connect to database");
            }
    
            $stmt = $conn->prepare("SELECT password FROM useraccount WHERE userID = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
    
            $stmt->bind_param("i", $userID);
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute statement: " . $stmt->error);
            }
    
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return $row['password'];
            } else {
                throw new Exception("No useraccount found with userID = $userID");
            }
        } catch (Exception $e) {
            error_log("Error fetching password: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
        }
    }
    public function userUpdate($id, $name, $address, $phone, $gender, $birth, $roleID)
    {
        $conn = null;
        $stmt = null;
        try {
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();

            $fields = [];
            $params = [];
            $types = '';

            if ($name !== null) {
                $fields[] = 'fullname = ?';
                $params[] = $name;
                $types .= 's';
            }
            if ($address !== null) {
                $fields[] = 'address = ?';
                $params[] = $address;
                $types .= 's';
            }
            if ($phone !== null) {
                $fields[] = 'phone = ?';
                $params[] = $phone;
                $types .= 's';
            }
            if ($birth !== null) {
                $fields[] = 'dateOfBirth = ?';
                $params[] = $birth;
                $types .= 's';
            }
            if ($gender !== null) {
                $fields[] = 'gender = ?';
                $params[] = $gender;
                $types .= 's';
            }
            if ($roleID !== null) {
                $fields[] = 'roleID = ?';
                $params[] = $roleID;
                $types .= 'i';
            }

            if (empty($fields)) {
                throw new Exception("No fields provided for update.", 400);
            }

            $sql = "UPDATE user SET " . implode(', ', $fields) . " WHERE id = ?";
            $params[] = $id;
            $types .= 'i';

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);

            $stmt->execute();

            if ($stmt->affected_rows === 0) {
            }

            $user = ['id' => $id];
            if ($name !== null) $user['name'] = $name;
            if ($address !== null) $user['address'] = $address;
            if ($phone !== null) $user['phone'] = $phone;
            if ($gender !== null) $user['gender'] = $gender;
            if ($roleID !== null) $user['roleID'] = $roleID;

            return $user;
        } catch (Exception $e) {
            error_log("Database error in userUpdate: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            if ($conn) $conn->close();
        }
    }


    public function bulkInsertWithNPlus1($data)
    {
        $mysql = new configMysqli();
        $conn = $mysql->connectDatabase();

        if (empty($data)) return;

        $duplicatedEmails = [];

        foreach ($data as $row) {
            $fullName    = $conn->real_escape_string($row['full_name']);
            $dateOfBirth = $conn->real_escape_string($row['date_of_birth']);
            $email       = $conn->real_escape_string($row['email']);
            $phone       = $conn->real_escape_string($row['phone']);
            $address     = $conn->real_escape_string($row['address']);
            $gender      = $conn->real_escape_string($row['gender']);
            $roleID      = (int) $row['role_id'];
            $createdAt   = $conn->real_escape_string($row['created_at']);

            $checkEmailQuery = "SELECT id FROM user WHERE email = '$email' LIMIT 1";
            $result = $conn->query($checkEmailQuery);

            if ($result && $result->num_rows > 0) {
                $duplicatedEmails[] = $email;
                continue;
            }

            $sqlUser = "
                    INSERT INTO user (fullname, dateOfBirth, email, phone, address, gender, roleID , createdAt)
                    VALUES ('$fullName', '$dateOfBirth', '$email', '$phone', '$address', '$gender', $roleID, '$createdAt')
                ";

            if (!$conn->query($sqlUser)) {
                throw new Exception("Lỗi khi chèn user: " . $conn->error);
            }

            $userId = $conn->insert_id;
            $passwordDefault = '123456';
            $sqlAccount = "
                    INSERT INTO useraccount (userID, username, password)
                    VALUES ($userId, '$email', '$passwordDefault')
                ";

            if (!$conn->query($sqlAccount)) {
                throw new Exception("Lỗi khi chèn account: " . $conn->error);
            }
        }

        $conn->close();

        if (!empty($duplicatedEmails)) {
            throw new Exception("Email đã tồn tại: " . implode(', ', $duplicatedEmails));
        }
    }

    public function search($keyword, $fields)
    {
        $conn = null;
        $stmt = null;

        try {
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();

            if (empty($fields)) return [];

            $conditions = [];
            foreach ($fields as $field) {
                $conditions[] = "$field LIKE ?";
            }

            $whereClause = implode(" OR ", $conditions);
            $sql = "SELECT * FROM user WHERE $whereClause";

            $stmt = $conn->prepare($sql);

            $paramTypes = str_repeat("s", count($fields));
            $params = array_fill(0, count($fields), '%' . $keyword . '%');

            $stmt->bind_param($paramTypes, ...$params);

            $stmt->execute();
            $result = $stmt->get_result();

            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        } catch (Exception $e) {
            error_log("Database error in search: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            if ($conn) $conn->close();
        }
    }

        public function getAccessModulesByRoleId($roleID) {
            $conn = null;
            $stmt = null;
            try {
                $mysql = new configMysqli();
                $conn = $mysql->connectDatabase();
        
                $stmt = $conn->prepare("SELECT moduleid FROM access WHERE roleid = ?");
                if (!$stmt) {
                    throw new Exception("Database error: " . $conn->error);
                }
        
                $stmt->bind_param("i", $roleID);
                $stmt->execute();
        
                $result = $stmt->get_result();
                $modules = [];
                while ($row = $result->fetch_assoc()) {
                    $modules[] = $row['moduleid'];
                }
        
                return $modules;
            } catch (Exception $e) {
                error_log("Database error in getAccessModulesByRoleId: " . $e->getMessage());
                throw new Exception("Database error: " . $e->getMessage());
            } finally {
                if ($stmt) $stmt->close();
                if ($conn) $conn->close();
            }
        }
        public function updatePasswordByUserId($userID, $newPassword) {
            $conn = null;
            $stmt = null;
            try {
                $mysql = new configMysqli();
                $conn = $mysql->connectDatabase();
        
                $stmt = $conn->prepare("UPDATE useraccount SET password = ? WHERE userID = ?");
                if (!$stmt) {
                    throw new Exception("Database error: " . $conn->error);
                }
        
                $stmt->bind_param("si", $newPassword, $userID);
                $stmt->execute();
        
                if ($stmt->affected_rows === 0) {
                    throw new Exception("No changes detected, password not updated.", 500);
                }
        
                return true;
                
            } catch (Exception $e) {
                error_log("Database error in updatePasswordByUserId: " . $e->getMessage());
                throw new Exception("Database error: " . $e->getMessage());
            } finally {
                if ($stmt) $stmt->close();
            }
        }
        
    }
    
    
?>