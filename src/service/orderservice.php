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
}
?>