<?php
    include dirname(__FILE__) . '/../service/userservice.php';
    include dirname(__FILE__) . '/../config/response/apiresponse.php';
    
    class UserController{

        private $userService;
    
        public function __construct()
        {
            $this->userService = new UserService();
        }

        public function defaultAccount($name, $email, $phone, $gender, $roleID){
            $user = $this->userService->defaultAccount($name, $email, $phone, $gender, $roleID);
            ApiResponse::customApiResponse($user,200);
        }
        
        public function getAccountByUserId($userId){
            $user = $this->userService->getAccountByUserId($userId);
            ApiResponse::customApiResponse($user,200);
        }

        public function getAllUsers(){
            $users = $this->userService->getAllUsers();
            ApiResponse::customApiResponse($users,200);
        }

        public function deleteUsers($userId){
            $users = $this->userService->deleteUsers($userId);
            ApiResponse::customApiResponse($users,200);
        }
    }

?>