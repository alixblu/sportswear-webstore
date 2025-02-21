<?php
    $wrapperCss = "
        background-color: black;
        color: white;
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 50px 300px; 
    ";

    $footerColumn = "
        display: flex;
        flex-direction: column;
        gap: 20px;
        font-family: 'Inter', sans-serif;
        font-size: 16px;
        font-weight: 400;
    ";

    $footerTitle = "
        font-size: 20px;
        font-weight: 500;
        margin-bottom: 10px;
    ";

?>

<footer ">
    <div style="<?= $wrapperCss ; ?>">
        <div style="<?= $footerColumn; ?>">
            <div style="<?= $footerTitle; ?>">Support</div>
            <div>Hồ CHí Minh City</div>
            <div>shop@gmail.com</div>
            <div>+84 000000000</div>
        </div>
        <div style="<?= $footerColumn; ?>">
            <div style="<?= $footerTitle; ?>">Account</div>
            <div>My Account</div>
            <div>Login / Register</div>
            <div>Cart</div>
        </div>

        <div style="<?= $footerColumn; ?>">
            <div style="<?= $footerTitle; ?>">Quick Link</div>
            <div>Privacy Policy</div>
            <div>Terms Of Use</div>
            <div>FAQ</div>
            <div>Contact</div>
        </div>
        
    </div>
</footer>
