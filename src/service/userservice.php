<?php
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
                    throw new Exception("Incorrect email or password", 400);
                }
            
                return $user;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }        
        public function signup($name, $email, $passWord, $phone, $gender, $roleID) {
            try {
                if ($this->isEmailExists($email)) {
                    throw new Exception("Email already exists", 400);
                }
                
                $user = $this->userRepository->save($name, $email, $passWord, $phone, $gender, $roleID);
                if (!$user) {
                    throw new Exception("Failed to create user", 500);
                }
            
                return $user;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }

        public function defaultAccount($name, $email, $phone, $gender, $roleID) {
            try {
                $passWordDefault ='123456';

                if ($this->isEmailExists($email)) {
                    throw new Exception("Email already exists", 400);
                }
                $this->userRepository->save($name, $email, $passWordDefault, $phone, $gender, $roleID);

            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }

        public function getAccountByUserId($userId) {
            try {
                $user = $this->userRepository->findAccountByUserId($userId);
                return $user;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }

        public function getAllUsers() {
            try {
                $users = $this->userRepository->findAllUsers();
                return $users;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }

        public function deleteUsers($userId){
            try {
                $users = $this->userRepository->deleteByUserID($userId);
                return $users;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }

        public function isEmailExists($email) {
            try {
                $existingUser = $this->userRepository->findUserByEmail($email);
            
                if ($existingUser) {
                    return true;
                }
                return false;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }
        
    }
?>