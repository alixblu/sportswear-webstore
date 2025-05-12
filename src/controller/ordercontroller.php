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
    public function getOrdersByCustomer()
    {
        try {
            $orders = $this->orderService->getOrdersByCustomer();
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
     * @param int $ID
     * @param string $status
     */
    public function updateOrderStatus($ID, $status)
    {
        try {
            if (!isset($ID) || !is_numeric($ID)) {
                ApiResponse::customResponse(null, 400, 'Invalid order ID');
                return;
            }

            $result = $this->orderService->updateOrderStatus($ID, $status);
            if ($result) {
                ApiResponse::customResponse(['success' => true], 200, 'Order status updated successfully');
            } else {
                ApiResponse::customResponse(null, 500, 'Failed to update order status');
            }
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    /**
     * Search orders with filters
     */
    public function searchOrders($orderID = null, $customerName = '', $status = '', $fromDate = '', $toDate = '')
    {
        try {
            $results = $this->orderService->searchOrders($orderID, $customerName, $status, $fromDate, $toDate);
            ApiResponse::customResponse($results, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    public function createOrders($receiverName,$address,$phone,$idCoupon,$payment)
    {
        try {
            $results = $this->orderService->createOrders($receiverName,$address,$phone,$idCoupon,$payment);
            ApiResponse::customResponse($results, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }
}