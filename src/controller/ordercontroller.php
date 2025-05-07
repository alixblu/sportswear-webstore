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
        try {
            if (!isset($orderID) || !is_numeric($orderID)) {
                ApiResponse::customResponse(null, 400, 'Invalid order ID');
                return;
            }

            $result = $this->orderService->updateOrderStatus($orderID, $status);
            if ($result) {
                ApiResponse::customResponse(null, 200, 'Order status updated successfully');
            } else {
                ApiResponse::customResponse(null, 500, 'Failed to update order status');
            }
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    /**
     * Update order billing & payment details
     */
    public function updateOrderDetails($orderID, $receiverName, $address, $phone, $email, $paymentMethodID)
    {
        try {
            $result = $this->orderService->updateOrderDetails(
                $orderID,
                $receiverName,
                $address,
                $phone,
                $email,
                $paymentMethodID
            );

            if ($result) {
                ApiResponse::customResponse(null, 200, 'Order details updated successfully');
            } else {
                ApiResponse::customResponse(null, 500, 'Failed to update order details');
            }
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

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