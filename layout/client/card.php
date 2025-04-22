<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
        <title>Client</title>
        <link rel="stylesheet" href="../../css/footer.css">
        <link rel="stylesheet" href="../../css/header.css">

        <!-- font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body{
                background-color: white;
            }
    
            .cart-container {
                max-width: 1000px;
                margin: 0 auto;
                padding-top: 90px; 
            }

            .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            }

            .cart-table th,
            .cart-table td {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            }

            .product-info {
            display: flex;
            align-items: center;
            gap: 10px;
            }

            .product-info img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            }

            .coupon-checkout {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            }

            .coupon-section {
                border: 1px solid #ddd;
                padding: 20px;
                width: 300px;
            }

            .coupon-section input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            }

            .apply-btn {
                margin-top: 15px;
                width: 100%;
                padding: 10px;
                background-color: rgb(1, 127, 255);
                color: white;
                border: none;
                cursor: pointer;
            }

            .cart-total {
            border: 1px solid #ddd;
            padding: 20px;
            width: 300px;
            }

            .cart-total h3 {
            margin-top: 0;
            }

            .cart-total p {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            }

            .total {
            font-weight: bold;
            font-size: 1.1em;
            }

            .checkout-btn {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #e53935;
            color: white;
            border: none;
            cursor: pointer;
            }

        </style>
    </head>

    <body>
        <header class="header">
         <nav class="nav container">
            <div class="nav__data">
               <a href="#" class="nav__logo">
                  <i class="ri-store-2-fill"></i> Sportwear Store
               </a>
               
            </div>

            <!--=============== NAV MENU ===============-->
            <div class="nav__menu" id="nav-menu">
               <ul class="nav__list">
                  <li><a href="#" class="nav__link">Trang chủ</a></li>

                  <!--=============== DROPDOWN 1 ===============-->
                  <li class="dropdown__item">
                     <div class="nav__link">
                        Sản phẩm<i class="ri-arrow-down-s-line dropdown__arrow"></i>
                     </div>

                     <ul class="dropdown__menu">
                        <li>
                           <a href="#" class="dropdown__link">
                              <i class="ri-pie-chart-line"></i> Áo 
                           </a>                          
                        </li>

                        <li>
                           <a href="#" class="dropdown__link">
                              <i class="ri-arrow-up-down-line"></i> Quần
                           </a>
                        </li>

                        <li>
                           <a href="#" class="dropdown__link">
                              <i class="ri-arrow-up-down-line"></i> Giày
                           </a>
                        </li>

                        <!--=============== DROPDOWN SUBMENU ===============-->
                        <li class="dropdown__subitem">
                           <div class="dropdown__link">
                              <i class="ri-bar-chart-line"></i> Phụ kiện <i class="ri-add-line dropdown__add"></i>
                           </div>

                           <ul class="dropdown__submenu">
                              <li>
                                 <a href="#" class="dropdown__sublink">
                                    <i class="ri-file-list-line"></i> Bình nước
                                 </a>
                              </li>
      
                              <li>
                                 <a href="#" class="dropdown__sublink">
                                    <i class="ri-cash-line"></i> Vợt
                                 </a>
                              </li>
      
                              <li>
                                 <a href="#" class="dropdown__sublink">
                                    <i class="ri-refund-2-line"></i> Balo/Túi
                                 </a>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </li>
                  
                  <!--=============== DROPDOWN 2 ===============-->
                  <li class="dropdown__item">
                     <div class="nav__link">
                        Thương hiệu <i class="ri-arrow-down-s-line dropdown__arrow"></i>
                     </div>

                     <ul class="dropdown__menu">
                        <li>
                           <a href="#" class="dropdown__link">
                              <i class="ri-user-line"></i> Nike
                           </a>                          
                        </li>

                        <li>
                           <a href="#" class="dropdown__link">
                              <i class="ri-lock-line"></i> Adidas
                           </a>
                        </li>

                        <li>
                           <a href="#" class="dropdown__link">
                              <i class="ri-message-3-line"></i> Puma
                           </a>
                        </li>
                     </ul>
                  </li>

                  <li><a href="#" class="nav__link">Chính sách bảo hành</a></li>

                  <li><a href="#" class="nav__link">Liên hệ</a></li>

               </ul>
            </div>
            
            <div class="nav__tools">
               <div class="search-box" >
                  <i class="ri-search-line"></i>
               <input type="text"placeholder="Search...">
               </div>
               
               <i class="ri-shopping-cart-2-line nav__cart"></i>
               <a class="nav__account"  id="account">
                  <i class="ri-account-circle-line"></i>
               </a>
               <div class="nav__toggle" id="nav-toggle">
                  <i class="ri-menu-line nav__burger"></i>
                  <i class="ri-close-line nav__close"></i>
               </div>
            </div>
            
            </nav>
        </header>

        <div class="cart-container">
            <table class="cart-table">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                    <div class="product-info">
                        <img src="/img/adidas.svg" alt="Backpack NH Arpenaz 100">
                        <span>Backpack NH Arpenaz 100</span>
                    </div>
                    </td>
                    <td>$650</td>
                    <td>
                    01
                    </td>
                    <td>$650</td>
                </tr>
                <tr>
                    <td>
                    <div class="product-info">
                        <img src="/img/adidas.svg" alt="Tennis Shirt Mens Dri Fit">
                        <span>Tennis Shirt Mens Dri Fit</span>
                    </div>
                    </td>
                    <td>$550</td>
                    <td>
                    02
                    </td>
                    <td>$1100</td>
                </tr>
                </tbody>
            </table>

            <div class="coupon-checkout">
                <div class="coupon-section">
                    <h3>Khuyến Mãi</h3>
                    <p>Giảm 5% đơn đầu</p>
                    <p>Miễn Phí Vận Chuyển</p>
                <button class="apply-btn">Chọn Mã Giảm Giá</button>
                </div>

                <div class="cart-total">
                    <h3>Cart Total</h3>
                    <p>Subtotal: <span>$1750</span></p>
                    <p>Shipping: <span>Free</span></p>
                    <p class="total">Total: <span>$1750</span></p>
                    <button class="checkout-btn">Process to checkout</button>
                </div>
            </div>
        </div>
    </body>
    <script>
    </script>
</html>