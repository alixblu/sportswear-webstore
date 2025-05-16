<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/sportswear-webstore/css/auth.css">
    <script src="/sportswear-webstore/JS/auth.js"></script>
    <!-- <script src="./js/client/search.js" defer></script> -->

</head>
</head>

<body>
    <header class="header">
        <nav class="nav container">
            <div class="nav__data">
                <a href="/sportswear-webstore/index.php" class="nav__logo">
                    <i class="ri-store-2-fill"></i> Cửa hàng Sportwear
                </a>
            </div>
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li><a href="/sportswear-webstore/index.php" class="nav__link">Trang chủ</a></li>
                    <li class="dropdown__item">
                        <div class="nav__link">
                            Sản phẩm <i class="ri-arrow-down-s-line dropdown__arrow"></i>
                        </div>
                        <ul class="dropdown__menu">
                            <li class="dropdown__subitem">
                                <div class="dropdown__link">
                                    <i class="ri-user-line"></i> Nam <i class="ri-add-line dropdown__add"></i>
                                </div>
                                <ul class="dropdown__submenu">
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=5&sort=newest" class="dropdown__sublink">
                                            <i class="ri-t-shirt-line"></i> Áo nam
                                        </a></li>
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=6&sort=newest" class="dropdown__sublink">
                                            <i class="ri-arrow-up-down-line"></i> Quần nam
                                        </a></li>
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=7&sort=newest" class="dropdown__sublink">
                                            <i class="ri-footprint-line"></i> Giày nam
                                        </a></li>
                                </ul>
                            </li>
                            <li class="dropdown__subitem">
                                <div class="dropdown__link">
                                    <i class="ri-user-star-line"></i> Nữ <i class="ri-add-line dropdown__add"></i>
                                </div>
                                <ul class="dropdown__submenu">
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=8&sort=newest" class="dropdown__sublink">
                                            <i class="ri-t-shirt-line"></i> Áo nữ
                                        </a></li>
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=9&sort=newest" class="dropdown__sublink recourse">
                                            <i class="ri-arrow-up-down-line"></i> Quần nữ
                                        </a></li>
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=10&sort=newest" class="dropdown__sublink">
                                            <i class="ri-footprint-line"></i> Giày nữ
                                        </a></li>
                                </ul>
                            </li>
                            <li class="dropdown__subitem">
                                <div class="dropdown__link">
                                    <i class="ri-shield-line"></i> Trang bị <i class="ri-add-line dropdown__add"></i>
                                </div>
                                <ul class="dropdown__submenu">
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=14&sort=newest" class="dropdown__sublink">
                                            <i class="ri-baseball-line"></i> Vợt
                                        </a></li>
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=15&sort=newest" class="dropdown__sublink">
                                            <i class="ri-football-line"></i> Bóng
                                        </a></li>
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=16&sort=newest" class="dropdown__sublink">
                                            <i class="ri-dumbbell-line"></i> Dụng cụ tập gym
                                        </a></li>
                                </ul>
                            </li>
                            <li class="dropdown__subitem">
                                <div class="dropdown__link">
                                    <i class="ri-bar-chart-line"></i> Phụ kiện <i class="ri-add-line dropdown__add"></i>
                                </div>
                                <ul class="dropdown__submenu">
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=11&sort=newest" class="dropdown__sublink">
                                            <i class="ri-briefcase-line"></i> Balo
                                        </a></li>
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=12&sort=newest" class="dropdown__sublink">
                                            <i class="ri-hand-heart-line"></i> Găng tay
                                        </a></li>
                                    <li><a href="/sportswear-webstore/layout/client/search_results.php?category=13&sort=newest" class="dropdown__sublink">
                                            <i class="ri-bandage-line"></i> Băng quấn
                                        </a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown__item">
                        <div class="nav__link">
                            Thương hiệu <i class="ri-arrow-down-s-line dropdown__arrow"></i>
                        </div>
                        <ul class="dropdown__menu">
                            <li><a href="/sportswear-webstore/layout/client/search_results.php?brand=1" class="dropdown__link">
                                    <i class="ri-user-line"></i> Nike
                                </a></li>
                            <li><a href="/sportswear-webstore/layout/client/search_results.php?brand=2" class="dropdown__link">
                                    <i class="ri-lock-line"></i> Adidas
                                </a></li>
                            <li><a href="/sportswear-webstore/layout/client/search_results.php?brand=3" class="dropdown__link">
                                    <i class="ri-message-3-line"></i> Puma
                                </a></li>
                        </ul>
                    </li>
                    <li><a href="#" class="nav__link">Chính sách bảo hành</a></li>
                    <li><a href="#" class="nav__link">Liên hệ</a></li>
                </ul>
            </div>
            <div class="nav__tools">
                <div class="search-box">
                    <form id="searchForm" action="/sportswear-webstore/layout/client/search_results.php" method="GET">
                        <i class="ri-search-line"></i>
                        <input type="text" name="search" id="searchInput" placeholder="Tìm kiếm..." required>
                        <button type="submit" style="display: none;"></button>
                    </form>
                </div>
                <a href="/sportswear-webstore/layout/client/card.php">
                    <i class="ri-shopping-cart-2-line nav__cart"></i>
                </a>
                <div class="nav__account-container">
                    <a class="nav__account" id="account"><i class="ri-account-circle-line"></i></a>
                    <?php if (isset($_SESSION['user'])): ?>
                        <span class="nav__username"><?php 
                            $email = $_SESSION['user']['username'];
                            echo explode('@', $email)[0]; 
                        ?></span>
                    <?php endif; ?>
                </div>
                <div class="nav__toggle" id="nav-toggle">
                    <i class="ri-menu-line nav__burger"></i>
                    <i class="ri-close-line nav__close"></i>
                </div>
            </div>
        </nav>
    </header>
    <div id="loginOverlay" class="login-overlay">
        <?php if (isset($_SESSION['user'])): ?>
            <div class="user-menu">
                <div class="user-info">
                    <i class="ri-user-circle-line"></i>
                    <span><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
                </div>
                <ul class="user-menu-list">
                    <?php if (isset($_SESSION['user']['roleid']) && $_SESSION['user']['roleid'] !== '05'): ?>
                        <li><a href="/sportswear-webstore/layout/admin/index.php"><i class="ri-admin-line"></i> Đi đến trang quản trị</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user']['roleid']) && (string)$_SESSION['user']['roleid'] === '05'): ?>
                        <li><a href="#" onclick="userProfileRedirect()"><i class="ri-user-settings-line"></i> Hồ sơ</a></li>
                        <li><a href="#" onclick="handleLogout(event)"><i class="ri-logout-box-line"></i> Đăng xuất</a></li>
                    <?php endif; ?>
                    
                </ul>
            </div>
        <?php else: ?>
            <?php              include __DIR__ . '/../layout/auth.php';?>
        <?php endif; ?>
    </div>
    <script>
        const showMenu = (toggleId, navId) => {
            const toggle = document.getElementById(toggleId),
                nav = document.getElementById(navId);
            toggle.addEventListener('click', () => {
                nav.classList.toggle('show-menu');
                toggle.classList.toggle('show-icon');
            });
        }
        showMenu('nav-toggle', 'nav-menu');

        document.getElementById('account').addEventListener('click', () => {
            document.getElementById('loginOverlay').style.display = 'flex';
        });

        document.getElementById('loginOverlay').addEventListener('click', (e) => {
            const overlay = document.getElementById('loginOverlay');
            if (e.target === overlay) {
                overlay.style.display = 'none';
            }
        });

        function handleLogout(event) {
            event.preventDefault();
            fetch('/sportswear-webstore/layout/auth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'submitLogout=1'
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Hello2");

                    if (data.success) window.location.href = '/sportswear-webstore/index.php';
                    else alert('Đăng xuất thất bại: ' + data.message);
                })
                .catch(error => alert('Đăng xuất thất bại: ' + error.message));
        }

        function userProfileRedirect() {
            const overlay = document.getElementById('loginOverlay');
            overlay.style.display = 'none';
            window.location.href = '/sportswear-webstore/index.php?page=profile';
        }
    </script>
</body>

</html>