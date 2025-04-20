<?php
// File: src/service/AccountService.php
require_once __DIR__ . '/../repository/AccountRepository.php';
require_once __DIR__ . '/../enums/AccountStatus.php';

class AccountService {
    private $accountRepository;

    public function __construct() {
        $this->accountRepository = new AccountRepository();
    }

    public function getAccountDetails($accountId) {
        if (!is_numeric($accountId)) {
            throw new Exception('ID tài khoản không hợp lệ');
        }
        $account = $this->accountRepository->getAccountDetails($accountId);
        if (!$account) {
            throw new Exception('Không tìm thấy tài khoản');
        }
        return $account;
    }

    public function getPermissions($roleId) {
        if (!preg_match('/^[0-9A-Z]{2}$/', $roleId)) {
            throw new Exception('ID vai trò không hợp lệ');
        }
        return $this->accountRepository->getPermissions($roleId);
    }

    public function getAllModules() {
        return $this->accountRepository->getAllModules();
    }

    public function getAllRoles() {
        return $this->accountRepository->getAllRoles();
    }

    public function updateAccount($data) {
        $requiredFields = ['accountId', 'username', 'fullname', 'phone', 'status'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Thiếu trường bắt buộc: $field");
            }
        }

        if (!preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            throw new Exception('Số điện thoại không hợp lệ');
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email không hợp lệ');
        }

        if (isset($data['gender']) && !in_array($data['gender'], ['1', '0', null])) {
            throw new Exception('Giới tính không hợp lệ');
        }

        if (!empty($data['dateOfBirth']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['dateOfBirth'])) {
            throw new Exception('Ngày sinh không hợp lệ');
        }

        try {
            AccountStatus::from($data['status']);
        } catch (ValueError $e) {
            throw new Exception('Trạng thái không hợp lệ');
        }

        if (isset($data['roleId']) && !preg_match('/^[0-9A-Z]{2}$/', $data['roleId'])) {
            throw new Exception('Vai trò không hợp lệ');
        }

        return $this->accountRepository->updateAccount($data);
    }

    public function updatePermissions($roleId, $permissions) {
        if (!preg_match('/^[0-9A-Z]{2}$/', $roleId)) {
            throw new Exception('ID vai trò không hợp lệ');
        }

        if (!is_array($permissions)) {
            throw new Exception('Quyền hạn phải là mảng');
        }

        foreach ($permissions as $perm) {
            if (!is_numeric($perm)) {
                throw new Exception('ID quyền hạn không hợp lệ');
            }
        }

        return $this->accountRepository->updatePermissions($roleId, $permissions);
    }

    public function filterAccounts($data) {
        if (!isset($data['type']) || !in_array($data['type'], ['staff', 'customer', 'all'])) {
            throw new Exception('Loại tài khoản không hợp lệ');
        }
        return $this->accountRepository->filterAccounts($data);
    }
}
?>