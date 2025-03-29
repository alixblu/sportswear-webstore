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
   <form action="" id="loginForm" class="login__form">
        <h1 class="login__title">Login</h1>

        <div class="login__content">
            <div class="login__box">
                <i class="bx bx-lock-alt"></i>
                
                <div class="login__box-input">
                    <input type="email" required class="login__input" placeholder="">
                    <label for="" class="login__label">Username</label>
                </div>
            </div>

            <div class="login__box">
                <i class="ri-lock-2-line login__icon"></i>

                <div class="login__box-input">
                    <input type="password" required class="login__input" id="login-pas" placeholder="">
                    <label for="" class="login__label">Password</label>
                    <i class=" login__eye"></i>
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

        <button type="submit" class="login__button">Login</button>

        <p class="login__register">
            Don't have an account? <a href="javascript:void(0);" onclick="displayform('register')"><strong>Register here</strong></a>
        </p>
    </form>

    <!-- Register Form -->
    <form id="registerForm" class="login__form" style="display: none;">
        <h1 class="login__title">Register</h1>

        <div class="login__content">
            <!-- Name Field -->
            <div class="login__box">
                <i class="ri-user-3-line login__icon"></i>
                <div class="login__box-input">
                <input type="text" required class="login__input" id="register-name" placeholder=" ">
                <label for="register-name" class="login__label">Full Name</label>
                </div>
            </div>

            <!-- Gender Field -->
            <div class="login__box">
                <i class="ri-group-line login__icon"></i>
                <div class="login__box-input">
                <select required class="login__input" id="register-gender">
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
                <input type="email" required class="login__input" id="register-email" placeholder=" ">
                <label for="register-email" class="login__label">Email</label>
                </div>
            </div>

            <!-- Password Field -->
            <div class="login__box">
                <i class="ri-lock-2-line login__icon"></i>
                <div class="login__box-input">
                <input type="password" required class="login__input" id="register-pass" placeholder=" ">
                <label for="register-pass" class="login__label">Password</label>
                <i class=" login__eye"></i>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="login__box">
                <i class="ri-lock-password-line login__icon"></i>
                <div class="login__box-input">
                <input type="password" required class="login__input" id="register-confirm-pass" placeholder=" ">
                <label for="register-confirm-pass" class="login__label">Confirm Password</label>
                <i class=" login__eye"></i>
                </div>
            </div>
        </div>

        <button type="submit" name="submitRegister" class="login__button">Register</button>

        <p class="login__register">
            Already have an account? <a href="javascript:void(0);" onclick="displayform('login')"><strong>Login here</strong></a>
        </p>
    </form>

    <script src="JS/login_regis.js"></script>

   </body>
</html>