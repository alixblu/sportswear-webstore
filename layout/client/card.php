<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Client</title>
   <link rel="stylesheet" href="../../css/footer.css">
   <link rel="stylesheet" href="../../css/header.css">
   <link rel="stylesheet" href="../../css/cart.css">

   <!-- font -->
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
   <style>
  

   </style>
</head>
<body>
   <?php include __DIR__ . '/../header.php'; ?>
   <div class="mainContainer">
      <div class="cart-container">
         <div class="containerRight">
            <table class="cart-table">
               <thead>
                  <tr>
                     <th>Product</th>
                     <th>Price</th>
                     <th>Quantity</th>
                     <th>Subtotal</th>
                     <th>
                        <image src="/sportswear-webstore/img/trash.svg" />
                     </th>
                  </tr>
               </thead>
               <tbody>

               </tbody>
            </table>
         </div>
         <div class="containerLeft">
            <div class="couponCss">
               <div class="section-title freeship-note"><img src="/sportswear-webstore/img/coupon.svg" alt="">Khuyến Mãi</div>
               <div class="voucher">
                  <div class="voucherItem">
                     <!-- <span>Giảm 15% tối đa 70K</span>
                     <button class="apply-btn" onclick="toggleApply(this)">Áp Dụng</button> -->
                  </div>
               </div>
               <!-- <div class="freeship-note" onclick="showPopup()">
                     <img src="/img/coupon.svg" alt=""> Xem Thêm Mã Giảm
                     </div> -->
            </div>
            <div class="discount">
               <div class="info-line">
                  <span>Tổng tiền hàng</span>
                  <span class="summary-price">429.000₫</span>
               </div>
               <div class="info-line">
                  <span>Giảm giá trực tiếp</span>
                  <span>-0.000₫</span>
               </div>
               <div class="info-line">
                  <span>Mã khuyến mãi</span>
                  <span class="summary-coupon">-0.000₫</span>
               </div>
               <br>
               <div class="summary-total">Tổng tiền thanh toán: 299.000₫</div>

               <button class="btn-buy">Mua Hàng</button>
            </div>
         </div>
      </div>
   </div>
<script src="/sportswear-webstore/JS/client/cartApi.js"></script>
<script src="/sportswear-webstore/JS/admin/coupon.js"></script>
<script src="/sportswear-webstore/JS/client/cartdetail.js"></script>
<script src="/sportswear-webstore/JS/admin/userApi.js"></script>
<script src="/sportswear-webstore/JS/admin/order.js"></script>

<script>
   loadCart()

   function loadCart() {
      let total = 0;
      getCartByUserId()
         .then(res => {
            if (res.status === 200) {
               const cartItems = res.data;
               const cartTableBody = document.querySelector(".cart-table tbody");
               const cartContainer = document.querySelector(".cart-container");

               cartTableBody.innerHTML = "";

               
               if (!cartItems || cartItems.length === 0) {
                  cartContainer.innerHTML = `
                     <div class="empty-cart-message">
                        <image src="/sportswear-webstore/img/emptycart.png" />
                        <p>Giỏ hàng của bạn đang trống!</p>
                     </div>
                  `;
               }


               cartItems.forEach(item => {
                  const row = document.createElement("tr");

                  const productCell = document.createElement("td");
                  productCell.innerHTML = `
                        <div class="product-info">
                           <img src="/sportswear-webstore/img/products/${item.ID}.jpg" alt="${item.productName}">
                           <span>${item.productName}</span>
                        </div>
                     `;

                  const priceCell = document.createElement("td");
                  priceCell.innerHTML = item.productPrice;

                  const quantityCell = document.createElement("td");
                  quantityCell.innerHTML = item.quantity < 10 ? `0${item.quantity}` : item.quantity;

                  const subtotalCell = document.createElement("td");
                  subtotalCell.innerHTML = `${item.quantity * item.productPrice}`;

                  const deleteCell = document.createElement("td");
                  deleteCell.innerHTML = `<img class="delete-icon" src="/sportswear-webstore/img/trash.svg" alt="Xoá" onclick="deleteProduct(${item.detailID})">`;

                  row.appendChild(productCell);
                  row.appendChild(priceCell);
                  row.appendChild(quantityCell);
                  row.appendChild(subtotalCell);
                  row.appendChild(deleteCell);

                  cartTableBody.appendChild(row);
               });

               cartItems.forEach(item => {
                  total += Number(item.productPrice) * Number(item.quantity);
               });

               const priceElement = document.querySelector(".summary-price");
               priceElement.innerText = formatCurrency(total);

               const summaryElement = document.querySelector(".summary-total");
               summaryElement.innerText = 'Tổng tiền thanh toán ' + formatCurrency(total);

               getCouponByUserId()
                  .then(result => {
                     const coupons = result.data;
                     const container = document.querySelector(".voucher");
                     container.innerHTML = "";

                     if (!coupons || coupons.length === 0) {
                        container.innerHTML = "<div class='voucherItem'>Không có mã khuyến mãi</div>";
                        return;
                     }

                     coupons.forEach(coupon => {
                        const div = document.createElement("div");
                        div.className = `voucherItem voucher-${coupon.ID}`; 
                        div.innerHTML = `
                              <span>${coupon.name}   </span>
                              <button class="apply-btn" onclick="toggleApply(this, ${total},${coupon.percent})">Áp Dụng</button>
                           `;
                        container.appendChild(div);
                     });
                  })
                  .catch(error => {
                     console.error('Lỗi khi gọi API:', error);
                  });

            }else{
               alert("Vui Lòng Đăng Nhập")
               window.location.href = '/sportswear-webstore/index.php';
            }
         })
         .catch(error => console.error('Lỗi khi lấy biến thể sản phẩm:', error));
   }

   const formatCurrency = (value) => {
      return Number(value).toLocaleString('vi-VN') + '₫';
   };

   function deleteProduct($id) {
      deleteCartDetail($id)
      loadCart()
   }

   function toggleApply(button, total, percent) {
      const voucherItem = button.closest('.voucherItem');
      const isActive = voucherItem.classList.toggle('active');


      if (isActive == true) {
         button.textContent = 'Bỏ Chọn';
         const couponElement = document.querySelector(".summary-coupon");
         couponElement.innerText = formatCurrency(-total * percent / 100);

         const summaryElement = document.querySelector(".summary-total");
         summaryElement.innerText = 'Tổng tiền thanh toán ' + formatCurrency(total - (total * percent / 100));
      }
      if (isActive == false) {
         button.textContent = 'Áp Dụng';
         const couponElement = document.querySelector(".summary-coupon");
         couponElement.innerText = '-0.000₫';


         const summaryElement = document.querySelector(".summary-total");
         summaryElement.innerText = 'Tổng tiền thanh toán ' + formatCurrency(total);
      }


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

      if (isActive == true) {
         button.textContent = 'Bỏ Chọn';
         voucher.innerHTML = `
            <div class="voucherItem active">
               <span>Giảm 15% tối đa 70K</span>
               <button class="apply-btn" onclick="toggleApplyForm(this)">Bỏ Chọn</button>
            </div>`;
      }
      if (isActive == false) {
         button.textContent = 'Áp Dụng';
         voucher.innerHTML = `
            <div class="voucherItem">
               <span>Giảm 15% tối đa 70K</span>
               <button class="apply-btn" onclick="toggleApplyForm(this)">Áp Dụng</button>
            </div>`;
      }


   }

   async function showCustomerInfoPopup() {
      const result = await getInfo();
      const userData = result.data;

      const overlay = document.createElement('div');
      overlay.classList.add('popup-overlay');

      const popup = document.createElement('div');
      popup.classList.add('popup-content');
      popup.innerHTML = `
         <div class="titlePopup">
            <div>Nhập Thông Tin Khách Hàng</div>
            <div onclick="closePopup()" style="cursor: pointer;">X</div>
         </div>
         <form id="customerForm" onsubmit="submitCustomerInfo(event)">
            <div class="form-group">
               <label for="name">Họ tên:</label>
               <input type="text" id="name" name="name" required />
            </div>
            <div class="form-group">
               <label for="address">Địa chỉ:</label>
               <select id="addressSelect" name="address" required onchange="handleAddressChange()">

               </select>

               <input type="text" id="newAddressInput" placeholder="Nhập địa chỉ mới" style="display: none; margin-top: 8px;" />
            </div>
            <div class="form-group">
               <label for="phone">Số điện thoại:</label>
               <input type="tel" id="phone" name="phone" required pattern="\\d{10,11}" />
            </div>
            <div class="form-group">
               <label for="paymentMethod">Hình thức chi trả:</label>
               <select id="paymentMethod" name="paymentMethod" required>
                  <option value="1">Tiền mặt</option>
                  <option value="2">Trực tuyến</option>
               </select>
            </div>
            <button type="submit" class="btn-xong">Đặt Hàng</button>
         </form>
      `;

      overlay.appendChild(popup);
      document.body.appendChild(overlay);

      document.getElementById('name').value = userData.fullname || '';
      document.getElementById('phone').value = userData.phone || '';

      addressSelect.innerHTML = '';

      const addresses = userData.address ? [userData.address] : [];
      const placeholderOption = new Option('-- Chọn địa chỉ --', '', true, false);
      addressSelect.appendChild(placeholderOption);

      if (userData.address && !addresses.includes(userData.address)) {
         addresses.unshift(userData.address);
      }

      for (const addr of addresses) {
         const option = new Option(addr, addr, false, userData.address === addr);
         addressSelect.appendChild(option);
      }  

      const addNewOption = new Option('+ Thêm địa chỉ mới...', 'new');
      addressSelect.appendChild(addNewOption);
      
   }

   document.querySelector('.btn-buy').addEventListener('click', function() {
      showCustomerInfoPopup()
   });

   function submitCustomerInfo(event) {
      event.preventDefault();

      const name = document.getElementById('name').value;
      const phone = document.getElementById('phone').value;
      const paymentMethod = document.getElementById('paymentMethod').value;

      const addressSelect = document.getElementById('addressSelect');
      let address = addressSelect.value;

      if (address === 'new') {
         const newAddress = document.getElementById('newAddressInput').value.trim();
         if (newAddress) {
            address = newAddress;

            const exists = Array.from(addressSelect.options).some(opt => opt.value === newAddress);
            if (!exists) {
               const option = new Option(newAddress, newAddress, true, true);
               addressSelect.insertBefore(option, addressSelect.lastElementChild); 
            }

            addressSelect.value = newAddress;
            document.getElementById('newAddressInput').value = '';
            document.getElementById('newAddressInput').style.display = 'none';
         } else {
            alert("Vui lòng nhập địa chỉ mới.");
            return;
         }
      }

      console.log({ name, phone, address, paymentMethod });

      const activeVoucher = document.querySelector('.voucherItem.active');

      let voucherId =null
      if (activeVoucher) {
         const classList = Array.from(activeVoucher.classList);
         const voucherClass = classList.find(cls => cls.startsWith('voucher-'));
         voucherId = voucherClass ? voucherClass.replace('voucher-', '') : null;
      }
      createOrder(name, address, phone, voucherId, paymentMethod)
      .then(response => {
         const orderId = response.data.order_id;

         closePopup();
         showInvoiceDetailPopup(orderId);
      })
      .catch(error => {
         console.error('Lỗi tạo đơn hàng:', error.message);
      });
   }


   async function showInvoiceDetailPopup(orderId) {
      getOrderDetails(orderId)
         .then(data => {
               console.log('Chi tiết đơn hàng:', data);
               const orderList = data.data;
               const firstItem = orderList[0]; // Dùng để lấy thông tin người nhận, vì giống nhau

               const overlay = document.createElement('div');
               overlay.classList.add('popup-overlay');

               const popup = document.createElement('div');
               popup.classList.add('popup-content');

               // Render tất cả sản phẩm
               let productsHTML = '';
               let totalAmount = 0;

               orderList.forEach(item => {
                  productsHTML += `
                     <div class="invoice-items">
                           <p><strong>Sản phẩm:</strong> ${item.productName}</p>
                           <p><strong>Số lượng:</strong> ${item.quantity}</p>
                           <p><strong>Tổng tiền sản phẩm:</strong> ${Number(item.productTotal).toLocaleString()}₫</p>
                           <hr/>
                     </div>
                  `;
                  totalAmount += Number(item.productTotal);
               });

               // Kiểm tra mã giảm giá
               const couponSection = firstItem.couponName ? `
                  <p><strong>Mã giảm giá:</strong> ${firstItem.couponName}</p>
                  <p><strong>Giảm giá:</strong> ${firstItem.couponPercent}%</p>
                  <p><strong>Thời gian hiệu lực:</strong> ${firstItem.couponDuration} ngày</p>
                  <p><strong>Trạng thái:</strong> ${firstItem.couponStatus === 'active' ? 'Kích hoạt' : 'Không kích hoạt'}</p>
               ` : '';

               // Tính tổng sau giảm (nếu có)
               let finalTotal = totalAmount;
               if (firstItem.couponPercent && firstItem.couponStatus === 'active') {
                  finalTotal = totalAmount * (1 - firstItem.couponPercent / 100);
               }

               popup.innerHTML = `
                  <div class="titlePopup">
                     <div>Chi Tiết Hóa Đơn</div>
                     <div onclick="closePopup()" style="cursor: pointer;">X</div>
                  </div>
                  <div class="invoice-section">
                     <p><strong>Họ tên:</strong> ${firstItem.receiverName || '---'}</p>
                     <p><strong>Địa chỉ:</strong> ${firstItem.address || '---'}</p>
                     <p><strong>Số điện thoại:</strong> ${firstItem.phone || '---'}</p>
                     <p><strong>Hình thức thanh toán:</strong> ${firstItem.paymentMethod === 'online' ? 'Trực tuyến' : 'Tiền mặt'}</p>
                     <hr/>
                     ${productsHTML}
                     ${couponSection}
                     <p class="total-price"><strong>Tổng cộng:</strong> <span id="totalPrice">${finalTotal.toLocaleString()}₫</span></p>
                     <button onclick="closePopup()" class="btn-xong">Đóng</button>
                  </div>
               `;

               overlay.appendChild(popup);
               document.body.appendChild(overlay);
         })
         .catch(error => {
               console.error('Lỗi:', error.message);
         });
   }


   function handleAddressChange() {
   const addressSelect = document.getElementById('addressSelect');
   const newAddressInput = document.getElementById('newAddressInput');

   if (addressSelect.value === 'new') {
      newAddressInput.style.display = 'block';
      newAddressInput.required = true;
   } else {
      newAddressInput.style.display = 'none';
      newAddressInput.required = false;
   }
}
</script>
</body>

</html>