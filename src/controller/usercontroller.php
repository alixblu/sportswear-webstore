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
        

    }

?>