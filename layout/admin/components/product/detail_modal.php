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
                <input type="text" class="info-value" id="modal-product-id" disabled required />
            </div>
            <div class="info-item feature">
                <div class="info-label">
                    <i class="fas fa-tag"></i> Name
                </div>
                <textarea type="text" class="info-value" id="modal-product-name" disabled required></textarea>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-layer-group"></i> Category
                </div>
                <select class="info-value" id="modal-product-category" disabled required>
                    <option value="">Select category ...</option>
                </select>
                <!--
                <input type="text" class="info-value" id="modal-product-category" disabled />
                -->
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-copyright"></i> Brand
                </div>
                <select class="info-value" id="modal-product-brand" disabled required>
                    <option value="">Select brand ...</option>
                </select>
                <!--
                <input type="text" class="info-value" id="modal-product-brand" disabled />
                -->
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-percentage"></i> Markup Percentage
                </div>
                <div style="display: flex;">
                    <input type="number" class="info-value" id="modal-product-markup" disabled required />
                    <span>%</span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-tags"></i> Discount ID
                </div>
                <select class="info-value" id="modal-product-discount-id" disabled required>
                    <option value="">-</option>
                </select>
                <!--
                <input type="number" class="info-value" id="modal-product-discount-id" disabled />
                -->
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-dollar-sign"></i> Base Price
                </div>
                <input type="number" class="info-value" id="modal-product-base-price" disabled required />
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
                <input class="info-value" id="modal-product-stock" disabled required />
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-info-circle"></i> Status
                </div>
                <input class="info-value" id="modal-product-status" disabled required />
            </div>
            <div class="info-item description">
                <div class="info-label">
                    <i class="fas fa-align-left"></i> Description
                </div>
                <textarea class="info-value" id="modal-product-description" disabled required></textarea>
            </div>
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