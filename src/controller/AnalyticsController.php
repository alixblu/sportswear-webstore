<?php
include dirname(__FILE__) . '/../service/analyticsservice.php';
include_once dirname(__FILE__) . '/../config/response/apiresponse.php';

class AnalyticsController {
    private $analyticsService;

    public function __construct() {
        $this->analyticsService = new AnalyticsService();
    }

    public function getTotalRevenue($startDate, $endDate) {
        try {
            $revenue = $this->analyticsService->getTotalRevenue($startDate, $endDate);
            ApiResponse::customApiResponse(['total_revenue' => $revenue], 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getOrderStats($startDate, $endDate) {
        try {
            $stats = $this->analyticsService->getOrderStats($startDate, $endDate);
            ApiResponse::customApiResponse($stats, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getTopProducts($startDate, $endDate, $limit = 10) {
        try {
            $products = $this->analyticsService->getTopProducts($startDate, $endDate, $limit);
            ApiResponse::customApiResponse($products, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getCouponUsage($startDate, $endDate) {
        try {
            $coupons = $this->analyticsService->getCouponUsage($startDate, $endDate);
            ApiResponse::customApiResponse($coupons, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }
}
?>