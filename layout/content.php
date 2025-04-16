<?php
// content.php - Product Listing Page with Pagination

// Sample product data (to be replaced with database data later)
$all_products = [
    [
        'id' => 1,
        'discount' => '40%',
        'name' => 'Adidas RUNFALCON 3.0',
        'price' => '$120',
        'original_price' => '$200',
        'rating' => '★★★★★ (88)'
    ],
    [
        'id' => 2,
        'name' => 'Short Tennis Dry TSH 100',
        'price' => '$960',
        'rating' => '★★★★★ (75)'
    ],
    [
        'id' => 3,
        'name' => 'Tennis Shirt Mens Drl Fit',
        'price' => '$370',
        'rating' => '★★★★★ (99)'
    ],
    [
        'id' => 4,
        'name' => 'Backpack NH Arpenaz 100',
        'price' => '$375',
        'rating' => '★★★★★ (99)'
    ],
    [
        'id' => 5,
        'discount' => '20%',
        'name' => 'Nike Air Max 270',
        'price' => '$150',
        'original_price' => '$190',
        'rating' => '★★★★☆ (120)'
    ],
    [
        'id' => 6,
        'name' => 'Puma Running Shorts',
        'price' => '$45',
        'rating' => '★★★★★ (65)'
    ],
    [
        'id' => 7,
        'discount' => '15%',
        'name' => 'Adidas Trefoil Hoodie',
        'price' => '$85',
        'original_price' => '$100',
        'rating' => '★★★★★ (92)'
    ],
    [
        'id' => 8,
        'name' => 'Nike Dri-FIT T-Shirt',
        'price' => '$35',
        'rating' => '★★★★☆ (78)'
    ]
];

// Pagination logic
$items_per_page = 4;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$total_items = count($all_products);
$total_pages = ceil($total_items / $items_per_page);
$offset = ($current_page - 1) * $items_per_page;
$products = array_slice($all_products, $offset, $items_per_page);
?>

<section class="product-section">
    <h1>Explore Our Products</h1>
    
    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <?php if (isset($product['discount'])): ?>
                    <div class="discount-badge"><?= $product['discount'] ?></div>
                <?php endif; ?>
                <div class="product-image">
                    <img src="https://via.placeholder.com/250x200?text=<?= urlencode($product['name']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                <div class="product-price">
                    <?php if (isset($product['original_price'])): ?>
                        <span class="original-price"><?= $product['original_price'] ?></span>
                    <?php endif; ?>
                    <span class="current-price"><?= $product['price'] ?></span>
                </div>
                <div class="product-rating"><?= $product['rating'] ?></div>
                <button class="buy-button" onclick="addToCart(<?= $product['id'] ?>)">
                    <i class="ri-shopping-cart-line"></i> Mua ngay
                </button>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?>" class="page-link">&laquo; Previous</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="page-link <?= $i == $current_page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        
        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?= $current_page + 1 ?>" class="page-link">Next &raquo;</a>
        <?php endif; ?>
    </div>
</section>

<script>
    function addToCart(productId) {
        // You'll implement actual cart functionality later
        alert('Added product ' + productId + ' to cart!');
        // Example AJAX call:
        /*
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product added to cart!');
            } else {
                alert('Error: ' + data.message);
            }
        });
        */
    }
</script>