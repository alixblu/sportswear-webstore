<div class="modal-content">
    <div class="modal-header">
        <h2>Product Details</h2>
        <button class="modal-close" onclick="closeModal()">&times;</button>
    </div>
    <div class="modal-body">
        <div class="product-image-section">
            <div class="product-image-large">
                <img id="modal-product-image" src="" alt="Product Image" />
            </div>
            <div class="product-actions">
                <button class="btn btn-primary" onclick="showEditForm()">
                    <i class="fas fa-edit"></i> Edit Product
                </button>

                <input type="file" id="changeImageInput" accept="image/*" style="display:none;">
                <button class="btn btn-outline" onclick="triggerImageUpload()">
                    <i class="fas fa-image"></i> Change Image
                </button>
            </div>
            <div class="discontinued-action">
                <button class="btn btn-discontinued">
                    <i class="fas fa-ban"></i> Mark as Discontinued
                </button>
            </div>
        </div>
        <div class="product-info-section" id="product-info-section">
            <div class="tabs">
                <div class="tab active" onclick="switchTab('details')">Details</div>
                <div class="tab" onclick="switchTab('variants')">Variants</div>
            </div>

            <div id="details-tab" class="tab-content active">
                <div class="info-grid">
                    <div class="info-item feature">
                        <div class="info-label">
                            <i class="fas fa-barcode"></i> Product ID
                        </div>
                        <div class="info-value" id="modal-product-id">-</div>
                    </div>
                    <div class="info-item feature">
                        <div class="info-label">
                            <i class="fas fa-tag"></i> Name
                        </div>
                        <div class="info-value" id="modal-product-name">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-layer-group"></i> Category
                        </div>
                        <div class="info-value" id="modal-product-category">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-copyright"></i> Brand
                        </div>
                        <div class="info-value" id="modal-product-brand">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-percentage"></i> Markup Percentage
                        </div>
                        <div class="info-value" id="modal-product-markup">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-tags"></i> Discount ID
                        </div>
                        <div class="info-value" id="modal-product-discount-id">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-dollar-sign"></i> Base Price
                        </div>
                        <div class="info-value" id="modal-product-base-price">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-star"></i> Rating
                        </div>
                        <div class="info-value" id="modal-product-rating">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-box"></i> Stock
                        </div>
                        <div class="info-value" id="modal-product-stock">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-info-circle"></i> Status
                        </div>
                        <div class="info-value" id="modal-product-status">-</div>
                    </div>
                </div>
                <div class="info-item description">
                    <div class="info-label">
                        <i class="fas fa-align-left"></i> Description
                    </div>
                    <div class="info-value" id="modal-product-description">-</div>
                </div>
            </div>

            <div id="variants-tab" class="tab-content">
                <table class="variants-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Full Name</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Base Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="modal-variants-list">
                        <!-- Variants will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Form Section (hidden by default) -->
        <div id="edit-form-section" class="edit-form">
            <form id="productEditForm">
                <div class="form-group">
                    <label for="editName">Name</label>
                    <input type="text" id="editName" name="name">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editCategory">Category</label>
                        <select id="editCategory" name="category">
                            <!-- Categories populated dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editBrand">Brand</label>
                        <select id="editBrand" name="brand">
                            <!-- Brands populated dynamically -->
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editMarkup">Markup Percentage</label>
                        <input type="number" id="editMarkup" name="markup" min="0" step="0.1">
                    </div>
                    <div class="form-group">
                        <label for="editDiscount">Discount ID</label>
                        <input type="text" id="editDiscount" name="discount">
                    </div>
                </div>
                <div class="form-group">
                    <label for="editDescription">Description</label>
                    <textarea id="editDescription" name="description"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="cancelEdit()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

    </div>
</div>