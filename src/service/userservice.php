<?php
    include './src/repository/userrepository.php';
    include './src/config/mysqli/mysqli.php';
    class UserService{
        private $userRepository;
    
        public function __construct()
        {
            $this->userRepository = new UserRepository();
        }

        public function login($userName, $passWord) {
            $user = $this->userRepository->findUserByUsername($userName);
        
            if (!$user || $user["password"] !== $passWord) {
                throw new Exception("Incorrect username or password",400);
            }
        
            return $user;
        }        
        public function signup($userName, $passWord ,$phone) {
            $existingUser = $this->userRepository->findUserByUsername($userName);
        
            if ($existingUser) {
                throw new Exception("User already exists",400);
            }
        
            $user = $this->userRepository->save($userName, $passWord,$phone);
            if (!$user) {
                throw new Exception("Failed to create user",500);
            }
        
            return $user;
        }
    }
?>