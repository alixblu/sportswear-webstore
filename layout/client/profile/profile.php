<?php

// Fetch user data from the session
$user = $_SESSION['user']; 
?>
<html>
<body>
<div class="profile-form">
        <div class="form-title">Edit Your Profile</div>

        <div class="form-row">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input id="fullname" name="fullname" type="text" value="">
            </div>
            <div class="form-group">
                <label for="dateOfBirth">Date of Birth</label>
                <input id="dateOfBirth" name="dateOfBirth" type="date" value="">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="" disabled>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="tel" value="">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="createdAt">Member Since</label>
                <input type="text" id="createdAt" value="" disabled>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="save-btn">Save Changes</button>
        </div>
    </div>
</body>
    <script src="../../JS/admin/userApi.js"></script>
    <script>
        // Load user data when page loads
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                console.log('Fetching user data...');
                // Get user info using the API
                const userData = await getInfo();
                console.log('Received user data:', userData);

                if (userData) {
                    // Populate form fields with user data
                    const fullnameInput = document.getElementById('fullname');
                    const dateOfBirthInput = document.getElementById('dateOfBirth');
                    const emailInput = document.getElementById('email');
                    const phoneInput = document.getElementById('phone');
                    const genderSelect = document.getElementById('gender');
                    const createdAtInput = document.getElementById('createdAt');

                    console.log('Setting form values...');
                    
                    // Set values with null checks
                    fullnameInput.value = userData.name || '';
                    dateOfBirthInput.value = userData.birthday || '';
                    emailInput.value = userData.email || '';
                    phoneInput.value = userData.phone || '';
                    genderSelect.value = userData.gender || 'male';
                    createdAtInput.value = userData.createdAt || '';

                    console.log('Form values set:', {
                        name: fullnameInput.value,
                        birthday: dateOfBirthInput.value,
                        email: emailInput.value,
                        phone: phoneInput.value,
                        gender: genderSelect.value,
                        createdAt: createdAtInput.value
                    });
                } else {
                    console.error('No user data received');
                }
            } catch (error) {
                console.error('Error loading user data:', error);
            }
        });

        // Handle form submission
        document.querySelector('.profile-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('fullname').value,
                address: '', // Add address field if needed
                newPassword: '' // Add password field if needed
            };

            try {
                const result = await updateUserLogin(
                    formData.name,
                    formData.address,
                    formData.newPassword
                );
                
                if (result) {
                    alert('Profile updated successfully!');
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                alert('Failed to update profile. Please try again.');
            }
        });
    </script>
</html>