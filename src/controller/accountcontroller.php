<?php
include dirname(__FILE__) . '/../service/accountService.php';
include dirname(__FILE__) . '/../config/response/apiresponse.php';

class AccountController {
    private $accountService;

    public function __construct() {
        $this->accountService = new AccountService();
    }

    public function getAllAccounts() {
        try {
            $accounts = $this->accountService->getAllAccounts();
            ApiResponse::customApiResponse($accounts, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getAccountById($accountId) {
        try {
            $account = $this->accountService->getAccountById($accountId);
            ApiResponse::customApiResponse($account, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 404);
        }
    }

    public function createAccount($username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        try {
            $result = $this->accountService->createAccount($username, $password, $fullname, $phone, $roleId, $status, $email, $address, $gender, $dateOfBirth);
            ApiResponse::customApiResponse($result, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function updateAccount($accountId, $username, $password, $fullname, $phone, $roleId, $status, $email = null, $address = null, $gender = null, $dateOfBirth = null) {
        try {
            $result = $this->accountService->updateAccount($accountId, $username, $password, $fullname, $phone, $roleId, $status, $email, $address, $gender, $dateOfBirth);
            ApiResponse::customApiResponse($result, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteAccount($accountId) {
        try {
            $result = $this->accountService->deleteAccount($accountId);
            ApiResponse::customApiResponse($result, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function filterAccounts($filters) {
        try {
            $accounts = $this->accountService->filterAccounts($filters);
            ApiResponse::customApiResponse($accounts, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getPermissions($roleId) {
        try {
            $permissions = $this->accountService->getPermissions($roleId);
            ApiResponse::customApiResponse($permissions, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getAllModules() {
        try {
            $modules = $this->accountService->getAllModules();
            ApiResponse::customApiResponse($modules, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllRoles() {
        try {
            $roles = $this->accountService->getAllRoles();
            ApiResponse::customApiResponse($roles, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 500);
        }
    }
}
?>