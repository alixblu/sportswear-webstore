const ACCOUNT_API_URL = '../../src/router/accountRouter.php';

const getAllAccounts = async () => {
    try {
        const response = await fetch(`${ACCOUNT_API_URL}?action=getAllAccounts`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy danh sách tài khoản');

        const data = await response.json();
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in getAllAccounts:', error);
        throw error;
    }
};

const getAccountById = async (accountId) => {
    try {
        const response = await fetch(`${ACCOUNT_API_URL}?action=getAccountById&accountId=${accountId}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy thông tin tài khoản');

        return await response.json();
    } catch (error) {
        console.error('Error in getAccountById:', error);
        throw error;
    }
};

const createAccount = async (username, password, fullname, phone, roleId, status, email = null, address = null, gender = null, dateOfBirth = null) => {
    try {
        const response = await fetch(ACCOUNT_API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'createAccount',
                username,
                password,
                fullname,
                phone,
                roleId,
                status,
                email,
                address,
                gender,
                dateOfBirth
            }),
        });

        if (!response.ok) throw new Error('Không thể tạo tài khoản');

        return await response.json();
    } catch (error) {
        console.error('Error in createAccount:', error);
        throw error;
    }
};

const updateAccount = async (accountId, username, password, fullname, phone, roleId, status, email = null, address = null, gender = null, dateOfBirth = null) => {
    try {
        const response = await fetch(ACCOUNT_API_URL, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'updateAccount',
                accountId,
                username,
                password,
                fullname,
                phone,
                roleId,
                status,
                email,
                address,
                gender,
                dateOfBirth
            }),
        });

        if (!response.ok) throw new Error('Không thể cập nhật tài khoản');

        return await response.json();
    } catch (error) {
        console.error('Error in updateAccount:', error);
        throw error;
    }
};

const deleteAccount = async (accountId) => {
    try {
        const response = await fetch(`${ACCOUNT_API_URL}?action=deleteAccount&accountId=${accountId}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể xóa tài khoản');

        return await response.json();
    } catch (error) {
        console.error('Error in deleteAccount:', error);
        throw error;
    }
};

const filterAccounts = async (filters) => {
    try {
        const response = await fetch(ACCOUNT_API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'filterAccounts',
                ...filters
            }),
        });

        if (!response.ok) throw new Error('Không thể lọc tài khoản');

        const data = await response.json();
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in filterAccounts:', error);
        throw error;
    }
};

const getPermissions = async (roleId) => {
    try {
        const response = await fetch(`${ACCOUNT_API_URL}?action=getPermissions&roleId=${roleId}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy quyền hạn');

        return await response.json();
    } catch (error) {
        console.error('Error in getPermissions:', error);
        throw error;
    }
};

const getAllModules = async () => {
    try {
        const response = await fetch(`${ACCOUNT_API_URL}?action=getAllModules`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy danh sách module');

        const data = await response.json();
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in getAllModules:', error);
        throw error;
    }
};

const getAllRoles = async () => {
    try {
        const response = await fetch(`${ACCOUNT_API_URL}?action=getAllRoles`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy danh sách vai trò');

        const data = await response.json();
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in getAllRoles:', error);
        throw error;
    }
};