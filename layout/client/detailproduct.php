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
   <link rel="stylesheet" href="../../css/detailproduct.css">

   <!-- font -->
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
   <style>
     
   </style>
</head>

<body>
   <?php include __DIR__ . '/../header.php'; ?>
   <main class="wrapperContent">
      <div class="wrapperImage">
         <div class="containerImageCss">
            <!-- <img src="/img/adidas.svg" alt="" >
                    <img src="/img/adidas.svg" alt="" >
                    <img src="/img/adidas.svg" alt="" >
                    <img src="/img/adidas.svg" alt="" > -->
         </div>
         <div class="mainImage">
            <img src="" alt="">
         </div>
      </div>
      <div class="detailProductCss">
         <h2 class="product-title">Adidas RUNFALCON 3.0</h2>

         <div class="rating-stock">
            <span class="stars">★★★★★</span>
            <span class="reviews">(Reviews)</span> |
            <span class="in-stock">In Stock</span>
         </div>

         <h3 class="price"></h3>

         <p class="description">
         </p>

         <hr>

         <div class="section">
            <strong>Colours:</strong>
            <div class="colors">

            </div>
         </div>

         <div class="section">
            <strong>Size:</strong>
            <div class="sizes">
            </div>
         </div>

         <div class="section quantity-cart">
            <button class="qty-btn">−</button>
            <input type="number" value="1" class="qty-input" />
            <button class="qty-btn">+</button>
            <button class="add-to-cart" onclick="themVaoGio()">Add To Cart</button>
         </div>
         <div class="delivery-box">
            <strong>🚚 Giao hàng miễn phí</strong><br>
            <small>Nhập mã bưu chính để kiểm tra khu vực giao hàng</small>
         </div>

         <div class="delivery-box">
            <strong>🔁 Chính Sách Bảo Hành</strong><br>
            <small>Trả hàng miễn phí trong 30 ngày. </small>
         </div>

      </div>

   </main>
</body>
<script src="../../JS/client/reviewApi.js"></script>
<script src="../../JS/client/cartApi.js"></script>

<script type="module">
   import * as product_api from '../../JS/admin/product/api.js'
   let selectedColor = null;
   let selectedSize = null;
   let variants = null;
   const urlParams = new URLSearchParams(window.location.search);
   const id = urlParams.get('id');
   if (id) {
      product_api.getProductById(id)
         .then(res => {
            const product = res
            document.querySelector(".product-title").innerText = product.name;

            document.querySelector(".description").innerText = product.description;

            const mainImg = document.querySelector(".mainImage img");
            mainImg.src = `/sportswear-webstore/img/products/product${product.ID}/${product.ID}.jpg`;
            mainImg.alt = product.name;
            mainImg.onerror = function() {
               this.src = '/sportswear-webstore/img/products/default.jpg';
            };

            if (product.rating) {
               document.querySelector(".stars").innerText = "★".repeat(product.rating) + "☆".repeat(5 - product.rating);
            }

         })
         .catch(error => console.error('Lỗi khi lấy dữ liệu sản phẩm:', error));

      product_api.getProductVariants(id)
         .then(res => {
            if (res.status === 200) {
               variants = res.data;
               renderColors(variants);
               renderSizes(variants);
               updatePriceStock(variants);
            }
         })
         .catch(error => console.error('Lỗi khi lấy biến thể sản phẩm:', error));

      const showBtn = document.querySelector(".reviews");

      showBtn.addEventListener("click", function(e) {
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

      colors.forEach((color, index) => {
         const span = document.createElement('span');
         span.className = 'color-option';
         span.dataset.color = color;
         span.style.backgroundColor = getColorCSS(color);

         if (index === 0) {
            selectedColor = color;
            span.classList.add('selected');
         }

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
      const filtered = selectedColor ?
         variants.filter(v => v.color === selectedColor) :
         variants;
      const sizes = [...new Set(filtered.map(v => v.size))];

      sizeContainer.innerHTML = '';
      sizes.forEach((size, index) => {
         const btn = document.createElement('button');
         btn.className = 'size-btn';
         btn.textContent = size;
         btn.dataset.size = size;

         if (index === 0) {
            selectedSize = size;
            btn.classList.add('active');
            updatePriceStock(variants);
         }

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
         priceEl.textContent = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
         }).format(match.price);
         stockEl.textContent = match.quantity > 0 ? `Còn ${match.quantity} sản phẩm` : 'Hết hàng';
      }
   }

   function getColorCSS(colorName) {
      switch (colorName.toLowerCase()) {
         case 'trắng':
            return '#fff';
         case 'đen':
            return '#000';
         case 'xanh':
            return '#00f';
         case 'đỏ':
            return '#f00';
         case 'vàng':
            return '#ff0';
         case 'xanh lá':
            return '#0f0';
         case 'cam':
            return '#f80';
         case 'tím':
            return '#800080';
         default:
            return '#ccc';
      }
   }



   document.addEventListener("DOMContentLoaded", function() {
      const qtyInput = document.querySelector(".qty-input");
      const minusBtn = document.querySelectorAll(".qty-btn")[0];
      const plusBtn = document.querySelectorAll(".qty-btn")[1];

      minusBtn.addEventListener("click", function() {
         let current = parseInt(qtyInput.value);
         if (current > 1) qtyInput.value = current - 1;
      });

      plusBtn.addEventListener("click", function() {
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

   function getVariantId(selectedColor, selectedSize) {
      const idvariant = variants.find(item => item.color === selectedColor && item.size === selectedSize);
      return idvariant ? idvariant.ID : null;
   }
   async function themVaoGio() {
      const activeSizeBtn = document.querySelector('.size-btn.active');
      const selectedSize = activeSizeBtn ? activeSizeBtn.getAttribute('data-size') : null;

      const activeColorBtn = document.querySelector('.color-option.selected');
      const selectedColor = activeColorBtn ? activeColorBtn.getAttribute('data-color') : null;

      const variantId = getVariantId(selectedColor, selectedSize);
      const quantity = document.querySelector('.qty-input').value;

      console.log(variantId)
      try {
         const result = await addCartDetail(variantId, quantity);

         if (result.status === 200) {
            alert('Đã thêm sản phẩm vào giỏ');
         } else {
            alert('Có lỗi xảy ra: ' + (result.data?.error || 'Không rõ lỗi'));
         }
      } catch (error) {
         const status = error.response?.status;
         const message = error.response?.data?.error || 'Lỗi không xác định';

         if (status === 400) {
            alert('Lỗi 400 - Bad Request: ' + message);
         } else if (status === 401) {
            alert('Bạn chưa đăng nhập. Vui lòng đăng nhập lại.');
         } else {
            alert('Đã xảy ra lỗi: ' + message);
         }
      }
   }
   window.themVaoGio = themVaoGio;

</script>

</html>