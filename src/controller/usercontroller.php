<?php
if (!class_exists('UserController')) {
    include dirname(__FILE__) . '/../service/userservice.php';
    include dirname(__FILE__) . '/../config/response/apiresponse.php';
    
    class UserController{

        private $userService;
    
        public function __construct()
        {
            $this->userService = new UserService();
        }

        public function login($userName, $passWord){
            try {
                if (empty($userName) || empty($passWord)) {
                    throw new Exception("Please enter complete information to login", 400);
                }
                
                $user = $this->userService->login($userName, $passWord);
                
                if (!$user) {
                    throw new Exception("Invalid username or password", 401);
                }
                
                $_SESSION['user'] =  [
                    'id' => $user['userID'],
                    'username' => $user['username'],
                    'roleid' => $user['roleID']
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

        public function signup($userName, $passWord, $phone, $gender){
            try {
                if (empty($userName) || empty($passWord) || empty($phone)) {
                    throw new Exception("Please enter complete information to sign up", 400);
                }

                $user = $this->userService->signup($userName, $passWord, $phone);
                $_SESSION['user'] =  [
                    'id' => $user['userID'],
                    'username' => $user['username'],
                    'roleid' => $user['roleID']
                ];  
                
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Signup successful']);
                exit;
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 400);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }

        public function logout(){
            try {
                $_SESSION = [];            
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Logout successful']);
                exit;
            } catch (Exception $e) {
                http_response_code($e->getCode() ?: 400);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }
    }
}
?>