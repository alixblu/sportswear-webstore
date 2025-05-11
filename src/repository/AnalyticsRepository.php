<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class AnalyticsRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    public function getTopCustomers($startDate, $endDate, $limit = 5) {
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }
            $stmt = $this->conn->prepare("
                SELECT 
                    u.ID as userID,
                    u.fullname,
                    u.phone,
                    SUM(o.totalPrice) as total_purchase,
                    GROUP_CONCAT(
                        JSON_OBJECT(
                            'orderID', o.ID,
                            'createdAt', o.createdAt,
                            'totalPrice', o.totalPrice,
                            'status', o.status
                        ) SEPARATOR '||'
                    ) as orders
                FROM `order` o
                JOIN user u ON o.customer = u.ID
                WHERE o.createdAt BETWEEN ? AND ?
                AND o.status = 'delivered'
                GROUP BY u.ID, u.fullname, u.phone
                ORDER BY total_purchase DESC
                LIMIT ?
            ");
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $this->conn->error);
            }
            $stmt->bind_param("ssi", $startDate, $endDate, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            $customers = [];
            while ($row = $result->fetch_assoc()) {
                // Tách các JSON object bằng '||' và decode
                $ordersString = $row['orders'];
                $ordersArray = $ordersString ? array_map('json_decode', explode('||', $ordersString)) : [];
                $row['orders'] = array_filter($ordersArray, function($order) {
                    return $order !== null && $order !== false;
                });
                $customers[] = $row;
            }
            $stmt->close();
            return $customers;
        } catch (Exception $e) {
            error_log("Get top customers failed: " . $e->getMessage());
            throw $e;
        }
    }
    public function getTotalRevenue($startDate, $endDate) {
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }
            $stmt = $this->conn->prepare("
                SELECT SUM(totalPrice) as total_revenue
                FROM `order`
                WHERE createdAt BETWEEN ? AND ?
                AND status = 'delivered'
            ");
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $this->conn->error);
            }
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            return $data['total_revenue'] ?? 0;
        } catch (Exception $e) {
            error_log("Get total revenue failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function getOrderStats($startDate, $endDate) {
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }
            $stmt = $this->conn->prepare("
                SELECT status, COUNT(*) as count
                FROM `order`
                WHERE createdAt BETWEEN ? AND ?
                GROUP BY status
            ");
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $this->conn->error);
            }
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $stats = [];
            while ($row = $result->fetch_assoc()) {
                $stats[$row['status']] = $row['count'];
            }
            $stmt->close();
            return $stats;
        } catch (Exception $e) {
            error_log("Get order stats failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function getTopProducts($startDate, $endDate, $limit = 10) {
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }
            $stmt = $this->conn->prepare("
                SELECT 
                    p.ID as productID,
                    p.name,
                    c.name as category,
                    SUM(od.quantity) as total_quantity,
                    SUM(od.quantity * pv.price) as total_revenue
                FROM orderdetail od
                JOIN productvariant pv ON od.productID = pv.ID
                JOIN product p ON pv.productID = p.ID
                JOIN category c ON p.categoryID = c.ID
                JOIN `order` o ON od.orderID = o.ID
                WHERE o.createdAt BETWEEN ? AND ?
                AND o.status = 'delivered'
                GROUP BY p.ID, p.name, c.name
                ORDER BY total_quantity DESC
                LIMIT ?
            ");
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $this->conn->error);
            }
            $stmt->bind_param("ssi", $startDate, $endDate, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            $stmt->close();
            return $products;
        } catch (Exception $e) {
            error_log("Get top products failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function getCouponUsage($startDate, $endDate) {
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }
            $stmt = $this->conn->prepare("
                SELECT c.name, COUNT(o.ID) as usage_count
                FROM `order` o
                JOIN coupon c ON o.couponID = c.ID
                WHERE o.createdAt BETWEEN ? AND ?
                AND o.status = 'delivered'
                GROUP BY c.ID
            ");
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $this->conn->error);
            }
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $coupons = [];
            while ($row = $result->fetch_assoc()) {
                $coupons[] = $row;
            }
            $stmt->close();
            return $coupons;
        } catch (Exception $e) {
            error_log("Get coupon usage failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function getActiveUsers($startDate, $endDate) {
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }
            $stmt = $this->conn->prepare("
                SELECT COUNT(DISTINCT u.ID) as active_users
                FROM user u
                JOIN `order` o ON u.ID = o.customer
                WHERE o.createdAt BETWEEN ? AND ?
                AND o.status = 'delivered'
            ");
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $this->conn->error);
            }
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            return ['active_users' => $data['active_users'] ?? 0];
        } catch (Exception $e) {
            error_log("Get active users failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>