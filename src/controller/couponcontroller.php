<?php
include dirname(__FILE__) . '/../service/couponservice.php';
include_once  dirname(__FILE__) . '/../config/response/apiresponse.php';

class CouponController
{
    private $couponService;

    public function __construct()
    {
        $this->couponService = new CouponService();
    }

    public function getAllCoupons()
    {
        $coupons = $this->couponService->getAllCoupons();
        ApiResponse::customApiResponse($coupons, 200);
    }

    public function createCoupon($name, $percent, $duration)
    {
        $result = $this->couponService->createCoupon($name, $percent, $duration);
        ApiResponse::customApiResponse($result, 200);
    }

    public function updateCoupon($id, $name, $percent, $duration, $status)
    {
        $result = $this->couponService->updateCoupon($id, $name, $percent, $duration, $status);
        ApiResponse::customApiResponse($result, 200);
    }

    public function deleteCoupon($id)
    {
        try {
            $result = $this->couponService->deleteCoupon($id);
            ApiResponse::customApiResponse($result, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getCouponById($id)
    {
        $coupon = $this->couponService->getCouponById($id);
        if ($coupon) {
            ApiResponse::customApiResponse($coupon, 200);
        } else {
            ApiResponse::customApiResponse(['error' => 'Coupon not found'], 404);
        }

    }

    public function getCouponByUserId($id)
    {
        $coupon = $this->couponService->getCouponByUserId($id);
        ApiResponse::customApiResponse($coupon, 200);
    }
}
