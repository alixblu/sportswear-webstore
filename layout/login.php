<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div style=" display: flex; flex-direction: column; min-height: 100vh;">
        <?php include './layout/header.php'?>
        <div style="flex:1;">
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
                    width: 40%;
                ";


                $loginLinkCss = "
                    text-decoration: none;
                    color: red;
                ";

                $formFooter = "
                    display: flex;
                    gap:50px;
                    align-items: center;
                ";
            ?>

            <div style="<?= $wrapperCss; ?>">
                <img src="images/side-image.svg" alt="side-image" style="<?= $imgCss; ?>">
                <div style="<?= $rightSectionCss; ?>">
                    <div style="<?= $formTitle; ?>" >Log in to Exclusive</div>
                    <div>Enter your details below</div>
                    <form id="loginForm" style="<?= $formCss; ?>" autocomplete="off">
                        <input type="text" id="username" name="username" placeholder="UserName" style="<?= $inputCss; ?>" required>
                        <input type="password" id="password" name="password" placeholder="Password" style="<?= $inputCss; ?>" required>
                        <div style="<?= $formFooter ; ?>">
                            <input type="submit" value="Login" style="<?= $buttonCss; ?>" required>
                            <a style="<?= $loginLinkCss; ?>">Forget Password?</a>
                        </div>
                        <div id="error-message" style="color: red;"></div>
                    </form>
                </div>
            </div>
        </div>
        <?php include './layout/footer.php'?>
    </div>
    <script>
        document.getElementById("loginForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            fetch('./login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: username,
                    password: password,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status==400) {
                    document.getElementById('error-message').innerText = data.data;
                } else {
                    window.location.href = '/';
                }
            })
            .catch(error => {
                document.getElementById('error-message').innerText = "Có lỗi xảy ra, vui lòng thử lại.";
            });
        });
    </script>
</body>
</html>