const API_URL = '../../src/router/userRouter.php';
const API_ACCOUNT_URL = '/sportswear-webstore/src/router/accountrouter.php';

const getAccountByUserId = async (userId) => {
    const response = await fetch(`${API_URL}?action=getAccountByUserId&userId=${userId}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy thông tin tài khoản');
    }

    return await response.json();
};

const getAllUsers = async () => {
    const response = await fetch(`${API_URL}?action=getAllUsers`, {
        method: 'GET',
        headers: {
           'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Không thể lấy danh sách người dùng');
    }

    return await response.json();
};

const createDefaultAccount = async (name, birthday,address,email, phone, gender, roleID) => {
    const formData = new URLSearchParams();
    formData.append('action', 'defaultAccount');
    formData.append('name', name);
    formData.append('birthday', birthday);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('gender', gender);
    formData.append('roleID', roleID);
    formData.append('address', address);

    const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể tạo tài khoản');
    }

    return await response.json();
};

const deleteUserApi = async (userId) => {
    const response = await fetch(`${API_URL}?action=deleteUsers&userId=${userId}`, {
        method: 'DELETE',
    });

    if (!response.ok) {
        throw new Error('Không thể xoá người dùng');
    }

    return await response.json();
};

const updateUser = async (id, name,address, email, passWord, phone, gender, roleID) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateUsers');
    formData.append('userId', id);
    formData.append('name', name);
    formData.append('address', address);
    formData.append('email', email);
    formData.append('passWord', passWord);
    formData.append('phone', phone);
    formData.append('gender', gender);
    formData.append('roleID', roleID);

    const response = await fetch(API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể cập nhật người dùng');
    }

    return await response.json();
};
const getAllRoles = async () => {
    const response = await fetch(`${API_URL}?action=getAllRoles`, {
        method: 'GET',
        headers: {
           'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Không thể lấy danh sách Roles');
    }

    return await response.json();
};

const uploadFileUser = async (file) => {
    try {
        const formData = new FormData();
        formData.append("excel_file", file);
        const response = await fetch(`${API_URL}?action=uploadFile`, {
            method: "POST",
            body: formData
        });

        return await response.json();
    } catch (err) {
        console.error("Lỗi:", err);
    }
};

const exportFileUser = async (file) => {
    try {
        const response = await fetch(`${API_URL}?action=exportFile`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json',
            },
        });
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob); 
            const a = document.createElement("a"); 
            a.style.display = "none";
            a.href = url;
            a.download = "user_list.xlsx"; 
            document.body.appendChild(a);
            a.click(); 
            window.URL.revokeObjectURL(url); 
        } else {
            const errorMessage = await response.text();
            alert("Lỗi khi tải file: " + errorMessage);
        }
    } catch (err) {
        console.error("Lỗi:", err);
    }
};

const searchUsers = async (keyword, fields) => {
    try {
        let query = `?action=search&keyword=${encodeURIComponent(keyword)}`;
        fields.forEach(field => {
            query += `&fields[]=${encodeURIComponent(field)}`;
        });

        const response = await fetch(`${API_URL}${query}`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (response.ok) {
            const data = await response.json();
            console.log("Kết quả tìm kiếm:", data);
            return data;
        } else {
            const errorMessage = await response.text();
            alert("Lỗi khi tìm kiếm: " + errorMessage);
        }
    } catch (err) {
        console.error("Lỗi:", err);
    }
};

const getInfo = async () => {
    try {
        const response = await fetch(`${API_ACCOUNT_URL}?action=info`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (response.ok) {
            const data = await response.json();
            return data;
        } else {
            const errorMessage = await response.text();
            alert("Lỗi khi lấy thông tin: " + errorMessage);
        }
    } catch (err) {
        console.error("Lỗi:", err);
    }
};

const updateUserLogin = async (name, address,birth,phone,gender) => {
    try {
        const response = await fetch(`${API_ACCOUNT_URL}`, {
            method: "PUT",
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: "updateUserLogin",
                name: name,
                address: address,
                birth: birth,
                phone:phone,
                gender:gender
            }),
        });

        if (response.ok) {
            const data = await response.json();
            return data;
        } else {
            const errorMessage = await response.text();
            alert("Lỗi khi cập nhật: " + errorMessage);
        }
    } catch (err) {
        console.error("Lỗi:", err);
    }
};

const updateUserPassword = async (passwordOld,passWord) => {
    try {
        const response = await fetch(`${API_ACCOUNT_URL}`, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: "updatePassword",
                passwordOld: passwordOld,
                newPassword: passWord,
            }),
        });

        if (response.ok) {
            const data = await response.json();
            return data;
        } else {
            const errorMessage = await response.text();
            alert("Lỗi khi cập nhật: " + errorMessage);
        }
    } catch (err) {
        console.error("Lỗi:", err);
    }
};