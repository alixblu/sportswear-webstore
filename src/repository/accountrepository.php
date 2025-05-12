<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

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
            // Không đóng kết nối để tái sử dụng
        }
    }
    public function getUserAccountIdByUserId($userID) {
        $query = "SELECT ID FROM useraccount WHERE userID = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }
    
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            return $row['ID'];
        } else {
            return null; // Không tìm thấy userID tương ứng
        }
    }
    
    public function findAll() {
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

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
            // Không đóng kết nối để tái sử dụng
        }
    }

    public function findById($accountId) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

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

            if (!$account) {
                error_log("Không tìm thấy tài khoản với ID: $accountId");
                throw new Exception("Không tìm thấy tài khoản với ID: $accountId");
            }

            error_log("Account found: " . json_encode($account));
            return $account;
        } catch (Exception $e) {
            error_log("Lỗi tìm tài khoản theo ID: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            // Không đóng kết nối để tái sử dụng
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
            // Không đóng kết nối để tái sử dụng
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
            // Không đóng kết nối để tái sử dụng
        }
    }

    public function filterAccounts($filters) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

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
                      WHERE 1=1";
            
            $params = [];
            $types = '';

            if ($filters['type'] === 'staff') {
                $query .= " AND u.roleID != 5";
            } elseif ($filters['type'] === 'customer') {
                $query .= " AND u.roleID = 5";
            }

            if (!empty($filters['status']) && $filters['status'] !== 'all') {
                $query .= " AND ua.status = ?";
                $params[] = $filters['status'];
                $types .= 's';
            }

            if (!empty($filters['roleID']) && $filters['roleID'] !== 'all') {
                $query .= " AND u.roleID = ?";
                $params[] = $filters['roleID'];
                $types .= 'i';
            }

            $query .= " ORDER BY ua.ID DESC";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn: " . $this->conn->error);
            }

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $accounts = [];
            while ($row = $result->fetch_assoc()) {
                $accounts[] = $row;
            }
            return $accounts;
        } catch (Exception $e) {
            error_log("Lỗi lọc tài khoản: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            // Không đóng kết nối để tái sử dụng
        }
    }

    public function getPermissions($roleId) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $query = "SELECT moduleid FROM access WHERE roleid = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn permissions: " . $this->conn->error);
            }
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            $permissions = [];
            while ($row = $result->fetch_assoc()) {
                $permissions[] = (int)$row['moduleid'];
            }
            return $permissions;
        } catch (Exception $e) {
            error_log("Lỗi lấy quyền hạn: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            // Không đóng kết nối để tái sử dụng
        }
    }

    public function getAllModules() {
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $query = "SELECT id, name, icon, type, page FROM modules ORDER BY id";
            $result = $this->conn->query($query);
            if (!$result) {
                throw new Exception("Truy vấn modules thất bại: " . $this->conn->error);
            }

            $modules = [];
            while ($row = $result->fetch_assoc()) {
                $modules[] = $row;
            }
            $result->close();
            return $modules;
        } catch (Exception $e) {
            error_log("Lỗi lấy danh sách modules: " . $e->getMessage());
            throw $e;
        } finally {
            // Không đóng kết nối để tái sử dụng
        }
    }

    public function getAllRoles() {
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $query = "SELECT ID, name FROM role ORDER BY ID";
            $result = $this->conn->query($query);
            if (!$result) {
                throw new Exception("Truy vấn roles thất bại: " . $this->conn->error);
            }

            $roles = [];
            while ($row = $result->fetch_assoc()) {
                $roles[] = $row;
            }
            $result->close();
            return $roles;
        } catch (Exception $e) {
            error_log("Lỗi lấy danh sách roles: " . $e->getMessage());
            throw $e;
        } finally {
            // Không đóng kết nối để tái sử dụng
        }
    }

    public function updatePermissions($roleId, $moduleIds) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại");
            }

            $this->conn->begin_transaction();

            // Xóa tất cả quyền hiện tại của role
            $deleteQuery = "DELETE FROM access WHERE roleid = ?";
            $stmt = $this->conn->prepare($deleteQuery);
            if (!$stmt) {
                throw new Exception("Không thể chuẩn bị truy vấn xóa access: " . $this->conn->error);
            }
            $stmt->bind_param('i', $roleId);
            if (!$stmt->execute()) {
                throw new Exception("Xóa quyền hiện tại thất bại: " . $stmt->error);
            }
            $stmt->close();

            // Thêm các quyền mới
            if (!empty($moduleIds)) {
                $insertQuery = "INSERT INTO access (roleid, moduleid) VALUES (?, ?)";
                $stmt = $this->conn->prepare($insertQuery);
                if (!$stmt) {
                    throw new Exception("Không thể chuẩn bị truy vấn thêm access: " . $this->conn->error);
                }
                foreach ($moduleIds as $moduleId) {
                    $stmt->bind_param('ii', $roleId, $moduleId);
                    if (!$stmt->execute()) {
                        throw new Exception("Thêm quyền mới thất bại: " . $stmt->error);
                    }
                }
                $stmt->close();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Lỗi cập nhật quyền hạn: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            // Không đóng kết nối để tái sử dụng
        }
    }
}
?>