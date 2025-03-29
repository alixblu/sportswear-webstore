<?php
    include './src/service/userservice.php';
    include './src/config/response/apiresponse.php';
    class UserController{

        private $userService;
    
        public function __construct()
        {
            $this->userService = new UserService();
        }

        public function login($userName, $passWord){
            if (empty($userName) || empty($passWord)) {
                throw new Exception("Please enter complete information to login",400);
            }
            $user = $this -> userService->login($userName, $passWord);
            $_SESSION['user'] =  [
                'id' => $user['userID'],
                'username' => $user['username'],

            ];        
            ApiResponse::customApiResponse("Login successful",200);
        }

        public function signup($userName, $passWord, $phone){
            if (empty($userName) || empty($passWord) || empty($phone)) {
                throw new Exception("Please enter complete information to sign up",400);
            }
            $user =  $this -> userService->signup($userName, $passWord,$phone);
            $_SESSION['user'] =  [
                'id' => $user['userID'],
                'username' => $user['username'],
                'roleid' => $user['roleID']
            ];  
            ApiResponse::customApiResponse("Signup successful",200);
        }

        public function logout(){
            $_SESSION = [];            
            ApiResponse::customApiResponse("Logout successful",200);
        }

    }
?>