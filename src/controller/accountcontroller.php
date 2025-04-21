<?php
// File: src/controller/accountcontroller.php
require_once __DIR__ . '/../service/AccountService.php';
require_once __DIR__ . '/../enums/AccountStatus.php';

class AccountController {
    private $accountService;

    public function __construct() {
        $this->accountService = new AccountService();
    }

    /**
     * Hiển thị trang quản lý tài khoản
     */
    public function renderAccountPage() {
        try {
            $staffAccounts = $this->accountService->filterAccounts(['type' => 'staff'])['staff'] ?? [];
            $customerAccounts = $this->accountService->filterAccounts(['type' => 'customer'])['customer'] ?? [];
            $roles = $this->accountService->getAllRoles();

            include __DIR__ . '/../../layout/admin/modules/account.php';
        } catch (Exception $e) {
            require_once __DIR__ . '/../config/exception/exceptionhandler.php';
            handleException($e);
        }
    }

    /**
     * Lấy chi tiết tài khoản
     */
    public function getAccountDetails($accountId) {
        try {
            $account = $this->accountService->getAccountDetails($accountId);
            $this->sendResponse(200, 'Thành công', $account);
        } catch (Exception $e) {
            $this->sendResponse(500, 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Lấy quyền hạn của vai trò
     */
    public function getPermissions($roleId) {
        try {
            $permissions = $this->accountService->getPermissions($roleId);
            $this->sendResponse(200, 'Thành công', $permissions);
        } catch (Exception $e) {
            $this->sendResponse(500, 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Lấy tất cả module
     */
    public function getAllModules() {
        try {
            $modules = $this->accountService->getAllModules();
            $this->sendResponse(200, 'Thành công', $modules);
        } catch (Exception $e) {
            $this->sendResponse(500, 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Lấy tất cả vai trò
     */
    public function getAllRoles() {
        try {
            $roles = $this->accountService->getAllRoles();
            $this->sendResponse(200, 'Thành công', $roles);
        } catch (Exception $e) {
            $this->sendResponse(500, 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật tài khoản
     */
    public function updateAccount($data) {
        try {
            $result = $this->accountService->updateAccount($data);
            $this->sendResponse(200, 'Cập nhật tài khoản thành công', $result);
        } catch (Exception $e) {
            $this->sendResponse(500, 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật quyền hạn
     */
    public function updatePermissions($data) {
        try {
            if (!isset($data['roleId']) || !isset($data['permissions'])) {
                throw new Exception('Thiếu dữ liệu cần thiết');
            }
            $result = $this->accountService->updatePermissions($data['roleId'], $data['permissions']);
            $this->sendResponse(200, 'Cập nhật quyền hạn thành công', $result);
        } catch (Exception $e) {
            $this->sendResponse(500, 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Lọc tài khoản
     */
    public function filterAccounts($data) {
        try {
            $accounts = $this->accountService->filterAccounts($data);
            $this->sendResponse(200, 'Lọc tài khoản thành công', $accounts);
        } catch (Exception $e) {
            $this->sendResponse(500, 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Gửi phản hồi JSON
     */
    private function sendResponse($status, $message, $data = null) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);
        $response = [
            'status' => $status,
            'message' => $message
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>
