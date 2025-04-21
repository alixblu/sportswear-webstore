var dashboard_swtitch = document.getElementById('dashboard_switch');

dashboard_swtitch.addEventListener('click', function() {
    var sideBar = document.getElementsByClassName('sidebar');
    var container = document.getElementsByClassName('container');

    sideBar.item(0).classList.toggle('collapsed');
    container.item(0).classList.toggle('sidebar-collapsed');
});

function navigate(ele){
    const page = ele.getAttribute('data-page');
    window.location.href = `index.php?page=${page}`;
}

window.addEventListener('DOMContentLoaded', function() {
    const currentPage = new URLSearchParams(window.location.search).get('page');
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        const page = item.getAttribute('data-page');
        if (page === currentPage) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
});