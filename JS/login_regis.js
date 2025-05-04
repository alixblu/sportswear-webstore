// Function to handle login form submission
function handleLogin(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    formData.append('submitLogin', '1');
    
    fetch('./layout/login_regis.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // alert(JSON.stringify(data, null, 2));
            if(data.user.roleID === 5) {
                window.location.reload(); 
            } else {
                // Redirect non-customers
                window.location.href = './layout/admin/index.php';
            }
        } else {
            alert('Login failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Login failed: ' + error.message);
    });
    return false;
}

// Function to handle register form submission
function handleRegister(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    formData.append('submitRegister', '1');
    
    fetch('./layout/login_regis.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Registration failed');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            displayform('login'); // Switch to login form after successful registration
        } else {
            alert('Registration failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Registration failed: ' + error.message);
    });
    
    return false;
}

// Function to switch between login and register forms
function displayform(formName) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (formName === 'register') {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
    } else {
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
    }
}

// Password visibility toggle
document.addEventListener('DOMContentLoaded', function() {
    const passwordInputs = document.querySelectorAll('.login__box-input');
    passwordInputs.forEach(box => {
        const input = box.querySelector('input[type="password"]');
        const eyeIcon = box.querySelector('.login__eye');
        if (input && eyeIcon) {
            eyeIcon.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                eyeIcon.classList.toggle('ri-eye-line');
                eyeIcon.classList.toggle('ri-eye-off-line');
            });
        }
    });
});
