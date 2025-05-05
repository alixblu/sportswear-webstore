<?php
require_once dirname(__FILE__) . '/../repository/cartrepository.php';

class CartService {
    private $cartRepository;

    public function __construct() {
        $this->cartRepository = new CartRepository();
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

}
