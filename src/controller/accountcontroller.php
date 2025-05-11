<?php
require_once dirname(__FILE__) . '/../service/accountservice.php';
require_once dirname(__FILE__) . '/../config/response/apiresponse.php';

class AccountController {
    private $accountService;

    public function __construct() {
        $this->accountService = new AccountService();
    }

    public function getAllAccounts() {
        try {
            $accounts = $this->accountService->getAllAccounts();
            ApiResponse::customResponse($accounts, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    public function getAccountById($accountId) {
        try {
            if (!isset($accountId) || !is_numeric($accountId)) {
                ApiResponse::customResponse(null, 400, 'ID tài khoản không hợp lệ');
                return;
            }
            $account = $this->accountService->getAccountById($accountId);
            ApiResponse::customResponse($account, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 404, $e->getMessage());
        }
    }

    public function createAccount($username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        try {
            $result = $this->accountService->createAccount($username, $password, $fullname, $phone, $roleId, $status, $email, $address, $gender, $dateOfBirth);
            ApiResponse::customResponse($result, 201, 'Tạo tài khoản thành công');
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 400, $e->getMessage());
        }
    }

    public function updateAccount($accountId, $username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        try {
            if (!isset($accountId) || !is_numeric($accountId)) {
                ApiResponse::customResponse(null, 400, 'ID tài khoản không hợp lệ');
                return;
            }
            $result = $this->accountService->updateAccount($accountId, $username, $password, $fullname, $phone, $roleId, $status, $email, $address, $gender, $dateOfBirth);
            ApiResponse::customResponse($result, 200, 'Cập nhật tài khoản thành công');
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 400, $e->getMessage());
        }
    }

    public function deleteAccount($accountId) {
        try {
            if (!isset($accountId) || !is_numeric($accountId)) {
                ApiResponse::customResponse(null, 400, 'ID tài khoản không hợp lệ');
                return;
            }
            $result = $this->accountService->deleteAccount($accountId);
            ApiResponse::customResponse($result, 200, 'Xóa tài khoản thành công');
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 400, $e->getMessage());
        }
    }

    public function filterAccounts($filters) {
        try {
            $accounts = $this->accountService->filterAccounts($filters);
            ApiResponse::customResponse($accounts, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    public function getPermissions($roleId) {
        try {
            if (!isset($roleId) || !is_numeric($roleId)) {
                ApiResponse::customResponse(null, 400, 'ID vai trò không hợp lệ');
                return;
            }
            $permissions = $this->accountService->getPermissions($roleId);
            ApiResponse::customResponse($permissions, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 400, $e->getMessage());
        }
    }

    public function getAllModules() {
        try {
            $modules = $this->accountService->getAllModules();
            ApiResponse::customResponse($modules, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    public function getAllRoles() {
        try {
            $roles = $this->accountService->getAllRoles();
            ApiResponse::customResponse($roles, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    public function updatePermissions($roleId, $moduleIds) {
        try {
            if (!isset($roleId) || !is_numeric($roleId)) {
                ApiResponse::customResponse(null, 400, 'ID vai trò không hợp lệ');
                return;
            }
            if (!isset($moduleIds) || !is_array($moduleIds)) {
                ApiResponse::customResponse(null, 400, 'Danh sách module không hợp lệ');
                return;
            }
            $result = $this->accountService->updatePermissions($roleId, $moduleIds);
            ApiResponse::customResponse($result, 200, 'Cập nhật quyền thành công');
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 400, $e->getMessage());
        }
    }
}
?>