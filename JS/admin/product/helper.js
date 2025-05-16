import {
    cancelEdit,
} from './detail.js'

export const resetCreateForm = () => {
    document.getElementById('name').value = '';
    document.getElementById('markup').value = null;
    document.getElementById('category').value = '';
    document.getElementById('brand').value = '';
    document.getElementById('discount').value = '';
    document.getElementById('description').value = '';
}

export function switchEditing() {
   const fields = [
        document.getElementById('modal-product-name'),
        document.getElementById('modal-product-category'),
        document.getElementById('modal-product-brand'),
        document.getElementById('modal-product-discount-id'),
        document.getElementById('modal-product-description'),
    ];

    // Kiểm tra các field có tồn tại không
    if (fields.some(field => !field)) {
        throw new Error("Không tìm thấy một hoặc nhiều fields trong detail!");
    }

    // Toggle disabled cho tất cả
    fields.forEach(field => {
        field.disabled = !field.disabled;
    });

    // Optional: Đổi nút từ "Chỉnh sửa" thành "Lưu" hoặc ngược lại
    const editBtn = document.querySelector('.open-edit-form');
    if (editBtn) {
        editBtn.innerHTML = ''
        if(fields[0].disabled){
            cancelEdit()
            editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit'
            document.querySelector('.delete-product-btn').style.display = 'block'
            document.getElementById('submit-update-btn').style.display = 'none'
            document.getElementById('img-btn').style.display = 'none'
        }
        else{
            editBtn.innerHTML = '<i class="fas fa-edit"></i> Cancel Edit'
            document.querySelector('.delete-product-btn').style.display = 'none'
            document.getElementById('submit-update-btn').style.display = 'block'
            document.getElementById('img-btn').style.display = 'block'
        }

    }   
}
window.switchEditing = switchEditing
// Function to render stars based on rating
export function renderStars(rating) {
    if (!rating) 
        return '<div class="stars"><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></div>';

    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);

    let starsHTML = '<div class="stars">';

    // Add full stars
    for (let i = 0; i < fullStars; i++) {
        starsHTML += '<i class="fas fa-star"></i>';
    }

    // Add half star if needed
    if (halfStar) {
        starsHTML += '<i class="fas fa-star-half-alt"></i>';
    }

    // Add empty stars
    for (let i = 0; i < emptyStars; i++) {
        starsHTML += '<i class="far fa-star"></i>';
    }

    starsHTML += '</div>';
    return starsHTML;
}