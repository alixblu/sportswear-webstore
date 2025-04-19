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
               cursor: pointer;
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
            .qty-input::-webkit-outer-spin-button,
            .qty-input::-webkit-inner-spin-button {
               -webkit-appearance: none;
               margin: 0;
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
            #portal-root .overlay {
               position: fixed;
               top: 0;
               left: 0;
               right: 0;
               bottom: 0;
               background-color: rgba(0, 0, 0, 0.5);
               display: flex;
               justify-content: center;
               align-items: center;
               z-index: 1000;
            }

            .review-box {
               background: white;
               padding: 20px;
               border-radius: 12px;
               width: 450px;
               max-width: 90%;
               max-height: 80%;
               overflow-y: auto;
               box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
               animation: fadeIn 0.3s ease-in-out;
            }

            .review-box h2 {
               margin-top: 0;
               text-align: center;
            }

            .review-list {
               list-style: none;
               padding: 0;
            }

            .review-list li {
               margin-bottom: 15px;
               padding-bottom: 10px;
               border-bottom: 1px solid #ddd;
            }

            .review-list strong {
               display: block;
               margin-bottom: 4px;
               color: #333;
            }

            .review-list p {
               margin: 0;
               color: #555;
            }

            .close-review {
               margin-top: 20px;
               background-color: #f44336;
               color: white;
               border: none;
               padding: 8px 16px;
               border-radius: 6px;
               cursor: pointer;
               float: right;
            }

            @keyframes fadeIn {
               from { opacity: 0; transform: scale(0.95); }
               to { opacity: 1; transform: scale(1); }
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
                  <span class="reviews">(Reviews)</span> |
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
                  <input type="number" value="1" class="qty-input" />
                  <button class="qty-btn">+</button>
                  <button class="add-to-cart">Add To Cart</button>
               </div>
               <div class="delivery-box">
                  <strong>🚚 Giao hàng miễn phí</strong><br>
                  <small>Nhập mã bưu chính để kiểm tra khu vực giao hàng</small>
               </div>

               <div class="delivery-box">
                  <strong>🔁 Trả hàng miễn phí</strong><br>
                  <small>Trả hàng miễn phí trong 30 ngày. </small>
               </div>

            </div>

        </main>
    </body>
      <script src="../../JS/admin/product.js"></script>
      <script src="../../JS/client/reviewApi.js"></script>

      <script>
      let selectedColor = null;
      let selectedSize = null;

      const urlParams = new URLSearchParams(window.location.search);
      const id = urlParams.get('id');
      if (id) {
         getProductById(id)
            .then(res => {
               const product = res.data;

               document.querySelector(".product-title").innerText = product.name;

               document.querySelector(".description").innerText = product.description;

               document.querySelector(".price").innerText = `${product.markup_percentage}% markup`;

               const stockStatus = document.querySelector(".in-stock");
               if (product.status === "in_stock" && product.stock > 0) {
                  stockStatus.innerText = "Còn hàng";
                  stockStatus.classList.remove("out-of-stock");
               } else {
                  stockStatus.innerText = "Hết hàng";
                  stockStatus.classList.add("out-of-stock");
               }

               const mainImg = document.querySelector(".mainImage img");
               if (product.image && product.image !== "null") {
                  mainImg.src = product.image;
               }

               if (product.rating) {
                  document.querySelector(".stars").innerText = "★".repeat(product.rating) + "☆".repeat(5 - product.rating);
               }

            })
            .catch(error => console.error('Lỗi khi lấy dữ liệu sản phẩm:', error));

            getProductVariants(id)
               .then(res => {
                  if (res.status === 200) {
                     const variants = res.data;
                     renderColors(variants);
                     renderSizes(variants);
                     updatePriceStock(variants);
                  }
               })
               .catch(error => console.error('Lỗi khi lấy biến thể sản phẩm:', error));

               const showBtn = document.querySelector(".reviews");

               showBtn.addEventListener("click", function (e) {
                  getReviewsByProductId(id)
                     .then(reviews => {
                        const portalRoot = document.createElement("div");
                        portalRoot.id = "portal-root";

                        const reviewItems = reviews.data.map(review => {
                           return `
                              <li>
                                 <strong>Người dùng #${review.userAccID}</strong>
                                 <p>⭐ Đánh giá: ${review.rating} sao</p>
                                 <p>${review.commentContent ? review.commentContent : "Không có nhận xét."}</p>
                                 <small>${review.createdAt}</small>
                              </li>
                           `;
                        }).join("");

                        portalRoot.innerHTML = `
                           <div class="overlay">
                              <div class="review-box">
                                 <h2>Đánh Giá Khách Hàng</h2>
                                 <ul class="review-list">
                                    ${reviewItems || "<li>Chưa có đánh giá nào cho sản phẩm này.</li>"}
                                 </ul>
                                 <button class="close-review">Đóng</button>
                              </div>
                           </div>
                        `;

                        document.body.appendChild(portalRoot);

                        portalRoot.querySelector(".close-review").addEventListener("click", () => {
                           portalRoot.remove();
                        });
                     })
                     .catch(error => {
                        console.error('Lỗi khi lấy đánh giá:', error.message);
                     });
               });


      }

      function renderColors(variants) {
         const colorContainer = document.querySelector('.colors');
         const colors = [...new Set(variants.map(v => v.color))];
         colorContainer.innerHTML = '';

         colors.forEach(color => {
            const span = document.createElement('span');
            span.className = 'color-option';
            span.dataset.color = color;
            span.style.backgroundColor = getColorCSS(color);
            span.addEventListener('click', () => {
               selectedColor = color;
               document.querySelectorAll('.color-option').forEach(option => option.classList.remove('selected'));
               span.classList.add('selected');
               renderSizes(variants);
               updatePriceStock(variants);
            });
            colorContainer.appendChild(span);
         });
      }


      function renderSizes(variants) {
         const sizeContainer = document.querySelector('.sizes');
         const filtered = selectedColor
            ? variants.filter(v => v.color === selectedColor)
            : variants;
         const sizes = [...new Set(filtered.map(v => v.size))];

         sizeContainer.innerHTML = '';
         sizes.forEach(size => {
            const btn = document.createElement('button');
            btn.className = 'size-btn';
            btn.textContent = size;
            btn.dataset.size = size;
            btn.addEventListener('click', () => {
               selectedSize = size;
               updatePriceStock(variants);
               document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
               btn.classList.add('active');
            });
            sizeContainer.appendChild(btn);
         });
      }

      function updatePriceStock(variants) {
         const priceEl = document.querySelector('.price');
         const stockEl = document.querySelector('.in-stock');

         const match = variants.find(v =>
            (!selectedColor || v.color === selectedColor) &&
            (!selectedSize || v.size === selectedSize)
         );

         if (match) {
            priceEl.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(match.price);
            stockEl.textContent = match.quantity > 0 ? `Còn ${match.quantity} sản phẩm` : 'Hết hàng';
         }
      }

      function getColorCSS(colorName) {
         switch (colorName.toLowerCase()) {
            case 'trắng': return '#fff';
            case 'đen': return '#000';
            default: return '#ccc';
         }
      }


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
      
   </script>
</html>