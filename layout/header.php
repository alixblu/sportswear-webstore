<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!--=============== REMIXICONS ===============-->
      <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

      <!--=============== CSS ===============-->
      <link rel="stylesheet" href="css/header.css">

   </head>
   <body>
      <!--=============== HEADER ===============-->
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
      <div id="loginOverlay" class="login-overlay">
         <?php include 'login_regis.php'; ?>
      </div>
      <script>
         const showMenu = (toggleId, navId) =>{
         const toggle = document.getElementById(toggleId),
         nav = document.getElementById(navId)
         
         toggle.addEventListener('click', () =>{
         // Add show-menu class to nav menu
         nav.classList.toggle('show-menu')
         
         // Add show-icon to show and hide the menu icon
         toggle.classList.toggle('show-icon')
         })
         }
         
         showMenu('nav-toggle','nav-menu')
         
         document.getElementById('account').addEventListener('click', function() {
            document.getElementById('loginOverlay').style.display = 'flex';
         });

         // Close overlay when clicking outside the form
         document.getElementById('loginOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                  this.style.display = 'none';
            }
         });
      </script>

   </body>
