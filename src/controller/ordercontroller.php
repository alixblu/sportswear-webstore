<?php
require_once dirname(__FILE__) . '/../service/orderservice.php';
include_once dirname(__FILE__) . '/../config/response/apiresponse.php';

class OrderController
{
    private $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    /**
     * Get all orders
     */
    public function getAllOrders()
    {
        try {
            $orders = $this->orderService->getAllOrders();
            ApiResponse::customResponse($orders, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    /**
     * Get order details by ID
     * @param int $orderID
     */
    public function getOrderDetails($orderID)
    {
        try {
            if (!isset($orderID) || !is_numeric($orderID)) {
                ApiResponse::customResponse(null, 400, 'Invalid order ID');
                return;
            }

            $details = $this->orderService->getOrderDetails($orderID);
            if (!$details) {
                ApiResponse::customResponse(null, 404, 'Order not found');
                return;
            }

            ApiResponse::customResponse($details, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    /**
     * Update order status
     * @param int $orderID
     * @param string $status
     */
    public function updateOrderStatus($orderID, $status)
    {
        $result = $this->orderService->updateOrderStatus($orderID, $status);
        ApiResponse::customApiResponse($result, 200);
    }

    /**
     * Update order billing & payment details
     */
    /**
     * Search orders with filters
     */
    public function searchOrders($orderID = null, $customerName = '', $fromDate = '', $toDate = '')
    {
        try {
            $results = $this->orderService->searchOrders($orderID, $customerName, $fromDate, $toDate);
            ApiResponse::customResponse($results, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }
}