import {
    getFilteredProducts,
    populateBrandFilter,
    populateCategoryFilter,
    getProductById,
    getBrandById,
    getCategoryById,
    getProductVariants,
} from './api.js'
const IMG_URL = '../../img/products/';

// ===================================== Function to load products with filters ===================================== 
const loadProducts = async () => {
    try {
        let categoryFilter  = document.getElementById('category').value
        let brandFilter     = document.getElementById('brand').value
        let statusFilter    = document.getElementById('status').value
        let ratingFilter    = document.getElementById('rating').value
        let startPriceFilter= document.getElementById('priceStart').value
        let endPriceFilter  = document.getElementById('priceEnd').value

        console.log(ratingFilter);
        
        let filteredProducts = await getFilteredProducts(categoryFilter, brandFilter, statusFilter, ratingFilter, startPriceFilter, endPriceFilter);

        const productGrid = document.getElementById('productGrid')
        productGrid.innerHTML = '';
        filteredProducts.forEach(product => {
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
                    view_btn.addEventListener('click', () => viewProduct(product.ID))
                else
                    console.warn('Không tìm thấy nút view-button cho sản phẩm', product.ID);

            });
    } catch (error) {
        console.error('Lỗi khi tải sản phẩm:', error);
    }
};

// ===================================== Initialize filters when page loads===================================== 
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Populate category & brand filter
        try {
            await Promise.all([
                populateBrandFilter(),
                populateCategoryFilter(),
                loadProducts()
            ]);
        } catch (err) {
            console.error('Lỗi khi khởi tạo filters và product:', err);
        }

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
        ['category', 'brand', 'status', 'rating', 'priceStart', 'priceEnd'].forEach( id => {
            const element = document.getElementById(id)
            if(element){
                const eventType = (id.includes('price') ? 'input' : 'change');
                element.addEventListener(eventType, loadProducts);
            }
        })

     } catch (error) {
         console.error('Error initializing filters:', error);
     }
 });

 // ================================== View detail of product ==================================
// Modal functions
async function viewProduct(id) {
    let product = null
    const modal = document.getElementById('productModal');
    modal.style.display = 'block';
    try {
        // Get product details
        let response = await getProductById(id);

        if (!response || !response.data) {
            throw new Error('No product data received');
        }
        product = response.data;


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

        // Set the image source and handle errors using the 'error' event listener
        modalElements.image.onerror = function() {
            this.src = `${IMG_URL}default.jpg`;
        };

        // Assign the image source
        modalElements.image.src = `${IMG_URL}${product.image}.jpg`;

        // Get and display category name
        if (product.categoryID) {
            response = await getCategoryById(product.categoryID);
            const category = response.data;
            modalElements.category.textContent = category ? category.name : 'Unknown Category';
        } else {
            modalElements.category.textContent = 'No category';
        }

        // Get and display brand name
        if (product.brandID) {
            response = await getBrandById(product.brandID);
            const brand = response.data;
            modalElements.brand.textContent = brand ? brand.name : 'Unknown Brand';
        } else {
            modalElements.brand.textContent = 'No brand';
        }

        // Get and display variants
        const res = await getProductVariants(id);
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