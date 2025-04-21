<?php
// File: src/repository/AccountRepository.php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once __DIR__ . '/../enums/AccountStatus.php';

class AccountRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
        $this->conn->set_charset('utf8mb4');
    }

    public function getAccountDetails($accountId) {
        $query = "SELECT 
                    ua.ID as accountID,
                    ua.userID,
                    ua.username,
                    LOWER(ua.status) as status,
                    u.fullname,
                    u.phone,
                    u.email,
                    u.address,
                    u.gender,
                    u.dateOfBirth,
                    u.roleID,
                    r.name as roleName,
                    ua.createdAt
                  FROM useraccount ua
                  JOIN user u ON ua.userID = u.ID
                  JOIN role r ON u.roleID = r.ID
                  WHERE ua.ID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $accountId);
        $stmt->execute();
        $result = $stmt->get_result();
        $account = $result->fetch_assoc();
        $stmt->close();
        return $account;
    }

    public function getPermissions($roleId) {
        $query = "SELECT moduleID FROM role_module WHERE roleID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $roleId);
        $stmt->execute();
        $result = $stmt->get_result();
        $permissions = [];
        while ($row = $result->fetch_assoc()) {
            $permissions[] = $row['moduleID'];
        }
        $stmt->close();
        return $permissions;
    }

    public function getAllModules() {
        $query = "SELECT ID, name, icon FROM module ORDER BY ID";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $modules = [];
        while ($row = $result->fetch_assoc()) {
            $modules[] = $row;
        }
        $stmt->close();
        return $modules;
    }

    public function getAllRoles() {
        $query = "SELECT ID, name FROM role";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }
        $stmt->close();
        return $roles;
    }

    public function updateAccount($data) {
        $this->conn->begin_transaction();
        try {
            // Cập nhật bảng user
            $userQuery = "UPDATE user SET 
                          fullname = ?, 
                          phone = ?,
                          email = ?,
                          address = ?,
                          gender = ?,
                          dateOfBirth = ?
                          WHERE ID = (SELECT userID FROM useraccount WHERE ID = ?)";
            $userStmt = $this->conn->prepare($userQuery);
            $userStmt->bind_param(
                'ssssssi',
                $data['fullname'],
                $data['phone'],
                $data['email'],
                $data['address'],
                $data['gender'],
                $data['dateOfBirth'],
                $data['accountId']
            );
            $userStmt->execute();
            $userStmt->close();

            // Cập nhật bảng useraccount
            $accountQuery = "UPDATE useraccount SET 
                            username = ?, 
                            status = ?" . 
                            (!empty($data['password']) ? ", password = ?" : "") . 
                            " WHERE ID = ?";
            $accountStmt = $this->conn->prepare($accountQuery);

            if (!empty($data['password'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                $accountStmt->bind_param(
                    'sssi',
                    $data['username'],
                    $data['status'],
                    $hashedPassword,
                    $data['accountId']
                );
            } else {
                $accountStmt->bind_param(
                    'ssi',
                    $data['username'],
                    $data['status'],
                    $data['accountId']
                );
            }
            $accountStmt->execute();
            $accountStmt->close();

            // Cập nhật vai trò nếu có
            if (isset($data['roleId'])) {
                $roleQuery = "UPDATE user SET roleID = ? 
                             WHERE ID = (SELECT userID FROM useraccount WHERE ID = ?)";
                $roleStmt = $this->conn->prepare($roleQuery);
                $roleStmt->bind_param('si', $data['roleId'], $data['accountId']);
                $roleStmt->execute();
                $roleStmt->close();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function updatePermissions($roleId, $permissions) {
        $this->conn->begin_transaction();
        try {
            // Xóa quyền hạn hiện tại
            $deleteStmt = $this->conn->prepare("DELETE FROM role_module WHERE roleID = ?");
            $deleteStmt->bind_param('s', $roleId);
            $deleteStmt->execute();
            $deleteStmt->close();

            // Thêm quyền hạn mới
            if (!empty($permissions)) {
                $insertQuery = "INSERT INTO role_module (roleID, moduleID) VALUES " . 
                    implode(',', array_fill(0, count($permissions), '(?, ?)'));
                $insertStmt = $this->conn->prepare($insertQuery);
                $params = [];
                foreach ($permissions as $moduleId) {
                    $params[] = $roleId;
                    $params[] = $moduleId;
                }
                $types = str_repeat('si', count($permissions));
                $insertStmt->bind_param($types, ...$params);
                $insertStmt->execute();
                $insertStmt->close();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function filterAccounts($filters, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $queryStaff = "SELECT 
                        ua.ID AS accountID,
                        ua.userID,
                        ua.username,
                        u.fullname,
                        u.phone,
                        u.email,
                        u.address,
                        u.gender,
                        u.dateOfBirth,
                        r.ID AS roleID,
                        r.name AS roleName,
                        LOWER(ua.status) AS status,
                        CASE LOWER(ua.status)
                            WHEN 'active' THEN 'Hoạt động'
                            WHEN 'inactive' THEN 'Không hoạt động'
                            WHEN 'banned' THEN 'Bị cấm'
                            ELSE ua.status
                        END AS status_label,
                        ua.createdAt
                       FROM useraccount ua
                       JOIN user u ON ua.userID = u.ID
                       JOIN role r ON u.roleID = r.ID
                       WHERE u.roleID != '05'";
        $queryCustomer = "SELECT 
                           ua.ID AS accountID,
                           ua.userID,
                           ua.username,
                           u.fullname,
                           u.phone,
                           u.email,
                           u.address,
                           u.gender,
                           u.dateOfBirth,
                           LOWER(ua.status) AS status,
                           CASE LOWER(ua.status)
                               WHEN 'active' THEN 'Hoạt động'
                               WHEN 'inactive' THEN 'Không hoạt động'
                               WHEN 'banned' THEN 'Bị cấm'
                               ELSE ua.status
                           END AS status_label,
                           ua.createdAt
                          FROM useraccount ua
                          JOIN user u ON ua.userID = u.ID
                          WHERE u.roleID = '05'";

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
            $types .= 's';
        }

        if ($conditions) {
            $conditionStr = ' AND ' . implode(' AND ', $conditions);
            $queryStaff .= $conditionStr;
            $queryCustomer .= $conditionStr;
        }

        $queryStaff .= " LIMIT ? OFFSET ?";
        $queryCustomer .= " LIMIT ? OFFSET ?";
        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;

        $accounts = ['staff' => [], 'customer' => []];

        if ($filters['type'] === 'staff' || $filters['type'] === 'all') {
            $stmtStaff = $this->conn->prepare($queryStaff);
            if ($params) {
                $stmtStaff->bind_param($types, ...$params);
            }
            $stmtStaff->execute();
            $resultStaff = $stmtStaff->get_result();
            while ($row = $resultStaff->fetch_assoc()) {
                $accounts['staff'][] = $row;
            }
            $stmtStaff->close();
        }

        if ($filters['type'] === 'customer' || $filters['type'] === 'all') {
            $stmtCustomer = $this->conn->prepare($queryCustomer);
            if ($params) {
                $stmtCustomer->bind_param($types, ...$params);
            }
            $stmtCustomer->execute();
            $resultCustomer = $stmtCustomer->get_result();
            while ($row = $resultCustomer->fetch_assoc()) {
                $accounts['customer'][] = $row;
            }
            $stmtCustomer->close();
        }

        return $accounts;
    }
}
?>