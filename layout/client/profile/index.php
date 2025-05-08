<?php
// profile.php
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
                    <div class="section-title">
                        <i class="ri-shopping-bag-line" style="color: #000;"></i>
                        Shopping
                    </div>
                    <div class="section-items">
                        <div class="sidebar-item">
                            <i class="ri-heart-line" style="color: #000;"></i>
                            My Wishlist
                        </div>
                        <div class="sidebar-item">
                            <i class="ri-shopping-cart-line" style="color: #000;"></i>
                            My Orders
                        </div>
                        <div class="sidebar-item">
                            <i class="ri-arrow-left-right-line" style="color: #000;"></i>
                            My Returns
                        </div>
                        <div class="sidebar-item">
                            <i class="ri-close-circle-line" style="color: #000;"></i>
                            My Cancellations
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">
                        <i class="ri-feedback-line" style="color: #000;"></i>
                        Feedback
                    </div>
                    <div class="section-items">
                        <div class="sidebar-item">
                            <i class="ri-star-line" style="color: #000;"></i>
                            My Reviews
                        </div>
                        <div class="sidebar-item">
                            <i class="ri-question-line" style="color: #000;"></i>
                            My Questions
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="section-title">
                        <i class="ri-user-settings-line" style="color: #000;"></i>
                        Account Settings
                    </div>
                    <div class="section-items">
                        <div class="sidebar-item active">
                            <i class="ri-user-line" style="color: #000;"></i>
                            My Profile
                        </div>
                        <div class="sidebar-item">
                            <i class="ri-map-pin-line" style="color: #000;"></i>
                            Address Book
                        </div>
                        <div class="sidebar-item">
                            <i class="ri-bank-card-line" style="color: #000;"></i>
                            My Payment
                        </div>
                        <div class="sidebar-item">
                            <i class="ri-lock-password-line" style="color: #000;"></i>
                            Change Password
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-container">
                <?php include 'profile.php'; ?>
            </div>
        </div>

        </main>
        
        <script>
        </script>
    </body>
    
</html>