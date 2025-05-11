<?php
require_once dirname(__FILE__) . '/../repository/analyticsrepository.php';

class AnalyticsService {
    private $analyticsRepository;

    public function __construct() {
        $this->analyticsRepository = new AnalyticsRepository();
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
                throw new Exception("Invalid limit");
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

    private function validateDateRange($startDate, $endDate) {
        return strtotime($startDate) && strtotime($endDate) && strtotime($startDate) <= strtotime($endDate);
    }
}
?>