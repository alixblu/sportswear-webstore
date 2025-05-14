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


            #review-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                display: none;
                animation: fadeIn 0.3s ease;
                }

                .review-overlay {
                position: absolute;
                top: 0;
                left: 0;
                background: rgba(0, 0, 0, 0.5);
                width: 100%;
                height: 100%;
                backdrop-filter: blur(2px);
                }

                .review-dialog {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 90%;
                max-width: 420px;
                background: #fff;
                padding: 25px 20px;
                border-radius: 12px;
                transform: translate(-50%, -50%);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
                animation: slideUp 0.3s ease;
                }

                .review-dialog h3 {
                margin-top: 0;
                margin-bottom: 15px;
                font-size: 1.2rem;
                color: #333;
                text-align: center;
                }

                .review-dialog select,
                .review-dialog textarea {
                width: 100%;
                font-size: 1rem;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 6px;
                margin-bottom: 15px;
                transition: border-color 0.2s;
                }

                .review-dialog select:focus,
                .review-dialog textarea:focus {
                outline: none;
                border-color: #007bff;
                }

                .review-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                margin-top: 10px;
                }

                .review-actions button {
                padding: 8px 14px;
                font-size: 0.95rem;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                transition: background 0.2s;
                }

                .review-actions button:first-child {
                background: #007bff;
                color: white;
                }

                .review-actions button:first-child:hover {
                background: #0056b3;
                }

                .review-actions button:last-child {
                background: #ccc;
                }

                .review-actions button:last-child:hover {
                background: #999;
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
        <div id="review-modal" style="display: none;">
            <div class="review-overlay"></div>
            <div class="review-dialog">
                <h3>Đánh giá sản phẩm</h3>
                <input type="hidden" id="review-product-id">
                <label>Số sao:</label>
                <select id="review-rating">
                <option value="5">5 sao</option>
                <option value="4">4 sao</option>
                <option value="3">3 sao</option>
                <option value="2">2 sao</option>
                <option value="1">1 sao</option>
                </select>
                <label>Bình luận:</label>
                <textarea id="review-comment" rows="4" placeholder="Nhận xét của bạn..."></textarea>
                <div class="review-actions">
                <button onclick="submitReview()">Gửi</button>
                <button onclick="closeReviewModal()">Hủy</button>
                </div>
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

            function startReview(productID) {
                document.getElementById('review-product-id').value = productID;
                document.getElementById('review-modal').style.display = 'block';
            }

            function closeReviewModal() {
                document.getElementById('review-modal').style.display = 'none';
            }

            async function submitReview() {
                const productId = document.getElementById('review-product-id').value;
                const rating = document.getElementById('review-rating').value;
                const comment = document.getElementById('review-comment').value;

                try {
                    await createReview(productId, rating, comment);
                    alert("Cảm ơn bạn đã đánh giá!");
                    closeReviewModal();
                    fetchPendingReviews(); 
                } catch (error) {
                    alert("Lỗi khi gửi đánh giá.");
                    console.error(error);
                }
            }

            fetchPendingReviews();
        </script>
    </body>

</html>