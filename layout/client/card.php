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
                background-color: #F5F5FA;
            }
    
            .cart-container {
               max-width: 1200px;
               margin: 0 auto;
               padding-top: 90px; 
               display: flex;
               justify-content: space-between;
               gap:40px;
            }

            .cart-table {
               width: 100%;
               border-collapse: collapse;
               margin-bottom: 20px;
               background-color: white;
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
            .containerLeft{
               display: flex;
               flex-direction: column;
               gap: 20px;
               width: 25%;
            }
            .containerRight{
               width: 75%;
            }
            .couponCss{
               background-color:white;
               padding: 16px;
               border-radius: 10px;
               display: flex;
               flex-direction: column;
               gap:10px;
            }
            .discount{
               background-color:white;
               padding: 16px;
               border-radius: 10px;
               display: flex;
               flex-direction: column;
               gap:10px;
            }
            .freeship-note img {
               vertical-align: middle;
               margin-right: 6px;
            }
            .freeship-note {
               color: rgb(10, 104, 255);
               cursor: pointer;
            }
            .section-title{
               font-weight: bold;
            }
            .voucherItem{
               display: flex;
               gap:10px;
               align-items: center;
               border-radius: 10px;
               padding: 10px 14px;
               background-color: white;
               box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
               justify-content: space-between;

            }
            .voucherItem.active {
               outline: 2px solid  rgb(10, 104, 255);

            }
            .voucher{
               display: flex;
               flex-direction: column;
               gap: 10px;
               box-sizing: border-box;
            }
            .apply-btn{
               background-color: #0074e8;
               color: white;
               border: none;
               border-radius: 6px;
               padding: 6px 12px;
               cursor: pointer;
               white-space: nowrap; 
            }
            .btn-buy {
               width: 100%;
               background: #ff424e;
               color: white;
               padding: 12px;
               font-size: 16px;
               border: none;
               border-radius: 8px;
               cursor: pointer;
            }
            .delete-icon {
               width: 20px;
               height: 20px;
               cursor: pointer;
               transition: transform 0.2s ease;
            }
            .popup-overlay {
               position: fixed;
               top: 0;
               left: 0;
               width: 100%;
               height: 100%;
               background-color: rgba(0,0,0,0.4);
               display: flex;
               justify-content: center;
               align-items: center;
               z-index: 9999;
            }

            .popup-content {
               background: white;
               padding: 20px 30px;
               border-radius: 10px;
               box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
               max-width: 400px;
               width: 90%;
               text-align: center;
            }
            .titlePopup{
               display: flex;
               justify-content: space-between;
            }
            .btn-xong {
               background-color: #007bff; 
               color: white;
               border: none;
               padding: 10px 0;
               width: 100%;
               border-radius: 6px;
               font-size: 16px;
               font-weight: 500;
               cursor: pointer;
               box-shadow: 0 2px 4px rgba(0,0,0,0.1);
               transition: background-color 0.2s ease;
               margin-top: 15px;
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
            <div class="containerRight">
               <table class="cart-table">
                  <thead>
                  <tr>
                     <th>Product</th>
                     <th>Price</th>
                     <th>Quantity</th>
                     <th>Subtotal</th>
                     <th><image src="/img/trash.svg"/></th> 
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
                     <td><img class="delete-icon" src="/img/trash.svg" alt="Xoá"></td>
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
                     <td><img class="delete-icon" src="/img/trash.svg" alt="Xoá"></td>
                  </tr>
                  </tbody>
               </table>
            </div>
            <div class="containerLeft">
               <div class="couponCss">
                  <div class="section-title">Khuyến Mãi</div>
                  <div class="voucher">
                     <div class="voucherItem">
                        <span>Giảm 15% tối đa 70K</span>
                        <button class="apply-btn" onclick="toggleApply(this)">Áp Dụng</button>
                     </div>
                  </div>
                  <div class="freeship-note" onclick="showPopup()">
                   <img src="/img/coupon.svg" alt=""> Xem Thêm Mã Giảm
                  </div>
               </div>
               <div class="discount">
                  <div class="info-line">
                     <span>Tổng tiền hàng</span>
                     <span>429.000₫</span>
                  </div>
                  <div class="info-line">
                     <span>Giảm giá trực tiếp</span>
                     <span>-110.000₫</span>
                  </div>
                  <div class="info-line">
                     <span>Mã khuyến mãi</span>
                     <span>-20.000₫</span>
                  </div>
                  <br>
                  <div class="total">Tổng tiền thanh toán: 299.000₫</div>

                  <button class="btn-buy">Mua Hàng</button>
               </div>
            </div>
         </div>
      </body>
   <script src="../../JS/client/cartApi.js"></script>
    <script>
      loadCart()
      function loadCart(){
         getCartByUserId(10)
            .then(res => {
               if (res.status === 200) {
                  const cartItems = res.data; 
                  const cartTableBody = document.querySelector(".cart-table tbody"); 
                  cartTableBody.innerHTML = "";

                  cartItems.forEach(item => {
                     const row = document.createElement("tr");

                     const productCell = document.createElement("td");
                     productCell.innerHTML = `
                        <div class="product-info">
                           <img src="/img/adidas.svg" alt="${item.productName}">
                           <span>${item.productName}</span>
                        </div>
                     `;

                     const priceCell = document.createElement("td");
                     priceCell.innerHTML = item.productPrice;

                     const quantityCell = document.createElement("td");
                     quantityCell.innerHTML = item.quantity < 10 ? `0${item.quantity}` : item.quantity;

                     const subtotalCell = document.createElement("td");
                     subtotalCell.innerHTML = `$${650 * item.quantity}`; 

                     const deleteCell = document.createElement("td");
                     deleteCell.innerHTML = `<img class="delete-icon" src="/img/trash.svg" alt="Xoá">`;

                     row.appendChild(productCell);
                     row.appendChild(priceCell);
                     row.appendChild(quantityCell);
                     row.appendChild(subtotalCell);
                     row.appendChild(deleteCell);

                     cartTableBody.appendChild(row);
                  });
               }
            })
            .catch(error => console.error('Lỗi khi lấy biến thể sản phẩm:', error));

      }
      function toggleApply(button) {
         const voucherItem = button.closest('.voucherItem');
         const isActive = voucherItem.classList.toggle('active');

         button.textContent = isActive ? 'Bỏ Chọn' : 'Áp Dụng';
      }
      function showPopup() {
         const overlay = document.createElement('div');
         overlay.classList.add('popup-overlay');

         const popup = document.createElement('div');
         popup.classList.add('popup-content');
         popup.innerHTML = `
            <div class="titlePopup">
               <div>Khuyến Mãi</div>
               <div onclick="closePopup()" style="cursor: pointer;">X</div>
            </div>
            <div class="section-title">Khuyến Mãi</div>
            <div class="voucher">
               <div class="voucherItem">
                  <span>Giảm 15% tối đa 70K</span>
                  <button class="apply-btn" onclick="toggleApplyForm(this)">Áp Dụng</button>
               </div>
            </div>
            <button class="btn-xong" onclick="closePopup()">Xong</button>
         `;
         overlay.appendChild(popup);
         document.body.appendChild(overlay);
         }

      function closePopup() {
         const overlay = document.querySelector('.popup-overlay');
         if (overlay) overlay.remove();
      }
      function toggleApplyForm(button) {
         const voucher = document.querySelector('.voucher');
         const voucherItem = button.closest('.voucherItem');
         const isActive = voucherItem.classList.toggle('active');

         if(isActive==true){
            button.textContent = 'Bỏ Chọn';
            voucher.innerHTML = `
            <div class="voucherItem active">
               <span>Giảm 15% tối đa 70K</span>
               <button class="apply-btn" onclick="toggleApplyForm(this)">Bỏ Chọn</button>
            </div>`;
         }
         if(isActive==false){
            button.textContent = 'Áp Dụng';
            voucher.innerHTML = `
            <div class="voucherItem">
               <span>Giảm 15% tối đa 70K</span>
               <button class="apply-btn" onclick="toggleApplyForm(this)">Áp Dụng</button>
            </div>`;
         }

        
      }
    </script>
</html>