
<?php
require_once dirname(__FILE__) . '/../repository/accountreposity.php';

class AccountService {
    private $accountRepository;

    public function __construct() {
        $this->accountRepository = new AccountRepository();
    }

    public function createAccount($username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        try {
            if (empty($username)) {
                throw new Exception("Tên đăng nhập là bắt buộc");
            }
            if (empty($password)) {
                throw new Exception("Mật khẩu là bắt buộc");
            }
            if (empty($fullname)) {
                throw new Exception("Họ và tên là bắt buộc");
            }
            if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
                throw new Exception("Số điện thoại không hợp lệ");
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

    public function getAccountById($accountId) {
        try {
            if (!is_numeric($accountId) || $accountId <= 0) {
                throw new Exception("ID tài khoản không hợp lệ");
            }
            $account = $this->accountRepository->findById($accountId);
            if (!$account) {
                throw new Exception("Không tìm thấy tài khoản");
            }
            return $account;
        } catch (Exception $e) {
            throw new Exception("Lấy thông tin tài khoản thất bại: " . $e->getMessage());
        }
    }

    public function updateAccount($accountId, $username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        try {
            if (!is_numeric($accountId) || $accountId <= 0) {
                throw new Exception("ID tài khoản không hợp lệ");
            }
            if (empty($username)) {
                throw new Exception("Tên đăng nhập là bắt buộc");
            }
            if (empty($fullname)) {
                throw new Exception("Họ và tên là bắt buộc");
            }
            if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
                throw new Exception("Số điện thoại không hợp lệ");
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
}
?>
