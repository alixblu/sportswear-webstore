<?php
require_once dirname(__FILE__) . '/../repository/orderrepository.php';

class OrderService
{
    private $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function getAllOrders()
    {
        try {
            return $this->orderRepository->getAllOrders();
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
            if (!in_array($status, ['pending', 'processing', 'shipped', 'completed', 'cancelled'])) {
                throw new Exception("Invalid order status");
            }

            return $this->orderRepository->updateOrderStatus($ID, $status);
        } catch (Exception $e) {
            throw new Exception("Failed to update order status: " . $e->getMessage());
        }
    }

    public function searchOrders($orderID = null, $customerName = '', $fromDate = '', $toDate = '')
    {
        try {
            if (!empty($orderID) && (!is_numeric($orderID) || $orderID <= 0)) {
                throw new Exception("Invalid order ID");
            }

            if (!empty($fromDate) && !strtotime($fromDate)) {
                throw new Exception("Invalid from date");
            }

            if (!empty($toDate) && !strtotime($toDate)) {
                throw new Exception("Invalid to date");
            }

            return $this->orderRepository->searchOrders($orderID, $customerName, $fromDate, $toDate);
        } catch (Exception $e) {
            throw new Exception("Failed to search orders: " . $e->getMessage());
        }
    }
}
?>
