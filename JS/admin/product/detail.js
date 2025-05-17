import {
    getProductVariants,
    updateProduct,
    getProductById,
    deleteProduct,
    uploadProductImageRequest,
} from './api.js'
import {
    IMG_URL,
} from './config.js'
import {
    brandsList,
    categoriesList,
    discountsList,
    loadProducts,
    closeModal,
} from './product.js'
import {
    renderStars,
    switchEditing
} from './helper.js'
let currentProduct = null

// ================================== View detail of product modal ==================================
// Fill detail fields of product
const fillDetailModal = (product) => {
    console.log(product);
    
    if(brandsList == [] || categoriesList == [] || discountsList == []){
        console.error("Không tìm thấy danh sách hãng / discount / loại sp");
        return
    }
    if(product.status == "discontinued"){
        document.querySelector('.restore-product-btn').style.display = 'block'
        document.querySelector('.delete-product-btn').style.display = 'none'
    }
    else{
        document.querySelector('.restore-product-btn').style.display = 'none'
        document.querySelector('.delete-product-btn').style.display = 'block'
    }
    const modalElements = {
            id: document.getElementById('modal-product-id'),
            name: document.getElementById('modal-product-name'),
            markup: document.getElementById('modal-product-markup'),
            rating: document.getElementById('modal-product-rating'),
            stock: document.getElementById('modal-product-stock'),
            status: document.getElementById('modal-product-status'),
            description: document.getElementById('modal-product-description'),
            basePrice: document.getElementById('modal-product-base-price'),
            image: document.getElementById('modal-product-image'),
        };
    // Format price text
    const fomartedPrice = new Intl.NumberFormat('vi-VN').format(product.basePrice)
    // Update modal with product details
    modalElements.id.value = product.ID || '-';
    modalElements.name.value = product.name || '-';
    modalElements.markup.value = (product.markup_percentage || '0');
    modalElements.rating.innerHTML = renderStars(product.rating);
    modalElements.stock.value = product.stock || '0';
    modalElements.status.value = product.status === 'in_stock' ? 'In Stock' : 
                                         product.status === 'out_of_stock' ? 'Out of Stock' :
                                         product.status === 'discontinued' ? 'Discontinued' : '';
    modalElements.description.value = product.description || 'No description available';
    modalElements.basePrice.value = fomartedPrice || '-';
    // Assign the image source
    modalElements.image.src = `${IMG_URL}/product${product.ID}/${product.image}`;
    modalElements.image.setAttribute('data-oldname', modalElements.image.src)

    // Load categories
    const categorySelect = document.getElementById('modal-product-category');
        categoriesList.forEach(category => {
            const option = document.createElement('option');
            option.value = category.ID;
            option.textContent = category.name;
            option.selected = category.ID === product.categoryID;
            categorySelect.appendChild(option);
        });
    // Load brands
    const brandSelect = document.getElementById('modal-product-brand');
        brandsList.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.ID;
            option.textContent = brand.name;
            option.selected = brand.ID === product.brandID;
            brandSelect.appendChild(option);
        });
    // Load discounts 
    const discountSelect = document.getElementById('modal-product-discount-id')
        discountsList.forEach(discount => {
            const option = document.createElement('option')
            option.value = discount.ID
            option.textContent = discount.discountRate + '%' 
            option.selected = discount.ID === product.discountID
            discountSelect.appendChild(option)
        })
}
const fillVariantsTable = (data) => {
    const variantsList = document.getElementById('modal-variants-list');
    if(!variantsList){
        console.error("Không tìm thấy bảng variants để tải dữ liệu !!!");
        return;
    }
    if( data && data.length == 0){
        variantsList.innerHTML = '<tr><td colspan="8" class="text-center">No variants found</td></tr>';
        return;
    }
    variantsList.innerHTML = '';
    data.forEach(variant => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${variant.Code || '-'}</td>
            <td>${variant.fullName || '-'}</td>
            <td>${variant.color || '-'}</td>
            <td>${variant.size || '-'}</td>
            <td>${variant.quantity || '0'}</td>
            <td>${variant.price || '0'}</td>
            <td>${variant.status ? 
                (variant.status == "out_of_stock" 
                    ? '<button class="btn btn-danger" disabled style="border-radius: 20px;">OOS</button>' 
                    : '<button class="btn btn-primary" disabled style="border-radius: 20px;">IS</button>') : '-'}</td>
        `;
        variantsList.appendChild(row);
    });
}
// Open modal to view detail
export const viewProduct = async (id) => {
    if(!id)
        throw new Error("Chosen product is null !!!")

    // Add display for modal
    const modal = document.getElementById('productModal');
    modal.style.display = 'block';
    document.getElementById('product-info-section').style.display = 'block';
    
    // Add header
    document.getElementById('modal-title').innerText = "Product Details"
    try {
        // Get product details
        const product = await getProductById(id);
        currentProduct = product
        if(!product)
            throw new Error("Cannot get product to view detail !!!");
        fillDetailModal(product)
        // Get and display variants
        const res = await getProductVariants(product.ID);
        const variants = res.data || [];
        fillVariantsTable(variants)
    } catch (error) {
        console.error('Error loading product details:', error);
        alert('Error loading product details: ' + error.message);
    }
}

// Process updating product
const proccessUpdating = async () => {
    if(!currentProduct)
        throw new Error('Cannot get chosen product !!!')
    try{
        const formData = {
            ID: currentProduct.ID,
            categoryID: Number(document.getElementById('modal-product-category').value),
            discountID: Number(document.getElementById('modal-product-discount-id').value) || null,
            brandID: Number(document.getElementById('modal-product-brand').value),
            name: document.getElementById('modal-product-name').value,
            markup_percentage: Number(document.getElementById('modal-product-markup').value),
            rating: currentProduct.rating,
            description: document.getElementById('modal-product-description').value,
            stock: currentProduct.stock,
            status: currentProduct.status,
            // get image
            image: null,
        }
        const isChanged = Object.keys(formData).some(key => formData[key] !== currentProduct[key])
        
        if(!isChanged){
            console.error('Product is not changed !!!');
            return;
        }
        // Proccess image
        const img = document.getElementById('modal-product-image');
        const imgInput = document.getElementById('changeImageInput');
        if(img.getAttribute('data-newname') !== ''){
            await uploadProductImage(currentProduct.ID, imgInput.files[0])
            formData.image = img.getAttribute('data-newname')
        }
        else
            formData.image = img.getAttribute('data-oldname').split('/').pop()

        // Update product into db
        const response = await updateProduct(formData);
        if (response) {
            alert('Product updated successfully!');
            await loadProducts(); // Refresh grid
            await viewProduct(formData.ID); // Refresh view
            switchEditing()
            cancelEdit(); // Close edit form
        } else {
            throw new Error(response.message || 'Failed to update product');
        }

    } catch (error){
        console.error('Error proccessing updating product: ', error);
        throw new Error("Cannot update product")
    }
}
window.proccessUpdating = proccessUpdating

document.getElementById('submit-update-btn').addEventListener('click', async () => {
    if(currentProduct)
        await proccessUpdating();
})

//Upload ảnh sp
async function uploadProductImage(productID, imageFile) {
    const formData = new FormData()
    formData.append('action', 'uploadProductImage')
    formData.append('product_id', productID)
    formData.append('image', imageFile)
    await uploadProductImageRequest(formData)
}

// Hủy edit
export function cancelEdit() {
    if(!currentProduct)
        throw new Error("Không tồn tại sản phẩm đang thao tác !!!")
    
    const currentData = {
        categoryID: Number(document.getElementById('modal-product-category').value),
        discountID: Number(document.getElementById('modal-product-discount-id').value) || null,
        brandID: Number(document.getElementById('modal-product-brand').value),
        name: document.getElementById('modal-product-name').value,
        markup_percentage: Number(document.getElementById('modal-product-markup').value),
        description: document.getElementById('modal-product-description').value,
    }
    // Ktra có thay đổi không
    const check_changes =  Object.keys(currentData).some(key => currentData[key] !== currentProduct[key])
    fillDetailModal(currentProduct)
}

export function clearDetailModal() {
    // Gán giá trị rỗng
    const modalElements = [
        document.getElementById('modal-product-id'),
        document.getElementById('modal-product-name'),
        document.getElementById('modal-product-category'),
        document.getElementById('modal-product-brand'),
        document.getElementById('modal-product-discount-id'),
        document.getElementById('modal-product-markup'),
        document.getElementById('modal-product-rating'),
        document.getElementById('modal-product-stock'),
        document.getElementById('modal-product-status'),
        document.getElementById('modal-product-description'),
        document.getElementById('modal-product-base-price'),
    ];
    modalElements.forEach(field => {
        field.value = '-'
    })
    const imgField = document.getElementById('modal-product-image')
    imgField.setAttribute('src', '')
    imgField.setAttribute('data-newname', '')
    imgField.setAttribute('data-oldname', '')
    // Đóng cửa sổ info
    document.getElementById('product-info-section').style.display = 'none';
    currentProduct = null
    document.querySelector('.restore-product-btn').style.display = 'none'
}

// ========================================================================== Delete Product ==========================================================================
async function confirmDeleteProduct() {
    // Always get the product ID directly from the modal that's currently open
    const productIdElement = document.getElementById('modal-product-id');
    let productId = null;
    console.log(productIdElement.value);
    
    
    if (!productIdElement || !productIdElement.value || productIdElement.value === '-') {
        alert('No product selected for deletion!');
        return;
    }
    
    try {
        productId = parseInt(productIdElement.value);
        if (isNaN(productId)) {
            alert('Invalid product ID!');
            return;
        }
        
        // Fetch fresh product details from the backend to ensure we have the correct information
        const productDetails = await getProductById(productId);
        if (!productDetails) {
            alert('Could not retrieve product details. Please try again.');
            return;
        }
        
        // Set the current product with the freshly fetched details
        currentProduct = productDetails;
        
        // Now proceed with the deletion dialog
        const confirmModal = document.getElementById('confirmModal');
        const confirmMessage = document.getElementById('confirmMessage');
        const confirmModalTitle = document.getElementById('confirm-modal-title');
        
        if (confirmModal && confirmMessage) {
            confirmMessage.textContent = `Are you sure you want to delete product "${currentProduct.name}" (ID: ${currentProduct.ID})?`;
            confirmModalTitle.textContent = 'Confirm Deleting';
            // Use the helper function from confirm_modal.php
            if (typeof showConfirmModal === 'function') {
                showConfirmModal();
            } else {
                confirmModal.style.display = 'flex';
            }
            
            // Set up confirm button action
            const confirmBtn = document.getElementById('confirmBtn');
            if (confirmBtn) {
                // Remove any existing event listeners
                const newConfirmBtn = confirmBtn.cloneNode(true);
                newConfirmBtn.classList.add('btn-danger');  // Thêm class cho nút mới
                confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
                // Add new event listener
                newConfirmBtn.addEventListener('click', async () => {
                    try {
                        const result = await deleteProduct(currentProduct.ID);
                        
                        if (result.action === 'discontinued') {
                            alert('Product has been marked as discontinued because it exists in order history.');
                        } else {
                            alert('Product has been successfully deleted from the database.');
                        }
                        
                        closeModal(); // Close the product details modal
                        confirmModal.style.display = 'none'; // Close the confirm modal
                        loadProducts(); // Refresh the product list
                    } catch (error) {
                        console.error('Error processing product deletion:', error);
                        alert('Error processing product: ' + error.message);
                    }
                });
            }
        } else {
            // Fallback if modal elements aren't found
            if (confirm(`Are you sure you want to delete product "${currentProduct.name}" (ID: ${currentProduct.ID})?`)) {
                try {
                    const result = await deleteProduct(currentProduct.ID);
                    
                    if (result.action === 'discontinued') {
                        alert('Product has been marked as discontinued because it exists in order history.');
                    } else {
                        alert('Product has been successfully deleted from the database.');
                    }
                    
                    closeModal();
                    loadProducts();
                } catch (error) {
                    console.error('Error processing product deletion:', error);
                    alert('Error processing product: ' + error.message);
                }
            }
        }
    } catch (error) {
        console.error('Error preparing for product deletion:', error);
        alert('An error occurred while preparing to delete the product. Please try again.');
    }
}
window.confirmDeleteProduct = confirmDeleteProduct;