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
      flex-direction: column;
      gap: 50px;
      max-width: 1000px;
      margin: auto;
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
      height: 40px;
      margin-right: 10px;
    }

    .item {
      display: flex;
      align-items: center;
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
    <button class="place-order">Place Order</button>

  </div>
</body>
</html>
