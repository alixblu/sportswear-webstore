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
      body {
         background-color: #F5F5FA;
      }

      .cart-container {
         max-width: 1200px;
         margin: 0 auto;
         padding-top: 90px;
         display: flex;
         justify-content: space-between;
         gap: 40px;
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

      .containerLeft {
         display: flex;
         flex-direction: column;
         gap: 20px;
         width: 25%;
      }

      .containerRight {
         width: 75%;
      }

      .couponCss {
         background-color: white;
         padding: 16px;
         border-radius: 10px;
         display: flex;
         flex-direction: column;
         gap: 10px;
      }

      .discount {
         background-color: white;
         padding: 16px;
         border-radius: 10px;
         display: flex;
         flex-direction: column;
         gap: 10px;
      }

      .freeship-note img {
         vertical-align: middle;
         margin-right: 6px;
      }

      .freeship-note {
         color: rgb(10, 104, 255);
         cursor: pointer;
      }

      .section-title {
         font-weight: bold;
      }

      .voucherItem {
         display: flex;
         gap: 10px;
         align-items: center;
         border-radius: 10px;
         padding: 10px 14px;
         background-color: white;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
         justify-content: space-between;

      }

      .voucherItem.active {
         outline: 2px solid rgb(10, 104, 255);

      }

      .voucher {
         display: flex;
         flex-direction: column;
         gap: 10px;
         box-sizing: border-box;
      }

      .apply-btn {
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
         background-color: rgba(0, 0, 0, 0.4);
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

      .titlePopup {
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
         box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
         transition: background-color 0.2s ease;
         margin-top: 15px;
      }

      .popup-overlay {
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background: rgba(0, 0, 0, 0.6);
         display: flex;
         align-items: center;
         justify-content: center;
         z-index: 9999;
      }

      .popup-content {
         background: white;
         padding: 20px;
         border-radius: 8px;
         width: 300px;
         max-width: 90%;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      }

      .titlePopup {
         display: flex;
         justify-content: space-between;
         margin-bottom: 15px;
         font-weight: bold;
      }

      .form-group {
         margin-bottom: 10px;
      }

      .form-group input {
         width: 100%;
         padding: 6px;
         box-sizing: border-box;
      }

      #paymentMethod,#addressSelect {
         width: 100%;
         padding: 8px 12px;
         font-size: 14px;
         border: 1px solid #ccc;
         border-radius: 5px;
         background-color: #fff;
         color: #333;
         appearance: none; 
         background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2210%22%20height%3D%225%22%20viewBox%3D%220%200%2010%205%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M0%200l5%205%205-5z%22%20fill%3D%22%23333%22/%3E%3C/svg%3E");
         background-repeat: no-repeat;
         background-position: right 10px center;
         background-size: 10px 5px;
         cursor: pointer;
         transition: border-color 0.3s ease;
      }

      #paymentMethod:focus ,#addressSelect:focus{
         outline: none;
         border-color: #007bff;
         box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
      }
   </style>
</head>

<body>
   <?php include __DIR__ . '/../header.php'; ?>
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
                     <image src="/img/trash.svg" />
                  </th>
               </tr>
            </thead>
            <tbody>

            </tbody>
         </table>
      </div>
      <div class="containerLeft">
         <div class="couponCss">
            <div class="section-title freeship-note"><img src="/img/coupon.svg" alt="">Khuyến Mãi</div>
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
</body>
<script src="../../JS/client/cartApi.js"></script>
<script src="../../JS/admin/coupon.js"></script>
<script src="../../JS/client/cartdetail.js"></script>
<script src="../../JS/admin/userApi.js"></script>

<script>
   loadCart()

   function loadCart() {
      let total = 0;
      getCartByUserId()
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
                           <img src="/img/products/${item.productID}.jpg" alt="${item.productName}">
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
                  deleteCell.innerHTML = `<img class="delete-icon" src="/img/trash.svg" alt="Xoá" onclick="deleteProduct(${item.detailID})">`;

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

            }
         })
         .catch(error => console.error('Lỗi khi lấy biến thể sản phẩm:', error));

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
               div.className = "voucherItem";
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
                  <option value="cash">Tiền mặt</option>
                  <option value="online">Trực tuyến</option>
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

      const addresses = userData.address
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

      closePopup();
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

</html>