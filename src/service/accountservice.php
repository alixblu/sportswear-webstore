<?php
require_once dirname(__FILE__) . '/../repository/accountrepository.php';

class AccountService {
    private $accountRepository;

    public function __construct() {
        $this->accountRepository = new AccountRepository();
    }

    public function createAccount($username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        try {
            // Xác thực đầu vào
            if (empty($username)) {
                throw new Exception("Tên đăng nhập là bắt buộc");
            }
            if (strlen($username) < 3) {
                throw new Exception("Tên đăng nhập phải có ít nhất 3 ký tự");
            }
            if (empty($password)) {
                throw new Exception("Mật khẩu là bắt buộc");
            }
            if (strlen($password) < 6) {
                throw new Exception("Mật khẩu phải có ít nhất 6 ký tự");
            }
            if (empty($fullname)) {
                throw new Exception("Họ và tên là bắt buộc");
            }
            if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
                throw new Exception("Số điện thoại phải có 10 hoặc 11 chữ số");
            }
            if (!is_numeric($roleId) || $roleId <= 0) {
                throw new Exception("Vai trò không hợp lệ");
            }
            if (!in_array($status, ['active', 'inactive', 'banned'])) {
                throw new Exception("Trạng thái không hợp lệ");
            }
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email không hợp lệ");
            }
            if ($gender && !in_array($gender, ['male', 'female', 'other'])) {
                throw new Exception("Giới tính không hợp lệ");
            }
            if ($dateOfBirth && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateOfBirth)) {
                throw new Exception("Ngày sinh không đúng định dạng (YYYY-MM-DD)");
            }

            // Kiểm tra username đã tồn tại
            $existingAccounts = $this->accountRepository->findAll();
            foreach ($existingAccounts as $account) {
                if ($account['username'] === $username) {
                    throw new Exception("Tên đăng nhập đã tồn tại");
                }
            }

            return $this->accountRepository->save($username, $password, $fullname, $phone, $roleId, $status, $email, $address, $gender, $dateOfBirth);
        } catch (Exception $e) {
            throw new Exception("Tạo tài khoản thất bại: " . $e->getMessage());
        }
    }

    public function getAllAccounts() {
        try {
            return $this->accountRepository->findAll();
        } catch (Exception $e) {
            throw new Exception("Lấy danh sách tài khoản thất bại: " . $e->getMessage());
        }
    }

    public function getAccountByUserID($accountId) {
        try {
            if (!is_numeric($accountId) || $accountId <= 0) {
                throw new Exception("ID tài khoản không hợp lệ");
            }
            $account = $this->accountRepository->getUserAccountIdByUserId($accountId);
            if (!$account) {
                throw new Exception("Không tìm thấy tài khoản với ID: $accountId");
            }
            return $account;
        } catch (Exception $e) {
            throw new Exception("Lấy thông tin tài khoản thất bại: " . $e->getMessage());
        }
    }

    public function getAccountById($accountId) {
        try {
            if (!is_numeric($accountId) || $accountId <= 0) {
                throw new Exception("ID tài khoản không hợp lệ");
            }
            $account = $this->accountRepository->findById($accountId);
            if (!$account) {
                throw new Exception("Không tìm thấy tài khoản với ID: $accountId");
            }
            return $account;
        } catch (Exception $e) {
            throw new Exception("Lấy thông tin tài khoản thất bại: " . $e->getMessage());
        }
    }

    public function updateAccount($accountId, $username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        try {
            // Xác thực đầu vào
            if (!is_numeric($accountId) || $accountId <= 0) {
                throw new Exception("ID tài khoản không hợp lệ");
            }
            if (empty($username)) {
                throw new Exception("Tên đăng nhập là bắt buộc");
            }
            if (strlen($username) < 3) {
                throw new Exception("Tên đăng nhập phải có ít nhất 3 ký tự");
            }
            if ($password && strlen($password) < 6) {
                throw new Exception("Mật khẩu mới phải có ít nhất 6 ký tự");
            }
            if (empty($fullname)) {
                throw new Exception("Họ và tên là bắt buộc");
            }
            if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
                throw new Exception("Số điện thoại phải có 10 hoặc 11 chữ số");
            }
            if (!is_numeric($roleId) || $roleId <= 0) {
                throw new Exception("Vai trò không hợp lệ");
            }
            if (!in_array($status, ['active', 'inactive', 'banned'])) {
                throw new Exception("Trạng thái không hợp lệ");
            }
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email không hợp lệ");
            }
            if ($gender && !in_array($gender, ['male', 'female', 'other'])) {
                throw new Exception("Giới tính không hợp lệ");
            }
            if ($dateOfBirth && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateOfBirth)) {
                throw new Exception("Ngày sinh không đúng định dạng (YYYY-MM-DD)");
            }

            // Kiểm tra nếu tài khoản là Admin (roleId = 1)
            $account = $this->accountRepository->findById($accountId);
            if ($account['roleID'] == 1) {
                if ($status !== $account['status']) {
                    throw new Exception("Không thể thay đổi trạng thái của tài khoản Admin");
                }
            }

            // Kiểm tra username đã tồn tại cho tài khoản khác
            $existingAccounts = $this->accountRepository->findAll();
            foreach ($existingAccounts as $account) {
                if ($account['username'] === $username && $account['accountID'] != $accountId) {
                    throw new Exception("Tên đăng nhập đã tồn tại");
                }
            }

            return $this->accountRepository->update($accountId, $username, $password, $fullname, $phone, $roleId, $status, $email, $address, $gender, $dateOfBirth);
        } catch (Exception $e) {
            throw new Exception("Cập nhật tài khoản thất bại: " . $e->getMessage());
        }
    }

    public function deleteAccount($accountId) {
        try {
            if (!is_numeric($accountId) || $accountId <= 0) {
                throw new Exception("ID tài khoản không hợp lệ");
            }
            return $this->accountRepository->delete($accountId);
        } catch (Exception $e) {
            throw new Exception("Xóa tài khoản thất bại: " . $e->getMessage());
        }
    }

    public function filterAccounts($filters) {
        try {
            if (!isset($filters['type']) || !in_array($filters['type'], ['staff', 'customer', 'all'])) {
                throw new Exception("Loại tài khoản không hợp lệ");
            }
            if (isset($filters['status']) && !in_array($filters['status'], ['all', 'active', 'inactive', 'banned'])) {
                throw new Exception("Trạng thái không hợp lệ");
            }
            if (isset($filters['roleID']) && $filters['roleID'] !== 'all' && (!is_numeric($filters['roleID']) || $filters['roleID'] <= 0)) {
                throw new Exception("ID vai trò không hợp lệ");
            }
            return $this->accountRepository->filterAccounts($filters);
        } catch (Exception $e) {
            throw new Exception("Lọc tài khoản thất bại: " . $e->getMessage());
        }
    }

    public function getPermissions($roleId) {
        try {
            if (!is_numeric($roleId) || $roleId <= 0) {
                throw new Exception("ID vai trò không hợp lệ");
            }
            return $this->accountRepository->getPermissions($roleId);
        } catch (Exception $e) {
            throw new Exception("Lấy quyền hạn thất bại: " . $e->getMessage());
        }
    }

    public function getAllModules() {
        try {
            return $this->accountRepository->getAllModules();
        } catch (Exception $e) {
            throw new Exception("Lấy danh sách module thất bại: " . $e->getMessage());
        }
    }

    public function getAllRoles() {
        try {
            return $this->accountRepository->getAllRoles();
        } catch (Exception $e) {
            throw new Exception("Lấy danh sách vai trò thất bại: " . $e->getMessage());
        }
    }

    public function updatePermissions($roleId, $moduleIds) {
        try {
            if (!is_numeric($roleId) || $roleId <= 0) {
                throw new Exception("ID vai trò không hợp lệ");
            }
            if (!is_array($moduleIds)) {
                throw new Exception("Danh sách module không hợp lệ");
            }

            // Xác thực moduleIds
            $validModules = $this->accountRepository->getAllModules();
            $validModuleIds = array_column($validModules, 'id');
            foreach ($moduleIds as $moduleId) {
                if (!in_array($moduleId, $validModuleIds)) {
                    throw new Exception("ID module không hợp lệ: $moduleId");
                }
            }

            return $this->accountRepository->updatePermissions($roleId, $moduleIds);
        } catch (Exception $e) {
            throw new Exception("Cập nhật quyền hạn thất bại: " . $e->getMessage());
        }
    }

    public function createRole($name, $moduleIds) {
        try {
            if (empty($name)) {
                throw new Exception("Tên vai trò là bắt buộc");
            }
            if (strlen($name) < 3) {
                throw new Exception("Tên vai trò phải có ít nhất 3 ký tự");
            }
            if (!is_array($moduleIds)) {
                throw new Exception("Danh sách module không hợp lệ");
            }
            
            // Xác thực moduleIds
            $validModules = $this->accountRepository->getAllModules();
            $validModuleIds = array_column($validModules, 'id');
            foreach ($moduleIds as $moduleId) {
                if (!in_array($moduleId, $validModuleIds)) {
                    throw new Exception("ID module không hợp lệ: $moduleId");
                }
            }

            return $this->accountRepository->createRole($name, $moduleIds);
        } catch (Exception $e) {
            throw new Exception("Tạo vai trò thất bại: " . $e->getMessage());
        }
    }
}
?>