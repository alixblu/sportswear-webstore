<?php

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
    <script src="/sportswear-webstore/JS/admin/userApi.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const result = await getInfo();
                if (result && result.data) {
                    const userData = result.data;

                    const elements = {
                        fullname: document.getElementById('fullname'),
                        dateOfBirth: document.getElementById('dateOfBirth'),
                        email: document.getElementById('email'),
                        phone: document.getElementById('phone'),
                        gender: document.getElementById('gender'),
                        createdAt: document.getElementById('createdAt')
                    };

                    if (elements.fullname) elements.fullname.value = userData.fullname || '';
                    if (elements.dateOfBirth) elements.dateOfBirth.value = userData.dateOfBirth || '';
                    if (elements.email) elements.email.value = userData.email || '';
                    if (elements.phone) elements.phone.value = userData.phone || '';
                    if (elements.gender) {
                        elements.gender.value = userData.gender === 0 ? 'male' : userData.gender === 1 ? 'female' : 'other';
                    }
                    if (elements.createdAt) elements.createdAt.value = userData.createdAt || '';

                } else {
                }
            } catch (error) {
            }
        });

        document.querySelector('.form-actions').addEventListener('click', async (e) => {
            e.preventDefault();
            const formData = {
                name: document.getElementById('fullname').value,
                address:null,
                birth: document.getElementById('dateOfBirth').value,
                phone: document.getElementById('phone').value,
                gender: document.getElementById('gender').value,
            };

            try {
                const result = await updateUserLogin(
                    formData.name,
                    formData.address,
                    formData.birth,
                    formData.phone,
                    formData.gender === 'male' ? 0 : formData.gender === 'female' ? 1 : 2,
                );
                
                if (result) {
                    alert('Profile updated successfully!');
                }
            } catch (error) {
                alert('Failed to update profile. Please try again.');
            }
        });
    </script>
</html>