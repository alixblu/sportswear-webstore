<?php

// Fetch user data from the session
$user = $_SESSION['user']; 
?>
<div class="profile-form">
    <div class="form-title">Edit Your Profile</div>

    <div class="form-row">
        <div class="form-group">
            <label for="first-name">First Name</label>
            <input id="first-name" type="text" value="">
        </div>
        <div class="form-group">
            <label for="last-name">Last Name</label>
            <input id="last-name" type="text" value="">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" value="" disabled>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input id="address" type="text" value="">
        </div>
    </div>

    <div class="form-section">
        <label>Password Changes</label>
        <input type="password" placeholder="Current Password">
        <input type="password" placeholder="New Password">
        <input type="password" placeholder="Confirm New Password">
    </div>

    <div class="form-actions">
        <button class="save-btn">Save Changes</button>
    </div>
</div>