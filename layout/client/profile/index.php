<?php
// profile.php
session_start();
if (!isset($_SESSION['user'])) {
    echo '<p>Please log in to view your profile.</p>';
    exit;
}

// Fetch user data from the database or session
$user = $_SESSION['user']; 

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
        <title>Client</title>


        <!-- font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="./css/profile.css">
        
    </head>

    <body>
        
        <main>
        <div class="wrapperContent">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="section">
                    <div class="section-title">Manage My Account</div>
                    <div class="section-items">
                        <!-- error,not fix yet -->
                        <div class="sidebar-item active" onclick="loadMyProfile()">My Profile</div>
                        <div class="sidebar-item">Address Book</div>
                        <div class="sidebar-item">My Payment Options</div>
                    </div>
                </div>
                <div class="section">
                    <div class="section-title">My Orders</div>
                    <div class="section-items">
                        <div class="sidebar-item">My Returns</div>
                        <div class="sidebar-item">My Cancellations</div>
                    </div>
                </div>
                <div class="section">
                    <div class="section-title">My WishList</div>
                </div>
            </div>

            <div class="profile-container">
                <!-- pages go here -->
                 <?php include 'profile.php'; ?>
            </div>
        </div>

        </main>
        
        <script >
        function loadMyProfile() {
            const pageContainer = document.querySelector('.profile-container');
            fetch('profile.php') // Fetch the profile page content
               .then(response => response.text())
               .then(data => {
                     pageContainer.innerHTML = data; // Load the profile content into the page-container
                     const overlay = document.getElementById('loginOverlay');
                     overlay.style.display = 'none'; // Hide the login overlay
               })
               .catch(error => console.error('Error loading profile:', error));
         }
        </script>
    </body>
    
</html>