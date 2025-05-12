<div id="create-form-section" class="create-product-form">
    <form id="createForm">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="create-name" name="name" class="form-name" placeholder="Enter product's name ..." required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="category">Category</label>
                <select id="create-category" name="category" class="form-category" required>
                    <option value="">Select category</option>
                    <!-- Categories populated dynamically -->
                </select>
            </div>
            <div class="form-group">
                <label for="brand">Brand</label>
                <select id="create-brand" name="brand" class="form-brand" required>
                    <option value="">Select brand</option>
                    <!-- Brands populated dynamically -->
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="markup">Markup Percentage</label>
                <input type="number" id="create-markup" name="markup" class="form-markup" min="0" step="0.1" required>
            </div>
            <div class="form-group">
                <label for="discount">Discount ID</label>
                <select name="discount" id="create-discount" class="form-discount">
                    <option value="">Select discount</option>
                    <!-- Discounts selection here -->
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="create-description" class="form-description" placeholder="Enter a detailed description for producct ..." required></textarea>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-outline" id="reset-btn" onclick="resetForm()">Reset</button>
            <button type="submit" class="btn btn-primary" id="form-submit-btn">Save</button>
        </div>
    </form>
</div>