<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class AnalyticsRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    // Lấy tổng doanh thu theo khoảng thời gian
    public function getTotalRevenue($startDate, $endDate) {
        try {
            $stmt = $this->conn->prepare("
                SELECT SUM(totalPrice) as total_revenue
                FROM `order`
                WHERE createdAt BETWEEN ? AND ?
                AND status = 'delivered'
            ");
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            return $data['total_revenue'] ?? 0;
        } catch (Exception $e) {
            error_log("Get total revenue failed: " . $e->getMessage());
            return 0;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    // Lấy thống kê đơn hàng theo trạng thái
    public function getOrderStats($startDate, $endDate) {
        try {
            $stmt = $this->conn->prepare("
                SELECT status, COUNT(*) as count
                FROM `order`
                WHERE createdAt BETWEEN ? AND ?
                GROUP BY status
            ");
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
            return [];
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    // Lấy danh sách sản phẩm bán chạy
    public function getTopProducts($startDate, $endDate, $limit = 10) {
        try {
            $stmt = $this->conn->prepare("
                SELECT p.name, SUM(od.quantity) as total_quantity
                FROM orderdetail od
                JOIN productvariant pv ON od.productID = pv.ID
                JOIN product p ON pv.productID = p.ID
                JOIN `order` o ON od.orderID = o.ID
                WHERE o.createdAt BETWEEN ? AND ?
                AND o.status = 'delivered'
                GROUP BY p.ID
                ORDER BY total_quantity DESC
                LIMIT ?
            ");
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
            return [];
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    // Lấy thống kê sử dụng mã giảm giá
    public function getCouponUsage($startDate, $endDate) {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.name, COUNT(o.ID) as usage_count
                FROM `order` o
                JOIN coupon c ON o.couponID = c.ID
                WHERE o.createdAt BETWEEN ? AND ?
                AND o.status = 'delivered'
                GROUP BY c.ID
            ");
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
            return [];
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }
}
?>