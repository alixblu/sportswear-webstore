<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout Page</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      background: #fff;
    }

    .container {
      display: flex;
      gap: 50px;
      max-width: 1000px;
      margin: auto;
      align-items: center;

    }

    .billing, .summary {
      flex: 1;
    }

    h2 {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: 500;
    }

    input[type="text"], input[type="email"], input[type="tel"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .checkbox {
      display: flex;
      align-items: center;
      margin-top: 20px;
    }

    .checkbox input {
      margin-right: 10px;
    }

    .order-items {
      margin-bottom: 20px;
 

    }

    .order-items div {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;

    }

    .totals {
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }

    .totals div {
      display: flex;
      justify-content: space-between;
      margin: 5px 0;
    }

    .payment-methods {
      margin: 20px 0;
    }

    .payment-methods label {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
      cursor: pointer;
    }

    .payment-methods input {
      margin-right: 10px;
    }

    .coupon {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    .coupon input {
      flex: 1;
      padding: 10px;
    }

    .coupon button,
    .place-order {
      background-color: #d9534f;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    .place-order {
      width: 100%;
    }

    .order-items img {
      margin-right: 10px;
    }

    .item {
      display: flex;
      align-items: center;
    }
    .couponCss{
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
  </style>
</head>
<body>
  <div class="container">
    <div class="billing">
      <h2>Billing Details</h2>
      <label>First Name*</label>
      <input type="text" required>

      <label>Company Name</label>
      <input type="text">

      <label>Street Address*</label>
      <input type="text" required>

      <label>Apartment, floor, etc. (optional)</label>
      <input type="text">

      <label>Town/City*</label>
      <input type="text" required>

      <label>Phone Number*</label>
      <input type="tel" required>

      <label>Email Address*</label>
      <input type="email" required>

    
    </div>
    <div class="order-summary">
    <div class="couponCss">
        <div class="section-title freeship-note"><img src="/img/coupon.svg" alt="">Khuyến Mãi</div>
        <div class="voucher">
            <div class="voucherItem active">
              <span>Giảm 15% tối đa 70K</span>
            </div>
        </div>
        
      </div>
      <div class="order-items">
        <div class="item">
          <img src="/img/adidas.svg"  alt="Backpack" />
          <span>Backpack NH Arpenaz</span>
          <span class="price">$650</span>
        </div>
        <div class="item">
          <img src="/img/adidas.svg"  alt="Tennis Shirt" />
          <span>Tennis Shirt Mens Dri Fit</span>
          <span class="price">$1100</span>
        </div>
      </div>
  
      <div class="order-total">
        <div class="row">
          <span>Subtotal:</span><span>$1750</span>
        </div>
        <div class="row">
          <span>Shipping:</span><span>Free</span>
        </div>
        <div class="row total">
          <span>Total:</span><span>$1750</span>
        </div>
        <button class="place-order">Place Order</button>

      </div>
    </div>
  </div>
</body>
</html>
