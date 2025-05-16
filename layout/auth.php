<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
    // Clear any previous output
    ob_clean();
    
    // Set headers for JSON response
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    
    try {   
        include __DIR__ . '/../src/controller/authcontroller.php';

        $authcontroller = new AuthController();
        $response = ['success' => false, 'message' => ''];
        
        if(isset($_POST['submitLogin'])){
            $userName = $_POST['login-username'];
            $passWord = $_POST['login-password'];
            
            $authcontroller->login($userName, $passWord);
        }else if(isset($_POST['submitRegister'])){
            $name = $_POST['register-name'];
            $email = $_POST['register-email'];
            $passWord = $_POST['register-password'];
            $phone = $_POST['register-phone'];
            $gender = $_POST['register-gender'];
            
            // Call signup with all required fields
            $roleID = 5; // Default role ID for new users
            $result = $authcontroller->signup($name, $email, $passWord, $phone, $gender, $roleID);
            $response = $result;
        
        }else if(isset($_POST['submitLogout'])){
            $result = $authcontroller->logout();
            $response = $result;
        } else {
            throw new Exception("Invalid form submission", 400);
        }

        // Send response for both login and register
        if(isset($_POST['submitLogin']) || isset($_POST['submitRegister']) || isset($_POST['submitLogout'])) {
            http_response_code(200);
            echo json_encode($response);
        }
        
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        http_response_code($e->getCode() ?: 400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } catch (Error $e) {
        error_log("PHP Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'An internal server error occurred']);
    }
    
    // End the request
    exit;
}
?>

<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!--=============== REMIXICONS ===============-->
      <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

     

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
                    <input type="password" name="login-password" required class="login__input" placeholder="">
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
    <form id="registerForm" class="login__form" style="display: none;" onsubmit="return handleRegister(event)">
        <h1 class="login__title">Register</h1>

        <div class="login__content">
            <!-- Name Field -->
            <div class="login__box">
                <i class="ri-user-3-line login__icon"></i>
                <div class="login__box-input">
                    <input type="text" name="register-name" required class="login__input" placeholder=" ">
                    <label for="register-name" class="login__label">Full Name</label>
                </div>
            </div>

            <!-- Gender Field -->
            <div class="login__box">
                <i class="ri-group-line login__icon"></i>
                <div class="login__box-input">
                    <div class="gender-container">
                        <label class="gender-label">
                            <input type="radio" name="register-gender" value="0" required>
                            <span>Male</span>
                        </label>
                        <label class="gender-label">
                            <input type="radio" name="register-gender" value="1" required>
                            <span>Female</span>
                        </label>
                    </div>
                    <label class="login__label">Gender</label>
                </div>
            </div>

            <!-- Phone Field -->
            <div class="login__box">
                <i class="ri-phone-line login__icon"></i>
                <div class="login__box-input">
                    <input type="tel" 
                           name="register-phone" 
                           required 
                           class="login__input" 
                           placeholder=" "
                           pattern="[0-9]{10}"
                           maxlength="10"
                           title="Please enter a valid 10-digit phone number">
                    <label for="register-phone" class="login__label">Phone number</label>
                </div>
            </div>

            <!-- Email Field -->
            <div class="login__box">
                <i class="ri-mail-line login__icon"></i>
                <div class="login__box-input">
                    <input type="email" name="register-email" required class="login__input" placeholder=" ">
                    <label for="register-email" class="login__label">Email</label>
                </div>
            </div>

            <!-- Password Field -->
            <div class="login__box">
                <i class="ri-lock-2-line login__icon"></i>
                <div class="login__box-input">
                    <input type="password" name="register-password" required class="login__input" placeholder=" ">
                    <label for="register-pass" class="login__label">Password</label>
                    <i class="login__eye"></i>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="login__box">
                <i class="ri-lock-password-line login__icon"></i>
                <div class="login__box-input">
                    <input type="password" name="register-confirm-pass" required class="login__input" placeholder=" ">
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


   </body>
</html>