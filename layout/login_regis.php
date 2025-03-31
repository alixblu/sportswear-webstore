<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if this is a POST request
if($_SERVER['REQUEST_METHOD']==='POST'){
    // Prevent any output before headers
    ob_clean();
    
    // Set header to indicate JSON response for POST requests
    header('Content-Type: application/json');
    
    try {
        // Check if file exists before including
        $controllerPath = __DIR__ . '/../src/controller/usercontroller.php';
        if (!file_exists($controllerPath)) {
            throw new Exception("Controller file not found at: " . $controllerPath, 500);
        }
        
        include $controllerPath;
        
        if (!class_exists('UserController')) {
            throw new Exception("UserController class not found", 500);
        }
        
        $userController = new UserController();
        
        if(isset($_POST['submitLogin'])){
            $userName = $_POST['login-username'];
            $passWord = $_POST['login-password'];
            
            if (empty($userName) || empty($passWord)) {
                throw new Exception("Please enter both username and password", 400);
            }
            
            $userController->login($userName, $passWord);
        }else if(isset($_POST['submitRegister'])){
            $userName = $_POST['register-name'];
            $passWord = $_POST['register-password'];
            $phone = $_POST['register-phone'];
            $gender = $_POST['register-gender'];
            $confirmPassWord = $_POST['register-confirm-pass'];
            
            if($passWord !== $confirmPassWord){
                throw new Exception("Passwords do not match", 400);
            }
            
            $userController->signup($userName, $passWord, $phone, $gender);
        }else if(isset($_POST['submitLogout'])){
            $userController->logout();
            exit;
        } else {
            throw new Exception("Invalid form submission", 400);
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        http_response_code($e->getCode() ?: 400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
    exit; // Ensure we exit after handling POST request
}

// For non-POST requests, display the form
try {
    $controllerPath = __DIR__ . '/../src/controller/usercontroller.php';
    if (!file_exists($controllerPath)) {
        throw new Exception("Controller file not found at: " . $controllerPath, 500);
    }
    include $controllerPath;
} catch (Exception $e) {
    error_log("Error loading controller: " . $e->getMessage());
    die("Error loading controller: " . $e->getMessage());
}
?>

<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!--=============== REMIXICONS ===============-->
      <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

      <!--=============== CSS ===============-->
      <link rel="stylesheet" href="./css/login_regis.css">

   </head>
   <body>
   <form action="" id="loginForm" class="login__form" onsubmit="return handleLogin(event)">
        <h1 class="login__title">Login</h1>

        <div class="login__content">
            <div class="login__box">
                <i class="bx bx-lock-alt"></i>
                
                <div class="login__box-input">
                    <input type="email" name="login-username" required class="login__input" placeholder="">
                    <label for="" class="login__label">Email</label>
                </div>
            </div>

            <div class="login__box">
                <i class="ri-lock-2-line login__icon"></i>

                <div class="login__box-input">
                    <input type="password" name="login-password" required class="login__input" id="login-pas" placeholder="">
                    <label for="" class="login__label">Password</label>
                    <i class="login__eye"></i>
                </div>
            </div>
        </div>

        <div class="login__check">
            <div class="login__check-group">
                <input type="checkbox" class="login__check-input">
                <label for="" class="login__check-label">Remember me</label>
            </div>

            <a href="#" class="login__forgot">Forgot Password?</a>
        </div>

        <button type="submit" name="submitLogin" value="1" class="login__button">Login</button>

        <p class="login__register">
            Don't have an account? <a href="javascript:void(0);" onclick="displayform('register')"><strong>Register here</strong></a>
        </p>
    </form>

    <!-- Register Form -->
    <form method="POST" id="registerForm" class="login__form" style="display: none;" onsubmit="return handleRegister(event)">
        <h1 class="login__title">Register</h1>

        <div class="login__content">
            <!-- Name Field -->
            <div class="login__box">
                <i class="ri-user-3-line login__icon"></i>
                <div class="login__box-input">
                <input type="text" name="register-name" required class="login__input" id="register-name" placeholder=" ">
                <label for="register-name" class="login__label">Full Name</label>
                </div>
            </div>

            <!-- Gender Field -->
            <div class="login__box">
                <i class="ri-group-line login__icon"></i>
                <div class="login__box-input">
                <select required class="login__input" id="register-gender" name="register-gender" >
                    <option value="" disabled selected>Choose gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <label for="register-gender" class="login__label">Gender</label>
                </div>
            </div>

            <!-- Email Field -->
            <div class="login__box">
                <i class="ri-mail-line login__icon"></i>
                <div class="login__box-input">
                <input type="email" name="register-email" required class="login__input" id="register-email" placeholder=" ">
                <label for="register-email" class="login__label">Email</label>
                </div>
            </div>

            <!-- Password Field -->
            <div class="login__box">
                <i class="ri-lock-2-line login__icon"></i>
                <div class="login__box-input">
                <input type="password" name="register-password" required class="login__input" id="register-pass" placeholder=" ">
                <label for="register-pass" class="login__label">Password</label>
                <i class="login__eye"></i>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="login__box">
                <i class="ri-lock-password-line login__icon"></i>
                <div class="login__box-input">
                <input type="password" name="register-confirm-pass" required class="login__input" id="register-confirm-pass" placeholder=" ">
                <label for="register-confirm-pass" class="login__label">Confirm Password</label>
                <i class="login__eye"></i>
                </div>
            </div>
        </div>

        <button type="submit" name="submitRegister" class="login__button">Register</button>

        <p class="login__register">
            Already have an account? <a href="javascript:void(0);" onclick="displayform('login')"><strong>Login here</strong></a>
        </p>
    </form>

    <script src="./JS/login_regis.js"></script> 

   </body>
</html>