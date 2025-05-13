<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin/product.css">

    <script src="../../JS/admin/product/product.js" type="module"></script>
    <a href="../components/product/detail_modal.php"></a>
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
                <button id="exportBtn" class="btn btn-outline">
                    <i class="fas fa-download"></i> Export
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
                        <button class="btn btn-primary open-edit-form">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-danger delete-product-btn" onclick="confirmDeleteProduct()">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                        <input type="file" id="changeImageInput" accept="image/*" style="display:none;">
                        <div id="img-btn" style="display: none;">
                            <button class="btn btn-outline" id="img-btn" onclick="triggerImageUpload()">
                                <i class="fas fa-image"></i> Change Image
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Modals -->
                <?php include(__DIR__ . '/../components/product/detail_modal.php');     ?>
                <?php include(__DIR__ . '/../components/product/create_product.php');   ?>
                <?php include(__DIR__ . '/../components/product/confirm_modal.php');    ?>
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
    </script>
</body>

</html>