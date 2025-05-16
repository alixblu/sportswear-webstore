import { 
    createProduct,
    uploadProductImageRequest,
} from './api.js';
import {
    DEFAULT_IMG,
} from './config.js'
import {
    brandsList,
    categoriesList,
    discountsList,
    closeModal
} from './product.js'

// MỞ modal thêm sp
export function generateModal(){
    // Chèn ảnh mặc định
    const imgField = document.getElementById('modal-product-image')
    if(!imgField)
        throw new Error("Không tìm thấy field chèn ảnh !!!");
    imgField.src = `${DEFAULT_IMG}`
    // Mở nút thêm ảnh
    document.getElementById('img-btn').style.display = 'block'
    
    // Tải select

    // Load categories
    const categorySelect = document.getElementById('category');
        categoriesList.forEach(category => {
            const option = document.createElement('option');
            option.value = category.ID;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    // Load brands
    const brandSelect = document.getElementById('brand');
        brandsList.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.ID;
            option.textContent = brand.name;
            brandSelect.appendChild(option);
        });
    // Load discounts 
    const discountSelect = document.getElementById('discount')
        discountsList.forEach(discount => {
            const option = document.createElement('option')
            option.value = discount.ID
            option.textContent = discount.discountRate + '%' 
            discountSelect.appendChild(option)
            })
}

// Xử lý thêm sp
const processCreating = async (event) => {
    // Ngăn reload trang
    event.preventDefault();
    const form = document.getElementById('createForm');

    const formData = {
        name: form.name.value,
        categoryID: form.category.value,
        brandID: form.brand.value,
        discountID: form.discount.value == "" ? null : form.discount.value,
        description: form.description.value,
        markup_percentage: form.markup.value,
        image: document.getElementById('modal-product-image').getAttribute('data-newname') || null,
    };
    if(formData.image == null){
        alert("Vui lòng chọn ảnh cho sản phẩm !!!");
        return;
    }
    console.log(formData.image);
    
    try{
        const result = await createProduct(formData)
        const productID = result?.product_id;
        if(!productID)
            throw new Error("Không nhận được id sp mới tạo !!!!");
        // Thêm ảnh
        const imgInput = document.getElementById('changeImageInput');
        const imgFile = imgInput.files[0];
        
        if(document.getElementById('modal-product-image').getAttribute('data-newname') !== ''){
            const formData = new FormData()
            formData.append('action', 'uploadProductImage')
            formData.append('product_id', productID)
            formData.append('image', imgFile)
            await uploadProductImageRequest(formData)
        }
        closeModal();
    } catch (error) {
        console.error('Thêm sản phẩm thất bại!');
        throw new Error("Không thể thêm sản phẩm", error);
    }
}

// Reset lại form
export function clearCreateModal(){
    // Xóa ảnh
    const imgField = document.getElementById('modal-product-image')
    imgField.setAttribute('src', `${DEFAULT_IMG}`)
    imgField.setAttribute('data-newname', '')
    imgField.setAttribute('data-oldname', '')
    // Reset lại form
    const form = document.getElementById('createForm');
    form.reset(); 
} 
window.clearCreateModal = clearCreateModal

// Xử lý submit form thêm
document.addEventListener('submit', function(event) {
    if (event.target && event.target.id === 'createForm') {
        event.preventDefault();
        processCreating(event);
    }
});