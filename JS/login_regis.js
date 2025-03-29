/*=============== SHOW HIDDEN - PASSWORD ===============*/
document.addEventListener('DOMContentLoaded', function() {
    // Get all password input fields
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    
    // Add click event to each password field's eye icon
    passwordInputs.forEach(input => {
        const eyeIcon = input.nextElementSibling.nextElementSibling; // Gets the eye icon after the label
        if (eyeIcon && eyeIcon.classList.contains('login__eye')) {
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
        if (loginForm) loginForm.style.display = 'none';
        if (registerForm) registerForm.style.display = 'block';
    } else {
        if (registerForm) registerForm.style.display = 'none';
        if (loginForm) loginForm.style.display = 'block';
    }
}

