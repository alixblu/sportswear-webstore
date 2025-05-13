const API_URL = '/sportswear-webstore/src/router/productRouter.php';

// ===================================== GET products ===================================== 
const getAllProducts = async () => {
    try {
        const response = await fetch(`${API_URL}?action=getAllProducts`, {
            method: 'GET',
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Không thể lấy danh sách sản phẩm');
        }

        const data = await response.json();
        // Check if data is an array or has a data property
        if (Array.isArray(data)) {
            return data;
        } else if (data && Array.isArray(data.data)) {
            return data.data;
        } else {
            throw new Error('Invalid response format from server');
        }
    } catch (error) {
        console.error('Error in getAllProducts:', error);
        throw error;
    }
};

const getFilteredProductsAdmin = async (page=1, productsPerPage=1, category=null, brand=null, status=null, rating=null, search = null) => {
    try {
        let url = `${API_URL}?action=getFilteredProductsAdmin`
        if (page) url += `&page=${page}`
        if (productsPerPage) url += `&productsPerPage=${productsPerPage}`
        if (search) url += `&search=${search}`
        if (category) url += `&category=${category}`
        if (brand) url += `&brand=${brand}`
        if (status) url += `&status=${status}`
        if (rating) url += `&rating=${rating}`
        const response = await fetch(url,
            {
                method: 'GET',
                mode: 'cors',
                headers: {
                    'Content-Type':'application/json'
                }
            }
        )
        if(!response.ok)
            throw new Error("Không thể lấy sản phẩm theo tùy chọn !!!")
    
        const data = await response.json();
        return data.data;
    } catch (error) {
        console.error('Lỗi, không thể lấy sản phẩm theo tùy chọn !!!', error)
        throw error
    }
}

const getFilteredProducts = async (category=null, brand=null, status=null, min_price=null, max_price=null, $sort = 'newest', search = null) => {
    // Không có thì lấy toàn bộ
    if(search=null && category == null && brand == null && status == null  && min_price == null && max_price == null)
        return data = await getAllProducts();

    try {
        let url = `${API_URL}?action=getFilteredProducts`
        if (search) url += `&search=${search}`
        if (category) url += `&category=${category}`
        if (brand) url += `&brand=${brand}`
        if (status) url += `&status=${status}`
        if (rating) url += `&rating=${rating}`
        if (min_price) url += `&min_price=${min_price}`
        if (max_price) url += `&max_price=${max_price}`
        const response = await fetch(url,
            {
                method: 'GET',
                mode: 'cors',
                headers: {
                    'Content-Type':'application/json'
                }
            }
        )
        if(!response.ok)
            throw new Error("Không thể lấy sản phẩm theo tùy chọn !!!")
    
        const data = await response.json();
        return data.data;
    } catch (error) {
        console.error('Lỗi, không thể lấy sản phẩm theo tùy chọn !!!', error)
        throw error
    }
}

const getProductById = async (id) => {
    const response = await fetch(`${API_URL}?action=getProductById&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy thông tin sản phẩm');
    }
    const data = await response.json()
    return data.data;
};

const getProductVariants = async (id) => {
    const response = await fetch(`${API_URL}?action=getProductVariants&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy thông tin biến thể sản phẩm');
    }

    return await response.json();
};


// ===================================== Update & Delete product ===================================== 
const updateProduct = async (product) => {
    if(product == null)
        throw new Error('Không có dữ liệu sản phẩm mới')

    const response = await fetch(`${API_URL}?action=updateProduct&id=${product.ID}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(product),
    });

    if (!response.ok) {
        throw new Error('Không thể cập nhật sản phẩm');
    }

    return await response.json();
};

const updateProductStock = async (id) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateProductStock');
    formData.append('id', id);

    const response = await fetch(API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể cập nhật số lượng tồn kho');
    }

    return await response.json();
};

const deleteProduct = async (id) => {
    const response = await fetch(`${API_URL}?action=deleteProduct&id=${id}`, {
        method: 'DELETE',
    });

    if (!response.ok) {
        throw new Error('Không thể xoá sản phẩm');
    }

    return await response.json();
};

// ===================================== Create product ===================================== 
/*
const createProduct = async (data) => {
    try{
        const response = await fetch(`${API_URL}?action=createProduct`, {
            method: 'POST',
            headers:{
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })

        if(!response.ok)
            throw new Error("Không thể tạo sản phẩm")
        
        const data = await response.json()
        return data
    }catch (error) {
        console.error('Lỗi, không thể thêm sản phẩm !!!', error)
        throw error
    }
}
const createProductVariants = async (productId) => {
    const response = await fetch(`${API_URL}?action=createProductVariants&id=${productId}`, {
        method: 'POST',
        headers:{
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })

    if(!response.ok)
        throw new Error("Không thể tạo sản phẩm")
    
    const data = await response.json()
    return data
}
*/

export {
    getFilteredProducts,
    getProductById,
    getProductVariants,
    getAllProducts,
    getFilteredProductsAdmin,
    updateProduct,
    deleteProduct,
};