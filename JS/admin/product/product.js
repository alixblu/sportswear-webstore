import {
    getProductVariants,
    getFilteredProductsAdmin,
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

const IMG_URL = '../../img/products/';

const productsPerPage = 30
let currentPage = 1
let filteredProducts = []
let chosenProduct = null, product = null
let categoriesList = [], brandsList = [], discountsList = []

// ===================================== Function to load products with filters ===================================== 
const loadProducts = async () => {
    try {
        let categoryFilter  = document.getElementById('category').value
        let brandFilter     = document.getElementById('brand').value
        let statusFilter    = document.getElementById('status').value
        let ratingFilter    = document.getElementById('rating').value
        let searchFilter    = document.getElementById('search').value
//        let startPriceFilter= document.getElementById('priceStart').value
//        let endPriceFilter  = document.getElementById('priceEnd').value
        filteredProducts = await getFilteredProductsAdmin(categoryFilter, brandFilter, statusFilter, ratingFilter, searchFilter);
        currentPage = filteredProducts.length != 0 ? 1 : 0;
        displayProduct(currentPage)
        updatePaginationControls(filteredProducts.length)
    } catch (error) {
        console.error('Lỗi khi tải sản phẩm:', error);
    }
};

// ===================================== Diplay products in particular page =====================================
const displayProduct = (page=1) => {
    const startIndex = (page -1) * productsPerPage
    const endIndex = startIndex + productsPerPage
    const productsList = filteredProducts.slice(startIndex, endIndex)

    const productGrid = document.getElementById('productGrid')
    productGrid.innerHTML = '';
    if(filteredProducts.length === 0){
        productGrid.classList.add('no-results')
        productGrid.innerHTML = '<div class="no-products"><i class="fas fa-box-open"></i> Không tìm thấy sản phẩm nào.</div>';
        return;
    } else productGrid.classList.remove('no-results')


    productsList.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        productCard.innerHTML = `
            <div class="product-image">
                <span class="product-id-badge">#${product.ID}</span>
                <img src="${IMG_URL + product.image}.jpg"></img>
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
                    <button class="btn btn-primary view-button">
                        <i class="fas fa-eye"></i> View
                    </button>
                </div>
            </div>
            `;
            productGrid.appendChild(productCard);

            const view_btn = productCard.querySelector('.view-button')
            if(view_btn)
                view_btn.addEventListener('click', () => {
                    chosenProduct = product.ID
                    viewProduct()
                })
            else
                console.error('Không tìm thấy nút view-button cho sản phẩm', product.ID);
        });
}

const updatePaginationControls = (totalProducts) => {
    const paginationContainer = document.getElementById('pagination')
    paginationContainer.innerHTML=''
    if(totalProducts == 0){
        paginationContainer.innerHTML += '<button id="prev-page" disabled>1</button>'
        return
    }

    const totalPages = Math.ceil(totalProducts / productsPerPage)
    if(currentPage > 1)
        paginationContainer.innerHTML += `<button id="prev-page">Previous</button>`
    for(let i=1; i <= totalPages; i++)
        paginationContainer.innerHTML += `<button class="page-btn ${i === currentPage ? 'active' : ''}">${i}</button>`
    if(currentPage < totalPages)
        paginationContainer.innerHTML += `<button id="next-page">Next</button>`

    document.querySelectorAll('.page-btn').forEach((button) => {
        button.addEventListener('click', () => {
            currentPage = parseInt(button.textContent)
            displayProduct(currentPage)
            updatePaginationControls(totalProducts)
        });
    })

    const nextBtn = document.getElementById('next-page');
    const prevBtn = document.getElementById('prev-page') 
    if(prevBtn)
        prevBtn.addEventListener('click', () => {
            currentPage --
            displayProduct(currentPage)
            updatePaginationControls(totalProducts)
        })
    if(nextBtn)
        nextBtn.addEventListener('click', () => {
            currentPage ++
            displayProduct(currentPage)
            updatePaginationControls(totalProducts)
        })
}

// ===================================== Initialize filters when page loads===================================== 
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Populate category & brand filter
        await Promise.all([
            populateBrandFilter(),
            populateCategoryFilter(),
            populateDiscountFilter(),
        ]);
        loadProducts()
        
        // Set status filter options
        const statusSelect = document.getElementById('status');
        
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
        ['search', 'category', 'brand', 'status', 'rating', 'priceStart', 'priceEnd'].forEach( id => {
            const element = document.getElementById(id)
            if(element){
                const eventType = (id.includes('price') || id.includes('search') ? 'input' : 'change');
                element.addEventListener(eventType,loadProducts);
            }
        })

     } catch (error) {
         console.error('Error initializing filters:', error);
     }
 });

 // ================================== View detail of product ==================================
// Modal functions
const viewProduct = async () => {
    if(!chosenProduct)
        throw new Error("Chosen product is null !!!")

    const modal = document.getElementById('productModal');
    modal.style.display = 'block';
    try {
        // Get product details
        product = filteredProducts.find(val => val.ID === chosenProduct);
        if(!product)
            throw new Error("Cannot get product to view detail !!!");

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

        // Update modal with product details
        modalElements.id.textContent = product.ID || '-';
        modalElements.name.textContent = product.name || '-';
        modalElements.markup.textContent = (product.markup_percentage || '0') + '%';
        modalElements.rating.innerHTML = renderStars(product.rating);
        modalElements.stock.textContent = product.stock || '0';
        modalElements.status.textContent = product.status === 'in_stock' ? 'In Stock' : 'Out of Stock';
        modalElements.description.textContent = product.description || 'No description available';
        modalElements.discountId.textContent = product.discountID || '-';
        modalElements.basePrice.textContent = product.price || '-';
        modalElements.category.textContent = category.name || '-';
        modalElements.brand.textContent = brand.name || '-';
        // Set the image source and handle errors using the 'error' event listener
        modalElements.image.onerror = function() {
            this.src = `${IMG_URL}default.jpg`;
        };

        // Assign the image source
        modalElements.image.src = `${IMG_URL}${product.ID}.jpg`;

        // Get and display variants
        const res = await getProductVariants(chosenProduct);
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
// Close modal
function closeModal() {
    cancelEdit();
    const modal = document.getElementById('productModal');
    modal.style.display = 'none';
    product = null
}
window.closeModal = closeModal
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

// ================================== Open & Close Edit Form ==================================
async function showEditForm() {
    if(!chosenProduct)
        throw new Error("Chosen product is null !!!")
    // Hide product info and show edit form
    document.getElementById('product-info-section').style.display = 'none';
    document.getElementById('edit-form-section').style.display = 'block';

    try {
        // Get product detailsj
        product = filteredProducts.find(val => val.ID == chosenProduct)

        // Populate form fields
        document.getElementById('editName').value = product.name || '';
        document.getElementById('editMarkup').value = product.markup_percentage || '0';
        document.getElementById('editDiscount').value = product.discountID || '';
        document.getElementById('editDescription').value = product.description || '';

        // Load categories
        const categorySelect = document.getElementById('editCategory');
        categorySelect.innerHTML = '<option value="">Select Category</option>';
        categoriesList.forEach(category => {
            const option = document.createElement('option');
            option.value = category.ID;
            option.textContent = category.name;
            option.selected = category.ID === product.categoryID;
            categorySelect.appendChild(option);
        });

        // Load brands
        const brandSelect = document.getElementById('editBrand');
        brandSelect.innerHTML = '<option value="">Select Brand</option>';
        brandsList.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.ID;
            option.textContent = brand.name;
            option.selected = brand.ID === product.brandID;
            brandSelect.appendChild(option);
        });

        // Load discounts 
        const discountSelect = document.getElementById('editDiscount')
        discountSelect.innerHTML = '<option value="">Select discount</option>'
        discountsList.forEach(discount => {
            const option = document.createElement('option')
            option.value = discount.ID
            option.textContent = discount.discountRate + '%' 
            option.selected = discount.ID === product.discountID
            discountSelect.appendChild(option)
        })

    } catch (error) {
        console.error('Error loading edit form:', error);
        alert('Error loading edit form: ' + error.message);
    }
}
window.showEditForm = showEditForm
// Cancel editing
function cancelEdit() {
    document.getElementById('product-info-section').style.display = 'block';
    document.getElementById('edit-form-section').style.display = 'none';

}
window.cancelEdit = cancelEdit

// ================================== Get all categories, brands, discounts ==================================
const populateCategoryFilter = async () => {
    try {
        const response = await getAllCategories();
        categoriesList = response.data || [];
        const categorySelect = document.getElementById('category');
        
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

// ===================================== Function to populate brand dropdown ===================================== 
const populateBrandFilter = async () => {
    try {
        const response = await getAllBrands();
        brandsList = response.data || [];
        const brandSelect = document.getElementById('brand');
        
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

const populateDiscountFilter = async () => {
    try {
        const response = await getAllDiscounts();
        discountsList = response.data || [];
    } catch (error) {
        console.error('Error populating brand filter:', error);
    }
}
