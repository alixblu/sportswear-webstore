<?php
include dirname(__FILE__) . '/../service/cartservice.php';
include dirname(__FILE__) . '/../config/response/apiresponse.php';

class ReviewController
{
    private $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function createCart($userId)
    {
        $card = $this->cartService->createCart($userId);
        ApiResponse::customApiResponse($card, 200);
    }
    public function getCartByUserId($userId)
    {
        $card = $this->cartService->getCartByUserId($userId);
        ApiResponse::customApiResponse($card, 200);
    }
    public function updateTotalPrice($cartID, $totalPrice)
    {
        $card = $this->cartService->updateTotalPrice($cartID, $totalPrice);
        ApiResponse::customApiResponse($card, 200);
    }
}
