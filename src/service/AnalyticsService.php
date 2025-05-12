<?php
require_once dirname(__FILE__) . '/../repository/analyticsrepository.php';

class AnalyticsService {
    private $analyticsRepository;

    public function __construct() {
        $this->analyticsRepository = new AnalyticsRepository();
    }

    public function getTopCustomers($startDate, $endDate, $limit = 5) {
        try {
            if (!$this->validateDateRange($startDate, $endDate)) {
                throw new Exception("Invalid date range");
            }
            if (!is_numeric($limit) || $limit <= 0) {
                throw new Exception("Limit must be a positive integer");
            }
            return $this->analyticsRepository->getTopCustomers($startDate, $endDate, $limit);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch top customers: " . $e->getMessage());
        }
    }

    public function getTotalRevenue($startDate, $endDate) {
        try {
            if (!$this->validateDateRange($startDate, $endDate)) {
                throw new Exception("Invalid date range");
            }
            return $this->analyticsRepository->getTotalRevenue($startDate, $endDate);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch total revenue: " . $e->getMessage());
        }
    }

    public function getOrderStats($startDate, $endDate) {
        try {
            if (!$this->validateDateRange($startDate, $endDate)) {
                throw new Exception("Invalid date range");
            }
            return $this->analyticsRepository->getOrderStats($startDate, $endDate);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch order stats: " . $e->getMessage());
        }
    }

    public function getTopProducts($startDate, $endDate, $limit = 10) {
        try {
            if (!$this->validateDateRange($startDate, $endDate)) {
                throw new Exception("Invalid date range");
            }
            if (!is_numeric($limit) || $limit <= 0) {
                throw new Exception("Limit must be a positive integer");
            }
            return $this->analyticsRepository->getTopProducts($startDate, $endDate, $limit);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch top products: " . $e->getMessage());
        }
    }

    public function getCouponUsage($startDate, $endDate) {
        try {
            if (!$this->validateDateRange($startDate, $endDate)) {
                throw new Exception("Invalid date range");
            }
            return $this->analyticsRepository->getCouponUsage($startDate, $endDate);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch coupon usage: " . $e->getMessage());
        }
    }

    public function getActiveUsers($startDate, $endDate) {
        try {
            if (!$this->validateDateRange($startDate, $endDate)) {
                throw new Exception("Invalid date range");
            }
            return $this->analyticsRepository->getActiveUsers($startDate, $endDate);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch active users: " . $e->getMessage());
        }
    }

    // New method: Fetch customer order details
    public function getCustomerOrderDetails($userID, $startDate, $endDate) {
        try {
            if (!$this->validateDateRange($startDate, $endDate)) {
                throw new Exception("Invalid date range");
            }
            if (!is_numeric($userID) || $userID <= 0) {
                throw new Exception("Invalid user ID");
            }
            return $this->analyticsRepository->getCustomerOrderDetails($userID, $startDate, $endDate);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch customer order details: " . $e->getMessage());
        }
    }

    // New method: Fetch product order details
    public function getProductOrderDetails($productID, $startDate, $endDate) {
        try {
            if (!$this->validateDateRange($startDate, $endDate)) {
                throw new Exception("Invalid date range");
            }
            if (!is_numeric($productID) || $productID <= 0) {
                throw new Exception("Invalid product ID");
            }
            return $this->analyticsRepository->getProductOrderDetails($productID, $startDate, $endDate);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch product order details: " . $e->getMessage());
        }
    }

    private function validateDateRange($startDate, $endDate) {
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        return $startTimestamp && $endTimestamp && $startTimestamp <= $endTimestamp;
    }
}
?>