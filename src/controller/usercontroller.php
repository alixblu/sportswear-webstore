<?php
    include dirname(__FILE__) . '/../service/userservice.php';
    include_once  dirname(__FILE__) . '/../config/response/apiresponse.php';
    
    class UserController{

        private $userService;
    
        public function __construct()
        {
            $this->userService = new UserService();
        }

        public function defaultAccount($name, $email,$address, $phone, $gender, $roleID,$birthday){
            $user = $this->userService->defaultAccount($name, $email, $address,$phone, $gender, $roleID,$birthday);
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

        public function updateUsers($id, $name,$address, $phone, $gender, $roleID){
            $users = $this->userService->updateUser($id, $name,$address, $phone, $gender, $roleID);
            ApiResponse::customApiResponse($users,200);
        }

        public function deleteUsers($userId){
            $users = $this->userService->deleteUsers($userId);
            ApiResponse::customApiResponse($users,200);
        }

        public function getAllRoles(){
            $users = $this->userService->getAllRoles();
            ApiResponse::customApiResponse($users,200);
        }

        public function importExcel($file){
            $result = $this->userService->importExcel($file);
            ApiResponse::customApiResponse($result,200);
        }
        public function exportExcel(){
            $result = $this->userService->exportExcel();
            ApiResponse::customApiResponse($result,200);
        }
        public function search($keyword, $fields){
            $result = $this->userService->search($keyword, $fields);
            ApiResponse::customApiResponse($result,200);
        }

        public function getAccessModulesByRoleId($roleID) {
            try {
                // Create an instance of UserService
                $userService = new UserService();
                
                // Call the service method to get access modules
                $modules = $userService->getAccessModulesByRoleId($roleID);
                
                // Return the modules
                return $modules;
            } catch (Exception $e) {
                // Handle exceptions and return an error response if needed
                error_log("Error fetching access modules: " . $e->getMessage());
                throw new Exception("Unable to fetch access modules", 500);
            }
        }
    }

?>