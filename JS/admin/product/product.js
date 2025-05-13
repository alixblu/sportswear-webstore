import {
    getProductVariants,
    getFilteredProductsAdmin,
    updateProduct,
    getProductById,
    deleteProduct,
} from './api.js'
import {
    getAllBrands,
    getBrandById,
} from '../brand/api.js'
import {
    getAllCategories,
    getCategoryById,
} from '../category/api.js'
import {
    getAllDiscounts
} from '../discount/api.js'

const IMG_URL = '/sportswear-webstore/img/products';
const productsPerPage = 30
let currentPage = 1
let currentProduct = null
let categoriesList = [], brandsList = [], discountsList = []
let formMode = ''

// ===================================== Load products ===================================== 
// Load products into page
const loadProducts = async () => {
    try {
        let categoryFilter  = document.getElementById('categoryFilter').value
        let brandFilter     = document.getElementById('brandFilter').value
        let statusFilter    = document.getElementById('statusFilter').value
        let ratingFilter    = document.getElementById('ratingFilter').value
        let searchFilter    = document.getElementById('search').value

        const productRespose = await getFilteredProductsAdmin(currentPage, productsPerPage, categoryFilter, brandFilter, statusFilter, ratingFilter, searchFilter);
        if(productRespose.data == null){
            console.warn('Không có sản phẩm nào');
        }
        displayProduct(productRespose.data)
        updatePaginationControls(productRespose.total)
    } catch (error) {
        console.error('Lỗi khi tải sản phẩm:', error);
    }
};
// Diplay products in particular page
const displayProduct = (data) => {
    const productGrid = document.getElementById('productGrid')
    productGrid.innerHTML = '';
    if(data.length === 0){
        productGrid.classList.add('no-results')
        productGrid.innerHTML = '<div class="no-products"><i class="fas fa-box-open"></i> Không tìm thấy sản phẩm nào.</div>';
        return;
    } else productGrid.classList.remove('no-results')

    data.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        productCard.innerHTML = `
            <div class="product-image">
                <span class="product-id-badge">#${product.ID}</span>
                <img src="${IMG_URL}/product${product.ID}/${product.ID}.jpg"></img>
                <span class="product-badge badge-${product.status === 'in_stock' ? 'in-stock' : 'out-stock'}">
                    ${product.status === 'in_stock' ? 'In Stock' : 'Out of Stock'}
                </span>
            </div>
            <div class="product-info">
                <h5 class="product-title">${product.name}</h5>
                <div class="product-meta">
                    <span class="product-stock">Stock: ${product.stock || 0}</span>
                    <span class="product-markup">Markup: ${product.markup_percentage}%</span>
                </div>
                <div class="product-rating">
                    ${renderStars(product.rating)}
                    <span class="rating-count">${product.rating ? `(${product.rating})` : '(No rating)'}</span>
                </div>
                <div class="product-actions">
                    <button class="btn btn-primary view-button" onclick="viewProduct(${product.ID})">
                        <i class="fas fa-eye"></i> View
                    </button>
                </div>
            </div>
            `;
            productGrid.appendChild(productCard);
        });
}
// Update pagiantion
const updatePaginationControls = (totalProducts) => {
    const paginationContainer = document.getElementById('pagination')
    paginationContainer.innerHTML=''
    if(totalProducts == 0){
        paginationContainer.innerHTML += '<button id="prev-page" disabled>1</button>'
        return
    }
    const totalPages = Math.ceil(totalProducts / productsPerPage)

    paginationContainer.innerHTML += `<button id="prev-page" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`
    for(let i=1; i <= totalPages; i++)
        paginationContainer.innerHTML += `<button class="page-btn ${i === currentPage ? 'active' : ''}" ${i === currentPage ? 'disabled': ''}>${i}</button>`
    paginationContainer.innerHTML += `<button id="next-page" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`

    document.querySelectorAll('.page-btn').forEach((button) => {
        button.addEventListener('click', () => {
            currentPage = parseInt(button.textContent)
            loadProducts()
        });
    })

    const nextBtn = document.getElementById('next-page');
    const prevBtn = document.getElementById('prev-page') 
    if(prevBtn)
        prevBtn.addEventListener('click', () => {
            currentPage --
            loadProducts()
        })
    if(nextBtn)
        nextBtn.addEventListener('click', () => {
            currentPage ++
            loadProducts()
        })
}

 // ================================== View detail of product modal ==================================
// Open modal to view detail
const viewProduct = async (id) => {
    if(!id)
        throw new Error("Chosen product is null !!!")

    // Add display for modal
    const modal = document.getElementById('productModal');
    modal.style.display = 'block';
    document.getElementById('product-info-section').style.display = 'block';
    document.querySelector('.product-image-section').style.display = 'flex';
    
    // Add header
    document.getElementById('modal-title').innerText = "Product Details"
    try {
        // Get product details
        const product = await getProductById(id);
        if(!product)
            throw new Error("Cannot get product to view detail !!!");

        const openEditBtn = document.querySelector('.open-edit-form');
        if(openEditBtn){
            openEditBtn.style.display = 'block'
            openEditBtn.addEventListener('click', () => showEditForm(product))
        }

        const category = categoriesList.find(ele => ele.ID === product.categoryID)
        const brand = brandsList.find(ele => ele.ID === product.brandID)

        // Set values for modal elements
        const modalElements = {
            id: document.getElementById('modal-product-id'),
            name: document.getElementById('modal-product-name'),
            markup: document.getElementById('modal-product-markup'),
            rating: document.getElementById('modal-product-rating'),
            stock: document.getElementById('modal-product-stock'),
            status: document.getElementById('modal-product-status'),
            description: document.getElementById('modal-product-description'),
            category: document.getElementById('modal-product-category'),
            brand: document.getElementById('modal-product-brand'),
            discountId: document.getElementById('modal-product-discount-id'),
            basePrice: document.getElementById('modal-product-base-price'),
            image: document.getElementById('modal-product-image'),
        };
        // Format price text
        const fomartedPrice = new Intl.NumberFormat('vi-VN').format(product.basePrice) + 'VND'

        // Update modal with product details
        modalElements.id.textContent = product.ID || '-';
        modalElements.name.textContent = product.name || '-';
        modalElements.markup.textContent = (product.markup_percentage || '0') + '%';
        modalElements.rating.innerHTML = renderStars(product.rating);
        modalElements.stock.textContent = product.stock || '0';
        modalElements.status.textContent = product.status === 'in_stock' ? 'In Stock' : 'Out of Stock';
        modalElements.description.textContent = product.description || 'No description available';
        modalElements.discountId.textContent = product.discountID || '-';
        modalElements.basePrice.textContent = fomartedPrice || '-';
        modalElements.category.textContent = category && category.name ? category.name : '-';
        modalElements.brand.textContent = brand && brand.name ? brand.name : '-';
        // Set the image source and handle errors using the 'error' event listener
        // modalElements.image.onerror = function() {
        //     this.src = `${IMG_URL}default.jpg`;
        // };

        // Assign the image source
        modalElements.image.src = `${IMG_URL}/product${product.ID}/${product.ID}.jpg`;
        modalElements.image.setAttribute('data-oldname', modalElements.image.src)

        // Get and display variants
        const res = await getProductVariants(product.ID);
        const variants = res.data || [];
        const variantsList = document.getElementById('modal-variants-list');

        if (variantsList) {
            variantsList.innerHTML = '';
            if (variants && variants.length > 0) {
                variants.forEach(variant => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${variant.Code || '-'}</td>
                        <td>${variant.fullName || '-'}</td>
                        <td>${variant.color || '-'}</td>
                        <td>${variant.size || '-'}</td>
                        <td>${variant.quantity || '0'}</td>
                        <td>${variant.price || '0'}</td>
                        <td>${variant.status || '-'}</td>
                    `;
                    variantsList.appendChild(row);
                });
            } else {
                variantsList.innerHTML = '<tr><td colspan="8" class="text-center">No variants found</td></tr>';
            }
        }
    } catch (error) {
        console.error('Error loading product details:', error);
        alert('Error loading product details: ' + error.message);
    }
}
window.viewProduct = viewProduct
// Function to render stars based on rating
function renderStars(rating) {
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

// ================================== Edit product modal ==================================
async function showEditForm(product) {
    if(!product)
        throw new Error("Chosen product is null !!!")
    currentProduct = product
    // Hide product info and show edit form
    formMode = 'update'
    document.getElementById('product-info-section').style.display = 'none';
    document.getElementById('edit-form-section').style.display = 'block';
    document.getElementById('img-btn').style.display = 'block';
    document.getElementById('cancel-edit-btn').style.display = 'block';

    try {
        // Fill edit form
        fillEditForm(product)
    } catch (error) {
        console.error('Error loading edit form:', error);
        alert('Error loading edit form: ' + error.message);
    }
}
window.showEditForm = showEditForm
// Cancel editing
function cancelEdit() {
    formMode = ''
    resetForm();
    const img = document.getElementById('modal-product-image');
    const oldSrc = img.getAttribute('data-oldname')
    img.src = oldSrc
    document.getElementById('product-info-section').style.display = 'block';
    document.getElementById('edit-form-section').style.display = 'none';
    document.getElementById('img-btn').style.display = 'none';
    document.getElementById('cancel-edit-btn').style.display = 'none';
}
// Reset edit form
function fillEditForm(product) {
    document.getElementById('edit-name').value = product.name || '';
    document.getElementById('edit-markup').value = product.markup_percentage || '0';
    document.getElementById('edit-discount').value = product.discountID || '';
    document.getElementById('edit-description').value = product.description || '';

        // Load categories
    const categorySelect = document.getElementById('edit-category');
        categoriesList.forEach(category => {
            const option = document.createElement('option');
            option.value = category.ID;
            option.textContent = category.name;
            option.selected = category.ID === product.categoryID;
            categorySelect.appendChild(option);
        });
        // Load brands
    const brandSelect = document.getElementById('edit-brand');
        brandsList.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.ID;
            option.textContent = brand.name;
            option.selected = brand.ID === product.brandID;
            brandSelect.appendChild(option);
        });
        // Load discounts 
    const discountSelect = document.getElementById('edit-discount')
        discountsList.forEach(discount => {
            const option = document.createElement('option')
            option.value = discount.ID
            option.textContent = discount.discountRate + '%' 
            option.selected = discount.ID === product.discountID
            discountSelect.appendChild(option)
        })
}
// ===================================== Process updating product =====================================
const proccessUpdating = async () => {
    if(!currentProduct)
        throw new Error('Cannot get chosen product !!!')
    try{
        const formData = {
            ID: currentProduct.ID,
            categoryID: Number(document.getElementById('edit-category').value),
            discountID: Number(document.getElementById('edit-discount').value) || null,
            brandID: Number(document.getElementById('edit-brand').value),
            name: document.getElementById('edit-name').value,
            markup_percentage: Number(document.getElementById('edit-markup').value),
            rating: currentProduct.rating,
            description: document.getElementById('edit-description').value,
            stock: currentProduct.stock,
            status: currentProduct.status,
            // get image
            image: null,
        }
        const img = document.getElementById('modal-product-image');
        if(img.getAttribute('data-newname') !== '')
            formData.image = img.getAttribute('data-newname')
        else
            formData.image = img.getAttribute('data-oldname').split('/').pop()

        const isChanged = Object.keys(formData).some(key => formData[key] !== currentProduct[key])
        
        if(!isChanged){
            console.error('Product is not changed !!!');
            return;
        }
        // Update product into db
        const response = await updateProduct(formData);
        if (response) {
            alert('Product updated successfully!');
            await loadProducts(); // Refresh grid
            await viewProduct(formData.ID); // Refresh view
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
// ================================== Create product modal ==================================
function openCreateModal() {
    formMode = 'create'
    document.getElementById('productModal').style.display = 'block';
    document.getElementById('create-form-section').style.display = 'block';
    document.querySelector('.product-image-section').style.display = 'flex'
    document.getElementById('img-btn').style.display = 'block';
    document.getElementById('reset-btn').style.display = 'block';
    const img = document.getElementById('modal-product-image')
    img.src = `${IMG_URL}default.jpg`
    img.setAttribute('data-newname', '')
    img.setAttribute('data-oldname', '')
    document.getElementById('modal-title').innerText = "Add New Product"

    // Load categories
    const categorySelect = document.getElementById('create-category');
    categoriesList.forEach(category => {
        const option = document.createElement('option');
        option.value = category.ID;
        option.textContent = category.name;
        categorySelect.appendChild(option);
    });
    // Load brands
    const brandSelect = document.getElementById('create-brand');
    brandsList.forEach(brand => {
        const option = document.createElement('option');
        option.value = brand.ID;
        option.textContent = brand.name;
        brandSelect.appendChild(option);
    });
    // Load discounts 
    const discountSelect = document.getElementById('create-discount')
    discountsList.forEach(discount => {
        const option = document.createElement('option')
        option.value = discount.ID
        option.textContent = discount.discountRate + '%' 
        discountSelect.appendChild(option)
        })
    
}
window.openCreateModal = openCreateModal
function resetCreateForm() {

}

// ================================== Get all categories, brands, discounts ==================================
// Function to generate categories list 
const populateCategoryFilter = async () => {
    try {
        const response = await getAllCategories();
        categoriesList = response.data || [];
        const categorySelect = document.getElementById('categoryFilter');
        
        // Clear existing options except the first one
        while (categorySelect.options.length > 1) {
            categorySelect.remove(1);
        }

        // Add new options
        categoriesList.forEach(category => {
            const option = document.createElement('option');
            option.value = category.ID;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error populating category filter:', error);
    }
};
// Function to generate brands list 
const populateBrandFilter = async () => {
    try {
        const response = await getAllBrands();
        brandsList = response.data || [];
        const brandSelect = document.getElementById('brandFilter');
        
        // Clear existing options except the first one
        while (brandSelect.options.length > 1) {
            brandSelect.remove(1);
        }

        // Add new options
        brandsList.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.ID;
            option.textContent = brand.name;
            brandSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error populating brand filter:', error);
    }
};
// Function to generate discounts list 
const populateDiscountFilter = async () => {
    try {
        const response = await getAllDiscounts();
        discountsList = response.data || [];
    } catch (error) {
        console.error('Error populating brand filter:', error);
    }
}

// ===================================== Initialize filters when page loads===================================== 
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Populate category & brand filter
        await Promise.all([
            populateCategoryFilter(),
            populateBrandFilter(),
            populateDiscountFilter(),
        ]);
        
        // Then load products
        await loadProducts()
        
        // Set status filter options
        const statusSelect = document.getElementById('statusFilter');
        
        // Keep the first "All Status" option
        while (statusSelect.options.length > 1) {
            statusSelect.remove(1);
        }
        
        const statusOptions = [
            { value: 'in_stock', text: 'Còn hàng' },
            { value: 'out_of_stock', text: 'Hết hàng' },
            { value: 'discontinued', text: 'Ngừng kinh doanh' }
        ];
        
        statusOptions.forEach(status => {
            const option = document.createElement('option');
            option.value = status.value;
            option.textContent = status.text;
            statusSelect.appendChild(option);
        });

         // Gán sự kiện xử lý bộ lọc
        ['search', 'categoryFilter', 'brandFilter', 'statusFilter', 'ratingFilter'].forEach( id => {
            const element = document.getElementById(id)
            if(element){
                const eventType = (id.includes('price') || id.includes('search') ? 'input' : 'change');
                element.addEventListener(eventType, () => {
                    currentPage = 1
                    loadProducts()
                });
            }
        })
    } catch (error) {
        console.error('Error initializing filters:', error);
        alert('Error loading product data: ' + error.message);
    }
});

// ===================================== Others =====================================
function resetForm() {
    document.getElementsByClassName('form-name')[0].value = '';
    document.getElementsByClassName('form-markup')[0].value = null;
    document.getElementsByClassName('form-category')[0].value = '';
    document.getElementsByClassName('form-brand')[0].value = '';
    document.getElementsByClassName('form-discount')[0].value = '';
    document.getElementsByClassName('form-description')[0].value = '';
}
window.resetForm = resetForm
// Close modal
async function closeModal() {
    resetForm()
    document.getElementById('productModal').style.display = 'none';
    document.getElementById('edit-form-section').style.display = 'none';
    document.getElementById('create-form-section').style.display = 'none'
    document.querySelector('.product-info-section').style.display = 'none'
    const img = document.getElementById('modal-product-image');
    img.setAttribute('data-oldname', '')
    img.setAttribute('data-newname', '')
    img.setAttribute('src', '')
    document.getElementById('img-btn').style.display = 'none';
    document.getElementById('cancel-edit-btn').style.display = 'none';
    document.getElementById('reset-btn').style.display = 'none';
}
window.closeModal = closeModal


document.getElementById('createForm').addEventListener('submit', async (e) => {
    e.preventDefault()
    await proccessCreating();
})
document.getElementById('editForm').addEventListener('submit', async (e) => {
    e.preventDefault()
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries()) 
    if(currentProduct)
        await proccessUpdating();
})
document.getElementById('cancel-edit-btn').addEventListener('click', () => cancelEdit())

// ===================================== Delete Product =====================================
async function confirmDeleteProduct() {
    // Always get the product ID directly from the modal that's currently open
    const productIdElement = document.getElementById('modal-product-id');
    let productId = null;
    
    if (!productIdElement || !productIdElement.textContent || productIdElement.textContent === '-') {
        alert('No product selected for deletion!');
        return;
    }
    
    try {
        productId = parseInt(productIdElement.textContent);
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
        
        if (confirmModal && confirmMessage) {
            confirmMessage.textContent = `Are you sure you want to delete product "${currentProduct.name}" (ID: ${currentProduct.ID})?`;
            
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
