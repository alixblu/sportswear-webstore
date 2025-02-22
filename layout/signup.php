<?php
    $wrapperCss = "
        display: flex; 
        padding: 100px 0; 
        gap:100px;
        justify-content: center;
        align-items: center;
    ";

    $imgCss = "
        width: 605px; 
        height: 581px; 
    ";

    $rightSectionCss = "
        display: flex; 
        flex-direction: column;
        gap:15px;
        width: 350px;
    ";
    $formCss="
        display: flex; 
        flex-direction: column;
        gap:30px;
    ";

    $formTitle="
        font-family: 'Inter', sans-serif; 
        font-size: 27px; 
        font-weight: 200; 
    ";

    $inputCss = "
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    ";

    $buttonCss = "
        background-color: #d23f3f;
        color: white;
        font-size: 16px;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Inter', sans-serif; 
    ";

    $loginTextCss = "
        font-size: 14px;
    ";
    $loginLinkCss = "
        font-weight: bold;
        text-decoration: none;
        color: black;
    ";
?>

<div style="<?= $wrapperCss; ?>">
    <img src="images/side-image.svg" alt="side-image" style="<?= $imgCss; ?>">
    <div style="<?= $rightSectionCss; ?>">
        <div style="<?= $formTitle; ?>" >Create an account</div>
        <div>Enter your details below</div>
        <form style="<?= $formCss; ?>">
            <input type="text" id="fname" name="fname" placeholder="Name" style="<?= $inputCss; ?>">
            <input type="text" id="phone" name="phone" placeholder="Phone Number" style="<?= $inputCss; ?>">
            <input type="password" id="password" name="password" placeholder="Password" style="<?= $inputCss; ?>">
            
            <input type="submit" value="Create Account" style="<?= $buttonCss; ?>">
        </form>
        <div style="<?= $loginTextCss; ?>">Already have an account? <a href="/login" style="<?= $loginLinkCss; ?>">Log in</a></div>
    </div>
</div>