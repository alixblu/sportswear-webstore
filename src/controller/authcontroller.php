<?php
include dirname(__FILE__) . '/../service/userservice.php';
include_once  dirname(__FILE__) . '/../config/response/apiresponse.php';
require_once dirname(__FILE__) . '/../utils/userUtils.php';

class AuthController
{

    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function login($userName, $passWord)
    {
        try {
            if (empty($userName) || empty($passWord)) {
                throw new Exception("Please enter complete information to login", 400);
            }

            $user = $this->userService->login($userName, $passWord);

            if (!$user) {
                throw new Exception("Invalid username or password", 401);
            }
            $_SESSION['user'] = [
                'id' => $user['userID'],
                'username' => $user['username'],
                'roleid' => $user['roleID'],
            ];

            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Login successful', 'user' => $user]);
            exit;
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }

        
    }
    public function updatePassword($currentPassword,$passWord ){
        try {
            $this->userService->updatePassword($currentPassword,$passWord);
            ApiResponse::customApiResponse('Cập Nhập Thành Công', 200);

        } catch (Exception $e) {
            throw $e;
        }
    }
    public function signup($name, $email, $passWord, $phone, $gender, $roleID)
    {
        try {
            if (empty($name) || empty($email) || empty($passWord) || empty($phone) || empty($gender)) {
                throw new Exception("Please enter complete information to sign up", 400);
            }

            $user = $this->userService->signup($name, $email, $passWord, $phone, $gender, $roleID);
            return ['success' => true, 'message' => 'Signup successful', 'user' => $user];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function logout()
    {
        try {
            $_SESSION = [];
            return ['success' => true, 'message' => 'Logout successful'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function info()
    {
        try {
            $user =  $this->userService->info();
            ApiResponse::customApiResponse($user, 200);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function updateUserLogin($name, $address, $birth, $phone, $gender)
    {
        try {
            $user =  $this->userService->updateUserLogin($name, $address, $birth, $phone, $gender);
            ApiResponse::customApiResponse($user, 200);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
