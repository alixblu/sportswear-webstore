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
        'name' => 'Nike Air Max 270',
        'price' => '$150',
        'rating' => '★★★★★ (92)'
    ],
    [
        'id' => 3,
        'discount' => '25%',
        'name' => 'Puma RS-X',
        'price' => '$90',
        'original_price' => '$120',
        'rating' => '★★★★☆ (76)'
    ],
    [
        'id' => 4,
        'name' => 'New Balance 574',
        'price' => '$85',
        'rating' => '★★★★★ (89)'
    ],
    [
        'id' => 5,
        'discount' => '30%',
        'name' => 'Reebok Classic',
        'price' => '$70',
        'original_price' => '$100',
        'rating' => '★★★★☆ (65)'
    ],
    [
        'id' => 6,
        'name' => 'Converse Chuck 70',
        'price' => '$80',
        'rating' => '★★★★★ (95)'
    ],
    [
        'id' => 7,
        'discount' => '15%',
        'name' => 'Vans Old Skool',
        'price' => '$60',
        'original_price' => '$70',
        'rating' => '★★★★★ (91)'
    ],
    [
        'id' => 8,
        'name' => 'Asics Gel-Kayano 28',
        'price' => '$160',
        'rating' => '★★★★★ (87)'
    ],
    [
        'id' => 9,
        'name' => 'Under Armour Tech T-Shirt',
        'price' => '$30',
        'rating' => '★★★★☆ (45)'
    ],
    [
        'id' => 10,
        'discount' => '25%',
        'name' => 'Reebok Running Shoes',
        'price' => '$90',
        'original_price' => '$120',
        'rating' => '★★★★★ (67)'
    ],
    [
        'id' => 11,
        'name' => 'Nike Basketball Shorts',
        'price' => '$55',
        'rating' => '★★★★★ (89)'
    ],
    [
        'id' => 12,
        'name' => 'Adidas Soccer Jersey',
        'price' => '$85',
        'rating' => '★★★★★ (92)'
    ],
    [
        'id' => 13,
        'discount' => '30%',
        'name' => 'Puma Training Shoes',
        'price' => '$70',
        'original_price' => '$100',
        'rating' => '★★★★☆ (56)'
    ],
    [
        'id' => 14,
        'name' => 'New Balance Running Jacket',
        'price' => '$110',
        'rating' => '★★★★★ (78)'
    ],
    [
        'id' => 15,
        'name' => 'Asics Volleyball Shoes',
        'price' => '$130',
        'rating' => '★★★★★ (82)'
    ],
    [
        'id' => 16,
        'discount' => '15%',
        'name' => 'Skechers Walking Shoes',
        'price' => '$65',
        'original_price' => '$75',
        'rating' => '★★★★★ (91)'
    ],
    [
        'id' => 17,
        'name' => 'Fila Training Pants',
        'price' => '$45',
        'rating' => '★★★★☆ (63)'
    ],
    [
        'id' => 18,
        'name' => 'Vans Classic Sneakers',
        'price' => '$60',
        'rating' => '★★★★★ (87)'
    ],
    [
        'id' => 19,
        'name' => 'Nike Air Force 1',
        'price' => '$100',
        'rating' => '★★★★★ (94)'
    ],
    [
        'id' => 20,
        'discount' => '20%',
        'name' => 'Adidas Ultraboost',
        'price' => '$160',
        'original_price' => '$200',
        'rating' => '★★★★★ (89)'
    ],
    [
        'id' => 21,
        'name' => 'Puma Cali Sport',
        'price' => '$85',
        'rating' => '★★★★☆ (72)'
    ],
    [
        'id' => 22,
        'name' => 'New Balance 990v5',
        'price' => '$175',
        'rating' => '★★★★★ (91)'
    ],
    [
        'id' => 23,
        'discount' => '10%',
        'name' => 'Reebok Nano X1',
        'price' => '$135',
        'original_price' => '$150',
        'rating' => '★★★★☆ (68)'
    ],
    [
        'id' => 23,
        'discount' => '10%',
        'name' => 'Reebok Nano X1',
        'price' => '$135',
        'original_price' => '$150',
        'rating' => '★★★★☆ (68)'
    ],
    [
        'id' => 24,
        'name' => 'Nike React Infinity Run',
        'price' => '$120',
        'rating' => '★★★★★ (90)'
    ],
    [
        'id' => 24,
        'name' => 'Nike React Infinity Run',
        'price' => '$120',
        'rating' => '★★★★★ (90)'
    ],
    [
        'id' => 24,
        'name' => 'Nike React Infinity Run',
        'price' => '$120',
        'rating' => '★★★★★ (90)'
    ],
    [
        'id' => 24,
        'name' => 'Nike React Infinity Run',
        'price' => '$120',
        'rating' => '★★★★★ (90)'
    ],
    [
        'id' => 24,
        'name' => 'Converse One Star',
        'price' => '$75',
        'rating' => '★★★★★ (85)'
    ]
];

// Check if view all is requested
$view_all = isset($_GET['view_all']) && $_GET['view_all'] == '1';

// Pagination logic
$items_per_page_default = 18; // Hiển thị 18 sản phẩm mặc định
$items_per_page = $view_all ? count($all_products) : $items_per_page_default;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$total_items = count($all_products);
$total_pages = ceil($total_items / $items_per_page);
$offset = ($current_page - 1) * $items_per_page;
$products = array_slice($all_products, $offset, $items_per_page);
?>

<section class="product-section">
    <div class="section-header">
        <h1>Explore Our Products</h1>
        <?php if (!$view_all && $total_items > $items_per_page_default): ?>
            <a href="?view_all=1" class="view-all-button">
                <i class="ri-eye-line"></i> View All
            </a>
        <?php elseif ($view_all): ?>
            <a href="?" class="view-all-button view-less-button">
                <i class="ri-close-line"></i> View Less
            </a>
        <?php endif; ?>
    </div>
    
    <div class="product-list <?= $view_all ? 'view-all-mode' : '' ?>">
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
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?<?= $view_all ? 'view_all=1&' : '' ?>page=<?= $current_page - 1 ?>" class="page-link">&laquo; Previous</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?<?= $view_all ? 'view_all=1&' : '' ?>page=<?= $i ?>" class="page-link <?= $i == $current_page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            
            <?php if ($current_page < $total_pages): ?>
                <a href="?<?= $view_all ? 'view_all=1&' : '' ?>page=<?= $current_page + 1 ?>" class="page-link">Next &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<script>
    function addToCart(productId) {
        alert('Added product ' + productId + ' to cart!');
    }
</script>