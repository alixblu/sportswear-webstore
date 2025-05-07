<?php
    include dirname(__FILE__) . '/../repository/userrepository.php';
    include dirname(__FILE__) . '/../utils/ExcelUtils.php';
    require_once dirname(__FILE__) . '/../utils/userUtils.php';

    class UserService{
        private $userRepository;
        private $excelUtils;
        private $userUtils;

        public function __construct()
        {
            $this->userRepository = new UserRepository();
            $this->excelUtils = new ExcelUtils();
            $this->userUtils = new UserUtils();

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
        public function info() {
            try {
                $userName = $this->userUtils->getUserName();
                $user = $this->userRepository->findUserByUsername($userName);

                return $user;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }     
        public function getAccessModulesByRoleId($roleID) {
            try {
                // Call the repository method to get access modules
                $modules = $this->userRepository->getAccessModulesByRoleId($roleID);
                return $modules;
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

        public function updateUser($id, $name, $address, $phone, $gender, $roleID) {
            try {
                $user = $this->userRepository->userUpdate($id, $name, $address, $phone, $gender, $roleID);
                if (!$user) {
                    throw new Exception("Failed to update user", 500);
                }
                return $user;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }
        public function updateUserLogin($name, $address, $newPassword) {
            try {
                $id = $this->userUtils->getUserId();
                if($newPassword!=null){
                    $user = $this->userRepository->updatePasswordByUserId($id, $newPassword);
                }
                $user = $this->userRepository->userUpdate($id, $name, $address, null, null,null);
                if (!$user) {
                    throw new Exception("Failed to update user", 500);
                }
                return $user;
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }
        public function defaultAccount($name, $email, $address,$phone, $gender, $roleID,$birthday) {
            try {
                $passWordDefault ='123456';

                if ($this->isEmailExists($email)) {
                    throw new Exception("Email already exists", 400);
                }
                return $this->userRepository->save($name,$email, $passWordDefault, $phone, $gender, $roleID,$address,$birthday);

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
                $rows = $this->excelUtils->readExcelRows($file);
        
                $first = true;
                $dataToInsert = [];
        
                foreach ($rows as $row) {
                    if ($first) { 
                        $first = false; 
                        continue; 
                    }
        
                    $fullName    = $row[0] ?? '';
                    $dateOfBirth = $row[1] ?? '';
                    $email       = $row[2] ?? '';
                    $phone       = $row[3] ?? '';
                    $address     = $row[4] ?? '';
                    $gender      = $row[5] ?? '';
                    $roleID      = $row[6] ?? '';
                    $createdAt   = $row[7] ?? '';
        
                    $dataToInsert[] = [
                        'full_name'     => $fullName,
                        'date_of_birth' => $dateOfBirth,
                        'email'         => $email,
                        'phone'         => $phone,
                        'address'       => $address,
                        'gender'        => $gender,
                        'role_id'       => $roleID,
                        'created_at'    => $createdAt
                    ];
                }
        
                $this->userRepository->bulkInsertWithNPlus1($dataToInsert);
        
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode() ?: 400);
            }
        }
        public function exportExcel()
        {
            try {
                $users = $this->userRepository->findAllUsers();

                $dataToExport = [];
                foreach ($users as $user) {
                    $dataToExport[] = [
                        'full_name'    => $user['fullname'] ?? '',
                        'date_of_birth' => $user['dateOfBirth'] ?? '',
                        'email'        => $user['email'] ?? '',
                        'phone'        => $user['phone'] ?? '',
                        'address'      => $user['address'] ?? '',
                        'gender'       => $user['gender'] ?? '',
                        'role_id'      => $user['roleID'] ?? '',
                        'created_at'   => $user['createdAt'] ?? ''
                    ];
                }

                $headers = [
                    'Họ và tên', 'Ngày sinh', 'Email', 'SĐT', 'Địa chỉ', 'Giới tính', 'Role ID', 'Ngày tạo'
                ];

                $excelUtils = new ExcelUtils();
                $excelUtils->exportExcel($dataToExport, $headers, 'user_list.xlsx');
            } catch (Exception $e) {
                throw new Exception("Lỗi export Excel: " . $e->getMessage(), $e->getCode() ?: 400);
            }
        }
        public function search($keyword, $fields) {
            try {
                return $this->userRepository->search($keyword, $fields);
        
            } catch (Exception $e) {
                throw new Exception("Lỗi import Excel: " . $e->getMessage(), $e->getCode() ?: 400);
            }
        }
    }
?>