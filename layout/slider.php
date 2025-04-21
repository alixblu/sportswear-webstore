<?php
// File PHP để hiển thị slider hình ảnh tự động, tăng chiều dài và căn giữa
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider Hình Ảnh Tự Động</title>
    <style>
        .slider-wrapper {
            width: 100%;
            max-width: 1600px; /* Tăng chiều rộng tối đa để hình ảnh dài hơn */
            margin: 0 auto;
            text-align: center;
        }
        .slider-container {
            width: 100%;
            max-width: 1600px; /* Tăng chiều rộng tối đa của khung hình */
            height: 300px;
            overflow: hidden;
            position: relative;
            margin: 0 auto;
        }
        .slides {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }
        .slides img {
            width: 100%;
            height: 100%;
            flex: 0 0 100%;
            object-fit: cover; /* Thay contain thành cover để hình ảnh lấp đầy khung */
            object-position: center;
        }
        .dots {
            margin-top: 10px;
        }
        .dot {
            height: 10px;
            width: 10px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            margin: 0 5px;
            cursor: pointer;
        }
        .dot.active {
            background-color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="slider-wrapper">
        <div class="slider-container">
            <div class="slides">
                <!-- Truy cập hình ảnh từ thư mục img -->
                <img src="img/adidas.svg" alt="Hình 1">
                <img src="img/adidas.svg" alt="Hình 2">
                <img src="https://media.istockphoto.com/id/624183176/vi/anh/ru%E1%BB%99ng-b%E1%BA%ADc-thang-%E1%BB%9F-mu-cang-ch%E1%BA%A3i-vi%E1%BB%87t-nam.jpg?s=612x612&w=0&k=20&c=UbNrn36xFBIff9yV3RDl5lPs3-kW-WQ_sSNMB1M3Trs=" alt="Hình 3">
            </div>
        </div>
        <div class="dots">
            <span class="dot" onclick="currentSlide(0)"></span>
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
        </div>
    </div>

    <script>
        let slideIndex = 0;
        const slides = document.querySelector('.slides');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = document.querySelectorAll('.slides img').length;

        function showSlides() {
            slideIndex++;
            if (slideIndex >= totalSlides) slideIndex = 0;
            slides.style.transform = `translateX(${-slideIndex * 100}%)`;
            
            // Cập nhật trạng thái dot
            dots.forEach(dot => dot.classList.remove('active'));
            dots[slideIndex].classList.add('active');
        }

        function currentSlide(index) {
            slideIndex = index;
            slides.style.transform = `translateX(${-slideIndex * 100}%)`;
            dots.forEach(dot => dot.classList.remove('active'));
            dots[slideIndex].classList.add('active');
        }

        // Tự động chuyển slide mỗi 3 giây
        setInterval(showSlides, 3000);

        // Đặt dot đầu tiên là active khi tải trang
        dots[0].classList.add('active');
    </script>
</body>
</html>