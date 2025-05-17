<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin/product.css">

    <script src="../../JS/admin/product/product.js" type="module"></script>
    <script src="../../JS/admin/product/helper.js" type="module"></script>
    <script src="../../JS/admin/product/create.js" type="module"></script>
</head>

<body>
    <div class="main-content">
        <div id="pageTitle" class="page-title">
            <div class="title">Products</div>
            <div class="action-buttons">
                <input type="file" id="fileInput" accept=".xlsx, .xls" style="display: none;">
                <button id="createBtn" class="btn btn-outline" onclick="openCreateModal()">
                    <i class="fas fa-plus"></i> New
                </button>
            </div>
        </div>

        <!-- Product Filters -->
        <div class="product-filters">
            <div class="filter-group search-group">
                <input type="text" id="search" class="filter-input" placeholder="Search products...">
            </div>
            <div class="filter-group">
                <select id="categoryFilter" class="filter-select">
                    <option value="">All Categories</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="brandFilter" class="filter-select">
                    <option value="">All Brands</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="ratingFilter" class="filter-select">
                    <option value="">All Ratings</option>
                    <option value="5">4-5 Stars</option>
                    <option value="4">3-4 Stars</option>
                    <option value="3">2-3 Stars</option>
                    <option value="2">1-2 Stars</option>
                    <option value="1">0 Star</option>
                </select>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="product-grid" id="productGrid">
            <!-- Products will be loaded here dynamically -->
        </div>

        <!-- Pagination  -->
        <div id="pagination">
            <!-- Insert page items -->
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal-overlay" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title"></h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Image section (used by Create, View, Update modals) -->
                <div class="product-image-section">
                    <div class="product-image-large">
                        <img id="modal-product-image" src="" alt="Product image" data-oldname="" data-newname="" />
                    </div>
                    <div class="product-actions">
                        <div id="img-btn">
                            <button class="btn btn-outline" onclick="triggerImageUpload()">
                                <i class="fas fa-image"></i> Change Image
                            </button>
                        </div>
                        <button class="btn btn-primary open-edit-form" onclick="switchEditing()">

                        </button>
                        <button class="btn btn-danger delete-product-btn" onclick="confirmDeleteProduct()">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                        <button class="btn btn-danger restore-product-btn" onclick="confirmRestoreProduct()" style="display: none;">
                            <i class="fas fa-undo"></i>
                            Restore
                        </button>
                        <button class="btn btn-primary" id="submit-update-btn">Save</button>
                        <input type="file" id="changeImageInput" accept="image/*" style="display:none;">
                    </div>
                </div>
                <!-- Modals -->
                <div id="modal-content-placeholder">
                    <!-- Dynamically loaded content will appear here -->
                </div>
            </div>
        </div>
    </div>
    <div id="confirmModal" class="confirm-modal">
        <div class="confirm-content">
            <div class="confirm-header">
                <h3 id="confirm-modal-title"> </h3>
                <button class="modal-close" onclick="document.getElementById('confirmModal').style.display='none'">&times;</button>
            </div>
            <div class="confirm-body">
                <p id="confirmMessage"> <!-- Write message here--> </p>
            </div>
            <div class="confirm-footer">
                <button class="btn btn-outline" onclick="document.getElementById('confirmModal').style.display='none'">Cancel</button>
                <button id="confirmBtn" class="btn">
                    Yes
                    <!-- Create button here :
                        danger : for delete, while editing
                        success : for insert new product
                -->
                </button>
            </div>
        </div>
    </div>
    <script>
        let productImg = null
        // Click input file
        function triggerImageUpload() {
            const imgInput = document.getElementById('changeImageInput')
            imgInput.click()
        }

        document.getElementById('changeImageInput').addEventListener('change', function(event) {
            const file = event.target.files[0]
            productImg = document.getElementById('modal-product-image')
            productImg.setAttribute('data-oldname', productImg.src)
            if (file) {
                // Add preview version of image being switched
                const imgURL = URL.createObjectURL(file)
                //Get old src
                productImg.src = imgURL
                // Get name of that image
                productImg.setAttribute('data-newname', file.name)
            }
        })

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content and mark tab as active
            document.getElementById(`${tabName}-tab`).classList.add('active');
            document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
        }
        // Helper to properly display the modal as flex
        function showConfirmModal() {
            const modal = document.getElementById('confirmModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        };
    </script>
</body>

</html>