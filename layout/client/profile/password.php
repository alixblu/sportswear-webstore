<?php

$user = $_SESSION['user']; 
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <title>Client</title>


    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/password.css">
    
</head>

<body>
<div class="change-password-container">
    <h2>Change Password</h2>

    <form action="change_password.php" method="POST" class="change-password-form">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" required>
        </div>

        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">Update Password</button>
        </div>
    </form>
</div>


</body>
    <script src="../../JS/admin/userApi.js"></script>
    <script>
        document.querySelector('.change-password-form').addEventListener('submit', async (e) => {
            e.preventDefault(); 

            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                alert('New password and confirmation do not match!');
                return;
            }

            try {
                const result = await updateUserPassword(currentPassword, newPassword);

                if (result.status === 200) {
                    alert('Password updated successfully!');
                } else {
                    alert(result.data || 'Failed to update password.');
                }
            } catch (error) {
                console.error(error);
                alert('Error: Unable to update password. Please try again.');
            }

        });

    </script>
</html>