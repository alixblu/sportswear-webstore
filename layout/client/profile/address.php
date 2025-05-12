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
    <link rel="stylesheet" href="./css/address.css">
    
</head>

<body>
<div class="edit-address-container">
        <h2>Edit Address Book</h2>

        <form action="save_address.php" method="POST" class="edit-address-form">
       
            <div class="form-group">
                <label for="address_line">Address</label>
                <input type="text" name="address_line" id="address_line" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-save">Save</button>
            </div>
        </form>
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
                        address: document.getElementById('address_line'),
                    };

                    if (elements.address) elements.address.value = userData.address || '';
                } else {
                }
            } catch (error) {
            }
        });

        document.querySelector('.edit-address-container').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                address:document.getElementById('address_line').value
            };

            try {
                const result = await updateUserLogin(
                    null,
                    formData.address,
                    null,
                    null,
                    null                
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