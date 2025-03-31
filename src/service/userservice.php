<?php
if (!class_exists('UserService')) {
    include dirname(__FILE__) . '/../repository/userrepository.php';
    require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
    
    class UserService{
        private $userRepository;
    
        public function __construct()
        {
            $this->userRepository = new UserRepository();
        }

        public function login($userName, $passWord) {
            try {
                $user = $this->userRepository->findUserByUsername($userName);

                if (!$user || $user["password"] !== $passWord) {
                    throw new Exception("Incorrect username or password", 400);
                }
            
                return $user;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }        
        public function signup($userName, $passWord, $phone) {
            try {
                $existingUser = $this->userRepository->findUserByUsername($userName);
            
                if ($existingUser) {
                    throw new Exception("User already exists", 400);
                }
                
                $user = $this->userRepository->save($userName, $passWord, $phone);
                if (!$user) {
                    throw new Exception("Failed to create user", 500);
                }
            
                return $user;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }
    }
}
?>