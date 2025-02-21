<?php
    $logoCss = "
    font-family: 'Inter', sans-serif; 
    font-size: 24px; 
    font-weight: bold; 
    color: #000;
    ";

    $menuStyle = "
    font-family: 'Inter', sans-serif; 
    font-size: 16px; 
    font-weight: normal; 
    color: #000; 
    display: flex; 
    gap: 50px;
    ";

    $wrapperCss = "
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 15px 100px; 
    border-bottom: 1px solid #ddd;
    ";

    $rightSectionCss = "
        display: flex; 
        align-items: center; 
        gap: 20px;
    ";

    $searchBoxCss = "
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f5f5f5;
        padding: 10px 15px;
        border-radius: 8px;
    ";

    $iconCss = "
    width: 24px;
    height: 24px;
    cursor: pointer;
    ";

    $inputCss = "
        border: none; 
        background: transparent; 
        outline: none;
    ";
?>

<header style="<?= $wrapperCss; ?>">
    <div style="<?= $logoCss; ?>">
        Exclusive
    </div>
    
    <nav style="<?= $menuStyle; ?>">
        <div>Home</div>
        <div>Contact</div>
        <div>About</div>
        <div>Sign Up</div>
    </nav>

    <div style="<?= $rightSectionCss; ?>">
        <div style="<?= $searchBoxCss; ?>">
            <input type="text" placeholder="What are you looking for?" style="<?= $inputCss?>">
            <img src="images/search-icon.svg" alt="Search" style="<?= $iconCss; ?>">
        </div>
        <img src="images/cart-icon.svg" alt="Cart" style="<?= $iconCss; ?>">
        <img src="images/user-icon.svg" alt="User" style="<?= $iconCss; ?>">
    </div>
</header>
