<?php
require_once dirname(__FILE__) . '/../service/AnalyticsService.php';
require_once dirname(__FILE__) . '/../config/response/apiresponse.php';

class AnalyticsController {
    private $analyticsService;

    public function __construct() {
        $this->analyticsService = new AnalyticsService();
    }

    public function getTotalRevenue($startDate, $endDate): void {
        try {
            if (!$startDate || !$endDate) {
                throw new Exception("Missing startDate or endDate");
            }
            $revenue = $this->analyticsService->getTotalRevenue($startDate, $endDate);
            ApiResponse::customApiResponse(['total_revenue' => $revenue], 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getOrderStats($startDate, $endDate): void {
        try {
            if (!$startDate || !$endDate) {
                throw new Exception("Missing startDate or endDate");
            }
            $stats = $this->analyticsService->getOrderStats($startDate, $endDate);
            ApiResponse::customApiResponse($stats, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getTopCustomers($startDate, $endDate, $limit = 5): void {
        try {
            if (!$startDate || !$endDate) {
                throw new Exception("Missing startDate or endDate");
            }
            if (!is_numeric($limit) || $limit <= 0) {
                throw new Exception("Limit must be a positive integer");
            }
            $customers = $this->analyticsService->getTopCustomers($startDate, $endDate, $limit);
            ApiResponse::customApiResponse($customers, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getTopProducts($startDate, $endDate, $limit = 10): void {
        try {
            if (!$startDate || !$endDate) {
                throw new Exception("Missing startDate or endDate");
            }
            if (!is_numeric($limit) || $limit <= 0) {
                throw new Exception("Limit must be a positive integer");
            }
            $products = $this->analyticsService->getTopProducts($startDate, $endDate, $limit);
            ApiResponse::customApiResponse($products, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getCouponUsage($startDate, $endDate): void {
        try {
            if (!$startDate || !$endDate) {
                throw new Exception("Missing startDate or endDate");
            }
            $coupons = $this->analyticsService->getCouponUsage($startDate, $endDate);
            ApiResponse::customApiResponse($coupons, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getActiveUsers($startDate, $endDate): void {
        try {
            if (!$startDate || !$endDate) {
                throw new Exception("Missing startDate or endDate");
            }
            $activeUsers = $this->analyticsService->getActiveUsers($startDate, $endDate);
            ApiResponse::customApiResponse($activeUsers, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }
}
?>