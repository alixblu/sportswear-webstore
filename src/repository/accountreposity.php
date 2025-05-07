<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once  dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class AccountRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
        if ($this->conn) {
            $this->conn->set_charset('utf8mb4');
        }
    }

    public function save($username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $this->conn->begin_transaction();

            // Thêm vào bảng user
            $userQuery = "INSERT INTO user (fullname, phone, roleID, email, address, gender, dateOfBirth) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($userQuery);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn user: " . $this->conn->error);
            }
            $stmt->bind_param('ssissis', $fullname, $phone, $roleId, $email, $address, $gender, $dateOfBirth);
            if (!$stmt->execute()) {
                throw new Exception("Thêm user thất bại: " . $stmt->error);
            }
            $userId = $this->conn->insert_id;
            $stmt->close();

            // Thêm vào bảng useraccount
            $accountQuery = "INSERT INTO useraccount (username, password, userID, status) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($accountQuery);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn useraccount: " . $this->conn->error);
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param('ssis', $username, $hashedPassword, $userId, $status);
            if (!$stmt->execute()) {
                throw new Exception("Thêm useraccount thất bại: " . $stmt->error);
            }
            $accountId = $this->conn->insert_id;
            $stmt->close();

            $this->conn->commit();

            return [
                'accountID' => $accountId,
                'userID' => $userId,
                'username' => $username,
                'fullname' => $fullname,
                'phone' => $phone,
                'roleID' => $roleId,
                'status' => $status,
                'email' => $email,
                'address' => $address,
                'gender' => $gender,
                'dateOfBirth' => $dateOfBirth
            ];
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Lỗi lưu tài khoản: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            if ($this->conn) $this->conn->close();
        }
    }

    public function findAll() {
        try {
            $query = "SELECT 
                        ua.ID AS accountID,
                        ua.userID,
                        ua.username,
                        ua.status,
                        ua.createdAt AS accountCreatedAt,
                        u.fullname,
                        u.phone,
                        u.email,
                        u.address,
                        u.gender,
                        u.dateOfBirth,
                        u.roleID,
                        r.name AS roleName
                      FROM useraccount ua
                      JOIN user u ON ua.userID = u.ID
                      JOIN role r ON u.roleID = r.ID
                      ORDER BY ua.ID DESC";
            $result = $this->conn->query($query);
            if (!$result) {
                throw new Exception("Truy vấn thất bại: " . $this->conn->error);
            }

            $accounts = [];
            while ($row = $result->fetch_assoc()) {
                $accounts[] = $row;
            }
            $result->close();
            return $accounts;
        } catch (Exception $e) {
            error_log("Lỗi lấy danh sách tài khoản: " . $e->getMessage());
            throw $e;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    public function findById($accountId) {
        try {
            $query = "SELECT 
                        ua.ID AS accountID,
                        ua.userID,
                        ua.username,
                        ua.status,
                        ua.createdAt AS accountCreatedAt,
                        u.fullname,
                        u.phone,
                        u.email,
                        u.address,
                        u.gender,
                        u.dateOfBirth,
                        u.roleID,
                        r.name AS roleName
                      FROM useraccount ua
                      JOIN user u ON ua.userID = u.ID
                      JOIN role r ON u.roleID = r.ID
                      WHERE ua.ID = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn: " . $this->conn->error);
            }
            $stmt->bind_param('i', $accountId);
            $stmt->execute();
            $result = $stmt->get_result();
            $account = $result->fetch_assoc();
            $stmt->close();
            return $account;
        } catch (Exception $e) {
            error_log("Lỗi tìm tài khoản theo ID: " . $e->getMessage());
            throw $e;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    public function update($accountId, $username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $this->conn->begin_transaction();

            // Cập nhật bảng user
            $userQuery = "UPDATE user SET fullname = ?, phone = ?, roleID = ?, email = ?, address = ?, gender = ?, dateOfBirth = ? 
                          WHERE ID = (SELECT userID FROM useraccount WHERE ID = ?)";
            $stmt = $this->conn->prepare($userQuery);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn user: " . $this->conn->error);
            }
            $stmt->bind_param('ssissisi', $fullname, $phone, $roleId, $email, $address, $gender, $dateOfBirth, $accountId);
            if (!$stmt->execute()) {
                throw new Exception("Cập nhật user thất bại: " . $stmt->error);
            }
            $stmt->close();

            // Cập nhật bảng useraccount
            $accountQuery = "UPDATE useraccount SET username = ?, status = ?" . ($password ? ", password = ?" : "") . " WHERE ID = ?";
            $stmt = $this->conn->prepare($accountQuery);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn useraccount: " . $this->conn->error);
            }
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bind_param('sssi', $username, $status, $hashedPassword, $accountId);
            } else {
                $stmt->bind_param('ssi', $username, $status, $accountId);
            }
            if (!$stmt->execute()) {
                throw new Exception("Cập nhật useraccount thất bại: " . $stmt->error);
            }
            $stmt->close();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Lỗi cập nhật tài khoản: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            if ($this->conn) $this->conn->close();
        }
    }

    public function delete($accountId) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $this->conn->begin_transaction();

            // Lấy userID
            $userQuery = "SELECT userID FROM useraccount WHERE ID = ?";
            $stmt = $this->conn->prepare($userQuery);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn userID: " . $this->conn->error);
            }
            $stmt->bind_param('i', $accountId);
            $stmt->execute();
            $result = $stmt->get_result();
            $userId = $result->fetch_assoc()['userID'];
            $stmt->close();

            // Xóa useraccount
            $accountQuery = "DELETE FROM useraccount WHERE ID = ?";
            $stmt = $this->conn->prepare($accountQuery);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị xóa useraccount: " . $this->conn->error);
            }
            $stmt->bind_param('i', $accountId);
            if (!$stmt->execute()) {
                throw new Exception("Xóa useraccount thất bại: " . $stmt->error);
            }
            $stmt->close();

            // Xóa user
            $userQuery = "DELETE FROM user WHERE ID = ?";
            $stmt = $this->conn->prepare($userQuery);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị xóa user: " . $this->conn->error);
            }
            $stmt->bind_param('i', $userId);
            if (!$stmt->execute()) {
                throw new Exception("Xóa user thất bại: " . $stmt->error);
            }
            $stmt->close();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Lỗi xóa tài khoản: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            if ($this->conn) $this->conn->close();
        }
    }

    public function filterAccounts($filters) {
        try {
            $query = "SELECT 
                        ua.ID AS accountID,
                        ua.userID,
                        ua.username,
                        ua.status,
                        ua.createdAt AS accountCreatedAt,
                        u.fullname,
                        u.phone,
                        u.email,
                        u.address,
                        u.gender,
                        u.dateOfBirth,
                        u.roleID,
                        r.name AS roleName
                      FROM useraccount ua
                      JOIN user u ON ua.userID = u.ID
                      JOIN role r ON u.roleID = r.ID";
            $params = [];
            $types = '';
            $conditions = [];

            if (!empty($filters['status'])) {
                $conditions[] = "ua.status = ?";
                $params[] = $filters['status'];
                $types .= 's';
            }
            if (!empty($filters['roleID'])) {
                $conditions[] = "u.roleID = ?";
                $params[] = $filters['roleID'];
                $types .= 'i';
            }
            if ($filters['type'] === 'staff') {
                $conditions[] = "u.roleID != 5";
            } elseif ($filters['type'] === 'customer') {
                $conditions[] = "u.roleID = 5";
            }

            if ($conditions) {
                $query .= ' WHERE ' . implode(' AND ', $conditions);
            }

            $query .= " ORDER BY ua.ID DESC";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn: " . $this->conn->error);
            }
            if ($params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $accounts = [];
            while ($row = $result->fetch_assoc()) {
                $accounts[] = $row;
            }
            $stmt->close();
            return $accounts;
        } catch (Exception $e) {
            error_log("Lỗi lọc tài khoản: " . $e->getMessage());
            throw $e;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    public function getPermissions($roleId) {
        try {
            $query = "SELECT moduleID FROM access WHERE roleid = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn: " . $this->conn->error);
            }
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            $permissions = [];
            while ($row = $result->fetch_assoc()) {
                $permissions[] = $row['moduleID'];
            }
            $stmt->close();
            return $permissions;
        } catch (Exception $e) {
            error_log("Lỗi lấy quyền hạn: " . $e->getMessage());
            throw $e;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    public function getAllModules() {
        try {
            $query = "SELECT ID, name, icon FROM module ORDER BY ID";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn: " . $this->conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $modules = [];
            while ($row = $result->fetch_assoc()) {
                $modules[] = $row;
            }
            $stmt->close();
            return $modules;
        } catch (Exception $e) {
            error_log("Lỗi lấy danh sách module: " . $e->getMessage());
            throw $e;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    public function getAllRoles() {
        try {
            $query = "SELECT ID, name, statusRole FROM role";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn: " . $this->conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $roles = [];
            while ($row = $result->fetch_assoc()) {
                $roles[] = $row;
            }
            $stmt->close();
            return $roles;
        } catch (Exception $e) {
            error_log("Lỗi lấy danh sách vai trò: " . $e->getMessage());
            throw $e;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }
}
?>