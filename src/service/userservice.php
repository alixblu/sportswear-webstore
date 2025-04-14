<?php
    include dirname(__FILE__) . '/../repository/userrepository.php';
    require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
    require_once dirname(__FILE__) .'/../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\IOFactory;
    
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

        public function updateUser($id, $name,  $phone, $gender, $roleID) {
            try {
                $user = $this->userRepository->userUpdate($id, $name,  $phone, $gender, $roleID);
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
        public function getAllRoles() {
            try {
                $users = $this->userRepository->getAllRoles();
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
        public function importExcel($file) {
            try {
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
        
                $first = true;
                $dataToInsert = [];
        
                foreach ($rows as $row) {
                    if ($first) { $first = false; continue; }
        
                    $name = $row[0] ?? '';
                    $email = $row[1] ?? '';
                    if (!$name || !$email) continue;
        
                    $dataToInsert[] = [
                        'name' => $name,
                        'email' => $email
                    ];
                }
                $this->userRepository->bulkInsert($dataToInsert);
                
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }
    }
?>