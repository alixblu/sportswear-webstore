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
                <!-- pages go here -->
                <?php include 'profile.php'; ?>
            </div>
        </div>

        </main>
        
        <script>
        // Load user data when page loads
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                console.log('Fetching user data...');
                const result = await getInfo();
                console.log('API Response:', result);

                if (result && result.data) {
                    const userData = result.data;
                    console.log('User data:', userData);

                    // Get form elements
                    const elements = {
                        fullname: document.getElementById('fullname'),
                        dateOfBirth: document.getElementById('dateOfBirth'),
                        email: document.getElementById('email'),
                        phone: document.getElementById('phone'),
                        gender: document.getElementById('gender'),
                        createdAt: document.getElementById('createdAt')
                    };

                    // Log found elements
                    console.log('Form elements:', elements);

                    // Set values
                    if (elements.fullname) elements.fullname.value = userData.fullname || '';
                    if (elements.dateOfBirth) elements.dateOfBirth.value = userData.dateOfBirth || '';
                    if (elements.email) elements.email.value = userData.email || '';
                    if (elements.phone) elements.phone.value = userData.phone || '';
                    if (elements.gender) {
                        elements.gender.value = userData.gender === 0 ? 'male' : userData.gender === 1 ? 'female' : 'other';
                    }
                    if (elements.createdAt) elements.createdAt.value = userData.createdAt || '';

                    console.log('Form values set successfully');
                } else {
                    console.error('No user data in response');
                }
            } catch (error) {
                console.error('Error loading user data:', error);
            }
        });

        function loadMyProfile() {
            const pageContainer = document.querySelector('.profile-container');
            fetch('profile.php')
               .then(response => response.text())
               .then(data => {
                     pageContainer.innerHTML = data;
                     // Reload user data after profile content is loaded
                     document.dispatchEvent(new Event('DOMContentLoaded'));
               })
               .catch(error => console.error('Error loading profile:', error));
        }

        // Handle form submission
        document.addEventListener('click', async (e) => {
            if (e.target && e.target.classList.contains('save-btn')) {
                e.preventDefault();
                
                const fullname = document.getElementById('fullname').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const gender = document.getElementById('gender').value;
                const dateOfBirth = document.getElementById('dateOfBirth').value;

                if (!fullname || !phone) {
                    alert("Please fill in all required fields.");
                    return;
                }

                try {
                    const response = await updateUserLogin(
                        fullname,
                        null, // address not in form
                        null, // password not in form
                        phone,
                        gender === 'male' ? 0 : gender === 'female' ? 1 : 2,
                        dateOfBirth
                    );

                    if (response && response.status === 200) {
                        alert("Profile updated successfully!");
                    } else {
                        alert("Failed to update profile.");
                    }
                } catch (err) {
                    console.error("Error updating profile:", err);
                    alert("An error occurred, please try again later.");
                }
            }
        });
        </script>
    </body>
    
</html>