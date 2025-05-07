<?php
include dirname(__FILE__) . '/../service/cartdetailservice.php';
include_once  dirname(__FILE__) . '/../config/response/apiresponse.php';

class CartDetailController
{
    private $cartDetailService;

    public function __construct()
    {
        $this->cartDetailService = new CartDetailService();
    }

    public function addCartDetail($productID, $quantity, $cartID)
    {
        try {
            $insertId = $this->cartDetailService->addCartDetail($productID, $quantity, $cartID);
            ApiResponse::customApiResponse(['insertId' => $insertId], 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getCartDetailsByCartID($cartID)
    {
        try {
            $details = $this->cartDetailService->getCartDetailsByCartID($cartID);
            ApiResponse::customApiResponse($details, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function updateCartDetailQuantity($cartDetailID, $quantity)
    {
        try {
            $success = $this->cartDetailService->updateCartDetailQuantity($cartDetailID, $quantity);
            ApiResponse::customApiResponse(['success' => $success], 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteCartDetail($cartDetailID)
    {
        try {
            $success = $this->cartDetailService->deleteCartDetail($cartDetailID);
            ApiResponse::customApiResponse(['success' => $success], 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(['error' => $e->getMessage()], 400);
        }
    }
}
