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
    <div class="tabs">
        <div class="tab active" onclick="switchTab('details')">Details</div>
        <div class="tab" onclick="switchTab('variants')">Variants</div>
    </div>
    <form id="editForm">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="edit-name" name="name" placeholder="Enter product's name ..." required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="category">Category</label>
                <select id="edit-category" name="category" required>
                    <option value="">Select category</option>
                    <!-- Categories populated dynamically -->
                </select>
            </div>
            <div class="form-group">
                <label for="brand">Brand</label>
                <select id="edit-brand" name="brand" required>
                    <option value="">Select brand</option>
                    <!-- Brands populated dynamically -->
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="markup">Markup Percentage</label>
                <input type="number" id="edit-markup" name="markup" min="0" step="0.1" required>
            </div>
            <div class="form-group">
                <label for="discount">Discount ID</label>
                <select name="discount" id="edit-discount">
                    <option value="">Select discount</option>
                    <!-- Discounts selection here -->
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="edit-description" name="description" placeholder="Enter a detailed description for producct ..." required></textarea>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-outline" id="cancel-edit-btn">Cancel edit</button>
            <button type="submit" class="btn btn-primary" id="form-submit-btn">Save</button>
        </div>
    </form>
</div>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });

        // Show selected tab content and mark tab as active
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });

        document.getElementById(`${tabName}-tab`).classList.add('active');
        document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
    }
</script>