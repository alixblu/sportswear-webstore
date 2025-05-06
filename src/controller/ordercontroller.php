<?php
include dirname(__FILE__) . '/../service/orderservice.php';
include dirname(__FILE__) . '/../config/response/apiresponse.php';

class OrderController
{
    private $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function getAllOrder()
    {
        try {
            $orders = $this->orderService->getAllOrder();
            ApiResponse::customApiResponse($orders, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function updateOrderStatus($orderID, $status)
    {
        try {
            $result = $this->orderService->updateOrderStatus($orderID, $status);
            if ($result) {
                ApiResponse::customApiResponse(['message' => 'Order status updated successfully'], 200);
            } else {
                ApiResponse::customApiResponse(['error' => 'Failed to update order status'], 400);
            }
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 500);
        }
    }
    public function getOrderById($orderID)
    {
        try {
            $order = $this->orderService->getOrderById($orderID);
            if (!empty($order)) {
                ApiResponse::customApiResponse($order, 200);
            } else {
                ApiResponse::customApiResponse(['error' => 'Order not found'], 404);
            }
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 500);
        }
    }
}
?>