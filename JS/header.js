/*=============== SHOW MENU ===============*/
const showMenu = (toggleId, navId) =>{
const toggle = document.getElementById(toggleId),
 nav = document.getElementById(navId)

toggle.addEventListener('click', () =>{
// Add show-menu class to nav menu
nav.classList.toggle('show-menu')

// Add show-icon to show and hide the menu icon
toggle.classList.toggle('show-icon')
})
}

showMenu('nav-toggle','nav-menu')

document.querySelector('.nav__account').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior
 
    const formContainer = document.getElementById('main-page-container');
    if (formContainer) { // Check if formContainer exists

        fetch('./layout/login.php')
            .then(response => response.text())
            .then(html => {
            formContainer.innerHTML = html;
            })
            .catch(error => console.error('Error loading form:', error));

    }
    else {
       console.error('Form container not found');
    }
 });


