/*=============== SHOW HIDDEN - PASSWORD ===============*/
document.addEventListener('DOMContentLoaded', function() {
    // Get all password input fields with their corresponding eye icons
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

