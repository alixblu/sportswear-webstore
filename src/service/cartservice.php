<?php
require_once dirname(__FILE__) . '/../repository/cartrepository.php';
require_once dirname(__FILE__) . '/../utils/userUtils.php';
require_once dirname(__FILE__) . '/../service/cartdetailService.php';

class CartService {
    private $cartRepository;
    private $userUtils;
    private $cartDetailService;

    public function __construct() {
        $this->cartRepository = new CartRepository();
        $this->userUtils = new UserUtils();
        $this->cartDetailService = new CartDetailService();

    }

    public function createCart($userAccID, $totalPrice = 0.0) {
        try {
            if (!is_numeric($userAccID) || $userAccID <= 0) {
                throw new Exception("Invalid user ID");
            }
            return $this->cartRepository->save($userAccID, $totalPrice);
        } catch (Exception $e) {
            throw new Exception("Failed to create cart: " . $e->getMessage());
        }
    }

    public function getCartByUserId($userAccID) {
        try {
            if (!is_numeric($userAccID) || $userAccID <= 0) {
                throw new Exception("Invalid user ID");
            }
            return $this->cartRepository->findByUserAccId($userAccID);
        } catch (Exception $e) {
            throw new Exception("Failed to get cart: " . $e->getMessage());
        }
    }

    public function updateTotalPrice($cartID, $totalPrice) {
        try {
            if (!is_numeric($cartID) || $cartID <= 0) {
                throw new Exception("Invalid cart ID");
            }
            return $this->cartRepository->update($cartID, $totalPrice);
        } catch (Exception $e) {
            throw new Exception("Failed to update cart: " . $e->getMessage());
        }
    }

    public function addProductCart($productID, $quantity)
    {
        try {
            if (!is_numeric($productID) || $productID <= 0) {
                throw new Exception("Invalid product ID");
            }
            if (!is_numeric($quantity) || $quantity <= 0) {
                throw new Exception("Invalid quantity");
            }
            $userId = $this->userUtils->getUserId();
            $cartID = $this->cartRepository->findCartIdByUserAccId($userId);

            if ($cartID !== null) {
                $cartID = $this->createCart($userId);
            } 
            return $this->cartDetailService->addCartDetail($productID, $quantity, $cartID);
        } catch (Exception $e) {
            throw new Exception("Failed to add cart detail: " . $e->getMessage());
        }
    }
}
