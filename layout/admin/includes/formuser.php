<!-- FormUser -->
<head>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
</head>
<style>
    .actionUser {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .formUserCss{
        background-color:white;
        max-width: 500;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .birthdayGenderCss{
        display: flex;
        gap: 100px;
    }
    .wrapperCss{
        padding: 0 30px;
        padding-bottom: 30px;
        display: flex;
        flex-direction: column;
        gap:10px
    }
    .genderCss{
        display: flex;
        margin: 10px 0px;
    }
    .inputUserCss{
        border: none;
        outline: none;
        color: #2d3748;
        font-size: 17px;
    }
    body{
        background-color:black;
    }
    .wrapperInputCss{
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.1);
        transition: border-bottom 0.3s ease;
        border-bottom: 1px solid silver;
        padding: 5px 3px;
    }
    .wrapperInputCss:focus-within {
        border-bottom: 1px solid #00e5ff;
    }
    .wrapperBirthday{
        margin: 10px 0px;
    }
    .selectUser {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d1d1;
        border-radius: 6px;
        outline: none;
    }
    .buttonUserCss {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }

    .buttonUserCss:hover {
        background-color: #45a049;
    }
    .wrapperButton{
        display: flex;
        gap: 10px
    }
    .CloseCss{
        display: flex;
        justify-content: flex-end;
        padding: 10px;
    }
</style>
<body>
    <div class="formUserCss">
        <div class="CloseCss"><i class="fa-solid fa-xmark"></i></div>
        <div class="wrapperCss">
            <label for="name">Họ và tên</label>
            <div class="wrapperInputCss">
                <input class="inputUserCss" type="text" id="name">
            </div>
            
            <label for="email">Email</label>

            <div class="wrapperInputCss">
                <input class="inputUserCss" type="text" id="email">
            </div>

            
            <label for="phone">Số điện thoại</label>
            
            <div class="wrapperInputCss">
                <input type="text" class="inputUserCss" id="phone">
            </div>
            <div class="birthdayGenderCss">
                <div >
                    <label for="birthday">Ngày Sinh</label><br>
                    <input class="wrapperBirthday" type="date" id="birthday">     
                </div>
                <div class="wrapperGender">
                    <label for="" >Giới Tính</label>
                    <div class="genderCss">
                        <input type="radio" id="male" name="gender">
                        <label for="male">Nam</label><br>
                        <input type="radio" id="female" name="gender">
                        <label for="female" >Nữ</label><br>
                    </div>
                </div>
            </div>
            <label for="role">Vai Trò:</label>

            <select class="selectUser" type="role" id="role">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <div>
                <p>*Tài khoản nhân viên được thêm tự động,Tên Tài khoản là số điện thoại, mật khẩu: 123456</p>
            </div>
            <div class="wrapperButton">
                <input class="buttonUserCss" type="submit" value="Thêm">
            </div>
        </div>
    </div>

</body>