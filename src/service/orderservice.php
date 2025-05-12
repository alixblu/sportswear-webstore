<?php
require_once dirname(__FILE__) . '/../repository/orderrepository.php';
require_once dirname(__FILE__) . '/../service/orderservice.php';
require_once dirname(__FILE__) . '/../service/cartservice.php';
require_once dirname(__FILE__) . '/../service/couponservice.php';

class OrderService
{
    private $orderRepository;
    private $couponService;
    private $cartService;
    private $userUtils;

    
    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->couponService = new CouponService();
        $this->cartService = new CartService();
        $this->userUtils = new UserUtils();

    }

    public function getAllOrders()
    {
        try {
            return $this->orderRepository->getAllOrders();
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve orders: " . $e->getMessage());
        }
    }
    public function getOrdersByCustomer()
    {
        try {
            $customerId = $this->userUtils->getUserId();
            return $this->orderRepository->getOrdersByCustomer($customerId);
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve orders: " . $e->getMessage());
        }
    }
    public function getOrderDetails($orderID)
    {
        try {
            if (!is_numeric($orderID) || $orderID <= 0) {
                throw new Exception("Invalid order ID");
            }

            return $this->orderRepository->getOrderDetails($orderID);
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve order details: " . $e->getMessage());
        }
    }

    public function updateOrderStatus($ID, $status)
    {
        try {
            if (!is_numeric($ID) || $ID <= 0) {
                throw new Exception("Invalid order ID");
            }
            if (!in_array($status, ['pending', 'approved', 'delivered', 'canceled'])) {
                throw new Exception("Invalid order status");
            }

            // Get current status
            $sql = "SELECT status FROM `order` WHERE ID = ?";
            $conn = ConfigMysqli::connectDatabase();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $currentStatus = $result['status'] ?? '';

            // Define valid transitions
            $statusTransitions = [
                'pending' => ['approved', 'canceled'],
                'approved' => ['delivered', 'canceled'],
                'delivered' => [],
                'canceled' => []
            ];

            if (!in_array($status, $statusTransitions[$currentStatus] ?? [])) {
                throw new Exception("Invalid status transition from $currentStatus to $status");
            }

            return $this->orderRepository->updateOrderStatus($ID, $status);
        } catch (Exception $e) {
            throw new Exception("Failed to update order status: " . $e->getMessage());
        }
    }

    public function searchOrders($orderID = null, $customerName = '', $status = '', $fromDate = '', $toDate = '')
    {
        try {
            if (!empty($orderID) && (!is_numeric($orderID) || $orderID <= 0)) {
                throw new Exception("Invalid order ID");
            }

            if (!empty($status) && !in_array($status, ['pending', 'approved', 'delivered', 'canceled'])) {
                throw new Exception("Invalid status");
            }

            if (!empty($fromDate) && !strtotime($fromDate)) {
                throw new Exception("Invalid from date");
            }

            if (!empty($toDate) && !strtotime($toDate)) {
                throw new Exception("Invalid to date");
            }

            return $this->orderRepository->searchOrders($orderID, $customerName, $status, $fromDate, $toDate);
        } catch (Exception $e) {
            throw new Exception("Failed to search orders: " . $e->getMessage());
        }
    }
    public function createOrders($receiverName,$address,$phone,$idCoupon,$payment)
    {
        try {
            $userAccID = $this->userUtils->getUserId();

            if ($idCoupon !== null && $idCoupon !== '' && strtolower($idCoupon) !== 'null') {
                $coupon = $this->couponService->getCouponById($idCoupon);
                //todo: check status coupon
                if (!$coupon) {
                    throw new Exception("coupon Error");
                }
                $couponId = $idCoupon;
                $this->couponService->useCoupon($userAccID,$idCoupon);
            } else {
                $couponId = null;
            }
    
            $carts = $this->cartService->getCartByUserId();
            $totalPrice = 0.0;

            foreach ($carts as $item) {
                $totalPrice += $item['quantity'] * $item['productPrice'];
            }   

            $order =  $this->orderRepository->createOrder($userAccID, $couponId, $totalPrice);

            foreach ($carts as $item) {
                $productID = $item['productID'];
                $quantity = $item['quantity'];
                $totalPriceForItem = $item['quantity'] * $item['productPrice'];
    
                $this->orderRepository->insertOrderDetail($order['order_id'], $productID, $quantity, $totalPriceForItem);
            }

            $this->orderRepository->insertBillingDetail($order['order_id'], $receiverName, $address, $phone);
            $this->orderRepository->insertPayment($payment, $order['order_id']);
            $this->cartService->deleteCartByUserId();
            return $order ;
        } catch (Exception $e) {
            throw new Exception("Failed to search orders: " . $e->getMessage());
        }
    }

}
?>