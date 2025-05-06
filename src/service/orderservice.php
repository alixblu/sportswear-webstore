<?php
require_once dirname(__FILE__) . '/../repository/orderrepository.php';

class OrderService
{
    private $orderrepository;

    public function __construct()
    {
        $this->orderrepository = new OrderRepository();
    }

    public function getAllOrder()
    {
        try {
            return $this->orderrepository->findAll();
        } catch (Exception $e) {
            throw new Exception("Failed to fetch orders: " . $e->getMessage());
        }
    }

    public function updateOrderStatus($orderID, $status)
    {
        try {
            return $this->orderrepository->updateStatus($orderID, $status);
        } catch (Exception $e) {
            throw new Exception("Failed to update order status: " . $e->getMessage());
        }
    }

    public function getOrderById($orderID)
    {
        try {
            return $this->orderrepository->findById($orderID);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch order details: " . $e->getMessage());
        }
    }
}
?>