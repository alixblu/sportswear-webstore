<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
        <title>Client</title>


        <!-- font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="./css/address.css">
        
        <style>
            .tabs {
                display: flex;
                border-bottom: 2px solid #ccc;
                margin-bottom: 1rem;
                }

            .tab-button {
                flex: 1;
                padding: 10px;
                text-align: center;
                background: none;
                border: none;
                outline: none;
                font-weight: bold;
                color: gray;
                border-bottom: 2px solid transparent;
                cursor: pointer;
            }

            .tab-button.active {
                color: #007bff;
                border-bottom-color: #007bff;
            }

            .tab-content {
                display: block;
            }

            .tab-content.hidden {
                display: none;
            }

            .product-container {
                display: flex;
                flex-wrap: wrap; 
                gap: 20px;
                justify-content: flex-start;
            }

            .product-card-review {
                width: 250px;
                box-sizing: border-box;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 16px;
                background-color: #fff;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            
            .containerReview{
                width: 80%;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <div class="containerReview">
            <div class="tabs">
                <button class="tab-button active" data-tab="pending">Chờ đánh giá</button>
                <button class="tab-button" data-tab="reviewed">Đã đánh giá</button>
            </div>

            <div class="tab-content" id="pending">
                <p id="pending-content">Đang tải...</p>
            </div>

            <div class="tab-content hidden" id="reviewed">
                <p id="reviewed-content">Đang tải...</p>
            </div>
        </div>
        <script src="/sportswear-webstore/JS/client/reviewApi.js"></script>
        <script>
            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.add('hidden'));
                    tab.classList.add('active');

                    const tabId = tab.getAttribute('data-tab');
                    document.getElementById(tabId).classList.remove('hidden');

                    if (tabId === 'pending') {
                        fetchPendingReviews();
                    } else if (tabId === 'reviewed') {
                        fetchReviewedProducts();
                    }
                });
            });

            function fetchPendingReviews() {
                const content = document.getElementById('pending-content');
                content.textContent = 'Đang tải...';

                getPendingReviews()
                    .then(response => {
                        console.log(response);
                        const data = response.data;

                        if (!Array.isArray(data) || data.length === 0) {
                            content.textContent = 'Bạn chưa có sản phẩm nào cần đánh giá. Mua sắm thôi!.';
                        } else {
                            content.innerHTML = `
                                <div class="product-container">
                                    ${data.map(item => `
                                        <div class="product-card-review">
                                            <img 
                                                src="/sportswear-webstore/img/products/product${item.productID}/${item.image}" 
                                                alt="${item.fullName}" 
                                                style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
                                            <div class="product-header">
                                                <strong>${item.fullName}</strong>
                                            </div>
                                            <div class="product-details">
                                                <p><strong>Màu:</strong> ${item.color || 'Không có'}</p>
                                                <p><strong>Size:</strong> ${item.size || 'Không có'}</p>
                                                <p><strong>Giá:</strong> ${Number(item.price).toLocaleString()}₫</p>
                                            </div>
                                            <button onclick="startReview(${item.productID})" style="margin-top: 10px; padding: 6px 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                                Đánh giá
                                            </button>
                                        </div>
                                    `).join('')}
                                </div>
                            `;

                        }
                    })
                    .catch(err => {
                        content.textContent = 'Lỗi khi tải dữ liệu.';
                        console.error(err);
                    });
            }




            function fetchReviewedProducts() {
                const content = document.getElementById('reviewed-content');
                content.textContent = 'Đang tải...';
                fetch('/api/reviews/reviewed')
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            content.textContent = 'Bạn chưa đánh giá sản phẩm nào.';
                        } else {
                            content.innerHTML = data.map(item => `<li>${item.productName}</li>`).join('');
                        }
                    })
                    .catch(err => {
                        content.textContent = 'Lỗi khi tải dữ liệu.';
                        console.error(err);
                    });
            }

            fetchPendingReviews();
        </script>
    </body>

</html>