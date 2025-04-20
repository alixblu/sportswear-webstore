<?php
include dirname(__FILE__) . '/../service/discountservice.php';
include dirname(__FILE__) . '/../config/response/apiresponse.php';

class DiscountController
{
    private $discountService;

    public function __construct()
    {
        $this->discountService = new DiscountService();
    }

    public function getAllDiscounts()
    {
        $discounts = $this->discountService->getAllDiscounts();
        ApiResponse::customApiResponse($discounts, 200);
    }

    public function getDiscountById($id)
    {
        $result = $this->discountService->getDiscountById($id);
        ApiResponse::customApiResponse($result, 200);
    }

    public function createDiscount($name, $discountRate, $status)
    {
        $result = $this->discountService->createDiscount($name, $discountRate, $status);
        ApiResponse::customApiResponse($result, 200);
    }

    public function updateDiscount($id, $name, $discountRate, $status)
    {
        $result = $this->discountService->updateDiscount($id, $name, $discountRate, $status);
        ApiResponse::customApiResponse($result, 200);
    }

    public function deleteDiscount($id)
    {
        $result = $this->discountService->deleteDiscount($id);
        ApiResponse::customApiResponse($result, 200);
    }
}
