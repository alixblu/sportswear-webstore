// Function to validate email format
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Function to validate phone number
function validatePhone(phone) {
    const phoneRegex = /^[0-9]{10}$/;
    return phoneRegex.test(phone);
}

// Function to validate password
function validatePassword(password) {
    return password.length >= 6;
}

// Function to handle login form submission
function handleLogin(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const email = formData.get('login-username');
    const password = formData.get('login-password');

    // Frontend validation
    if (!email || !password) {
        alert('Please enter both email and password');
        return false;
    }

    if (!validateEmail(email)) {
        alert('Please enter a valid email address');
        return false;
    }

    formData.append('submitLogin', '1');
    
    fetch('/sportswear-webstore/layout/auth.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if(data.user.roleID == 5) {
                window.location.reload(); 
            } else {
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
    
    // Get form values
    const name = formData.get('register-name');
    const email = formData.get('register-email');
    const password = formData.get('register-password');
    const confirmPassword = formData.get('register-confirm-pass');
    const phone = formData.get('register-phone');
    const gender = formData.get('register-gender');

    // Frontend validation
    if (!name || !email || !password || !confirmPassword || !phone || !gender) {
        alert('All fields are required');
        return false;
    }

    if (!validateEmail(email)) {
        alert('Please enter a valid email address');
        return false;
    }

    if (!validatePhone(phone)) {
        alert('Please enter a valid 10-digit phone number');
        return false;
    }

    if (!validatePassword(password)) {
        alert('Password must be at least 6 characters long');
        return false;
    }

    if (password !== confirmPassword) {
        alert('Passwords do not match');
        return false;
    }

    formData.append('submitRegister', '1');
    
    fetch('./layout/auth.php', {
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
            displayform('login');
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
