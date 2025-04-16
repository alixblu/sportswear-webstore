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
            main {
               padding-top: 90px; 
               width: 1160px;
               margin: 0 auto;
            }
            .wrapperContent{
                display: flex;
            }
            .wrapperImage{
                display: flex;
                flex: 1;
                max-width: 1000px;
                
            }
            .containerImageCss{
               display: flex;
               flex-direction: column  ;
               flex: 2;
            }
            .containerImageCss img {
               padding: 10px;
            }
            .mainImage {
                flex: 8;
            }
            .mainImage img {
               width: 100%;
               height: auto;
               object-fit: contain;
               display: block;
               padding: 10px;
            }
            .detailProductCss{
               max-width: 30%;
               padding: 10px;
            }
            .detailProductCss {
               font-family: Arial, sans-serif;
               max-width: 400px;
               margin: 0 auto;
            }

            .product-title {
               font-size: 20px;
               font-weight: bold;
            }

            .rating-stock {
               font-size: 14px;
               margin: 5px 0;
            }

            .stars {
               color: #FFA500;
            }

            .reviews {
               color: gray;
            }

            .in-stock {
               color: green;
            }

            .price {
               font-size: 24px;
               margin: 10px 0;
            }

            .description {
               font-size: 14px;
               color: #333;
            }

            .section {
               margin-top: 15px;
            }

            .colors {
               margin-top: 5px;
            }

            .color-option {
               display: inline-block;
               width: 20px;
               height: 20px;
               border-radius: 50%;
               border: 1px solid #ccc;
               margin-right: 8px;
            }

            .color-option.black {
               background-color: #000;
            }

            .color-option.pink {
               background-color: #f08a8a;
            }

            .sizes {
               margin-top: 5px;
            }

            .size-btn {
               padding: 5px 10px;
               margin: 2px;
               border: 1px solid #ccc;
               background: white;
               cursor: pointer;
            }

            .size-btn.active {
               background-color: #f44336;
               color: white;
               border: none;
            }

            .quantity-cart {
               margin-top: 10px;
               display: flex;
               align-items: center;
               gap: 5px;
            }

            .qty-btn {
               width: 30px;
               height: 30px;
               font-size: 18px;
               border: 1px solid #ccc;
               background: white;
               cursor: pointer;
            }

            .qty-input {
               width: 40px;
               text-align: center;
               font-size: 16px;
            }

            .add-to-cart {
               background-color: #f44336;
               color: white;
               border: none;
               padding: 8px 16px;
               cursor: pointer;
               margin-left: 10px;
            }

            .delivery-box {
               margin-top: 15px;
               padding: 10px;
               border: 1px solid #ccc;
               border-radius: 5px;
               font-size: 14px;
            }
            .color-option.selected {
               border: 2px solid orange;
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


        <main class="wrapperContent">
            <div class="wrapperImage">
                <div class="containerImageCss">
                    <img src="/img/adidas.svg" alt="" >
                    <img src="/img/adidas.svg" alt="" >
                    <img src="/img/adidas.svg" alt="" >
                    <img src="/img/adidas.svg" alt="" >
                </div>
                <div class="mainImage">
                    <img src="/img/adidas.svg" alt="" >
                </div>
            </div>
            <div class="detailProductCss">
               <h2 class="product-title">Adidas RUNFALCON 3.0</h2>

               <div class="rating-stock">
                  <span class="stars">★★★★☆</span>
                  <span class="reviews">(150 Reviews)</span> |
                  <span class="in-stock">In Stock</span>
               </div>

               <h3 class="price">$192.00</h3>

               <p class="description">
                  Freely challenge the trails! Maximize outdoor running with grip and support, whether on tarmac or trail.
               </p>

               <hr>

               <div class="section">
                  <strong>Colours:</strong>
                  <div class="colors">
                     <span class="color-option black"></span>
                     <span class="color-option pink"></span>
                  </div>
               </div>

               <div class="section">
                  <strong>Size:</strong>
                  <div class="sizes">
                        <button class="size-btn">XS</button>
                        <button class="size-btn">S</button>
                        <button class="size-btn active">M</button>
                        <button class="size-btn">L</button>
                        <button class="size-btn">XL</button>
                  </div>
               </div>

               <div class="section quantity-cart">
                  <button class="qty-btn">−</button>
                  <input type="number" value="2" class="qty-input" />
                  <button class="qty-btn">+</button>
                  <button class="add-to-cart">Add To Cart</button>
               </div>

               <div class="delivery-box">
                  <strong>🚚 Free Delivery</strong><br>
                  <small><a href="#">Enter your postal code for Delivery Availability</a></small>
               </div>

               <div class="delivery-box">
                  <strong>🔁 Return Delivery</strong><br>
                  <small>Free 30 Days Delivery Returns. <a href="#">Details</a></small>
               </div>
            </div>

        </main>
    <!-- <footer class="footer">
  	 <div class="footer-container">
  	 	<div class="row">
  	 		<div class="footer-col">
  	 			<h4>company</h4>
  	 			<ul>
  	 				<li><a href="#">about us</a></li>
  	 				<li><a href="#">our services</a></li>
  	 				<li><a href="#">privacy policy</a></li>
  	 				<li><a href="#">affiliate program</a></li>
  	 			</ul>
  	 		</div>
  	 		<div class="footer-col">
  	 			<h4>get help</h4>
  	 			<ul>
  	 				<li><a href="#">FAQ</a></li>
  	 				<li><a href="#">shipping</a></li>
  	 				<li><a href="#">returns</a></li>
  	 				<li><a href="#">order status</a></li>
  	 				<li><a href="#">payment options</a></li>
  	 			</ul>
  	 		</div>
  	 		<div class="footer-col">
  	 			<h4>online shop</h4>
  	 			<ul>
  	 				<li><a href="#">watch</a></li>
  	 				<li><a href="#">bag</a></li>
  	 				<li><a href="#">shoes</a></li>
  	 				<li><a href="#">dress</a></li>
  	 			</ul>
  	 		</div>
  	 		<div class="footer-col">
  	 			<h4>follow us</h4>
  	 			<div class="social-links">
  	 				<a href="#"><i class="fab fa-facebook-f"></i></a>
  	 				<a href="#"><i class="fab fa-twitter"></i></a>
  	 				<a href="#"><i class="fab fa-instagram"></i></a>
  	 				<a href="#"><i class="fab fa-linkedin-in"></i></a>
  	 			</div>
  	 		</div>
  	 	</div>
  	 </div>
  </footer> -->
    </body>
      <script>
      document.addEventListener("DOMContentLoaded", function () {
         const qtyInput = document.querySelector(".qty-input");
         const minusBtn = document.querySelectorAll(".qty-btn")[0];
         const plusBtn = document.querySelectorAll(".qty-btn")[1];

         minusBtn.addEventListener("click", function () {
               let current = parseInt(qtyInput.value);
               if (current > 1) qtyInput.value = current - 1;
         });

         plusBtn.addEventListener("click", function () {
               let current = parseInt(qtyInput.value);
               qtyInput.value = current + 1;
         });

         const sizeButtons = document.querySelectorAll(".size-btn");
         sizeButtons.forEach(button => {
               button.addEventListener("click", () => {
                  sizeButtons.forEach(btn => btn.classList.remove("active"));
                  button.classList.add("active");
               });
         });
      });

      document.addEventListener("DOMContentLoaded", function () {
        const colorOptions = document.querySelectorAll(".color-option");

        colorOptions.forEach(option => {
            option.addEventListener("click", () => {
                colorOptions.forEach(opt => opt.classList.remove("selected"));
                option.classList.add("selected");
            });
        });
    });
   </script>

</html>