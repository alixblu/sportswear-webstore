import {
    getFilteredProductsAdmin,
    deleteProduct,
    loadModal,
} from './api.js'
import {
    getAllBrands,
} from '../brand/api.js'
import {
    getAllCategories,
} from '../category/api.js'
import {
    getAllDiscounts
} from '../discount/api.js'
import {
    IMG_URL,
    productsPerPage,
} from './config.js'
import {
    viewProduct,
    clearDetailModal,
} from './detail.js'
import {
    renderStars,
} from './helper.js'
import {
    generateModal,
    clearCreateModal
} from './create.js'

let currentPage = 1
export let categoriesList = [], brandsList = [], discountsList = []
let mode = ''

// ===================================== Load products ===================================== 
// Load products into page
export const loadProducts = async () => {
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
                <img src="${IMG_URL}/product${product.ID}/${product.image}"></img>
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
                    <button class="btn btn-primary view-button" onclick="openDetailModal(${product.ID})">
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
// ================================== Get all categories, brands, discounts ==================================
// Function to generate categories list 
const populateCategoryFilter = async () => {
    try {
        const response = await getAllCategories();
        categoriesList.length = 0;
        categoriesList.push(...(response.data || []));

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
        brandsList.length = 0;
        brandsList.push(...(response.data || []));

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
        discountsList.length = 0;
        discountsList.push(...(response.data || []));
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
// ========================================================================== Tải modals ==========================================================================
// Goi modal (AJAX)
const loadModalContent = async (modalName) => {
    try{
        const response = await loadModal(modalName)
        document.getElementById('modal-content-placeholder').innerHTML = response
    } catch (error) {
        console.error('Không thể load modal:', error)
        document.getElementById('modal-content-placeholder').innerHTML = '<div class="error">Không thể tải nội dung modal.</div>'
    }
}
// Mở modal detail
const openDetailModal = async (productId) => {
    try{
        // Tải fild xem chi tiet sp ==> đặt mode
        await loadModalContent('detail')
        mode = 'update'
        // Mở modal
        document.getElementById('productModal').style.display = 'block'
        // Mở form chi tiết
        const editBtn = document.querySelector('.open-edit-form');
        if(editBtn){
            editBtn.style.display = 'block'
            editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit'
        }
        document.querySelector('.delete-product-btn').style.display = 'block'
        document.getElementById('img-btn').style.display = 'none'
        // Tải thông tin sp
        await viewProduct(productId)

    } catch (error) {
        console.error('Lỗi khi mở modal chi tiết sp:', error)
    }
}
window.openDetailModal = openDetailModal
// Mở modal thêm sp
const openCreateModal = async () => {
    try{
        // Tải fild form thêm sp ==> đặt mode
        await loadModalContent('create')
        mode = 'create'
        // Mở modal
        document.getElementById('productModal').style.display = 'block'
        // MỞ form tạo sp
        const createModal = document.getElementById('create-form-section')
        if(!createModal)
            throw new Error("Không tìm thấy form thêm sp");
        // Hiển thị modal thêm
        createModal.style.display = 'block'
        generateModal();
    } catch (error) {
        console.error('Lỗi khi mở modal thêm sp:', error)
    }
}
window.openCreateModal = openCreateModal
// ========================================================================== Close all modals ==========================================================================

export function closeModal() {
    document.getElementById('productModal').style.display = 'none'
    const eBtn = document.querySelector('.open-edit-form') 
    if(eBtn.innerHTML != '')
        eBtn.innerHTML = ''
    if(mode == 'update'){
        clearDetailModal()
    }
    if(mode == 'create'){
        clearCreateModal()
    }
    document.getElementById('modal-content-placeholder').innerHTML = ''
    // Gán lại mode 
    mode = ''
}
window.closeModal = closeModal