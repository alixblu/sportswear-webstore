<div id="create-form-section" class="create-product-form">
    <form id="createForm">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="create-form-item form-name" placeholder="Enter product's name .." required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" class="create-form-item form-category" required>
                    <option value="">Select category ..</option>
                    <!-- Categories populated dynamically -->
                </select>
            </div>
            <div class="form-group">
                <label for="brand">Brand</label>
                <select id="brand" name="brand" class="create-form-item form-brand" required>
                    <option value="">Select brand ..</option>
                    <!-- Brands populated dynamically -->
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="markup">Markup Percentage</label>
                <input type="number" id="markup" name="markup" class="create-form-item form-markup" min="0" step="0.1" max="70" required placeholder="Enter markup % ..">
            </div>
            <div class="form-group">
                <label for="discount">Discount ID</label>
                <select name="discount" id="discount" class="create-form-item form-discount">
                    <option value="">Select discount</option>
                    <!-- Discounts selection here -->
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="create-form-item form-description" placeholder="Enter a detailed description for producct .." required></textarea>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-outline" id="reset-btn" onclick="clearCreateModal()">Reset</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>