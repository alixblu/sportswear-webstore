<?php
require_once dirname(__FILE__) . '/../repository/cartdetailrepository.php';

class CartDetailService
{
    private $cartDetailRepository;

    public function __construct()
    {
        $this->cartDetailRepository = new CartDetailRepository();
    }

    public function addCartDetail($productID, $quantity, $cartID)
    {
        try {
            if (!is_numeric($productID) || $productID <= 0) {
                throw new Exception("Invalid product ID");
            }
            if (!is_numeric($quantity) || $quantity <= 0) {
                throw new Exception("Invalid quantity");
            }
            if (!is_numeric($cartID) || $cartID <= 0) {
                throw new Exception("Invalid cart ID");
            }

            $existingDetail = $this->cartDetailRepository->findByCartIdAndProductId($cartID, $productID);

            if ($existingDetail) {
                $newQuantity = $existingDetail['quantity'] + $quantity;
                return $this->cartDetailRepository->updateQuantity($existingDetail['ID'], $newQuantity);
            } else {
                return $this->cartDetailRepository->add($productID, $quantity, $cartID);
            }

        } catch (Exception $e) {
            throw new Exception("Failed to add cart detail: " . $e->getMessage());
        }
    }


    public function getCartDetailsByCartID($cartID)
    {
        try {
            if (!is_numeric($cartID) || $cartID <= 0) {
                throw new Exception("Invalid cart ID");
            }

            return $this->cartDetailRepository->findByCartID($cartID);
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve cart details: " . $e->getMessage());
        }
    }

    public function updateCartDetailQuantity($cartDetailID, $quantity)
    {
        try {
            if (!is_numeric($cartDetailID) || $cartDetailID <= 0) {
                throw new Exception("Invalid cart detail ID");
            }
            if (!is_numeric($quantity) || $quantity <= 0) {
                throw new Exception("Invalid quantity");
            }

            return $this->cartDetailRepository->updateQuantity($cartDetailID, $quantity);
        } catch (Exception $e) {
            throw new Exception("Failed to update cart detail quantity: " . $e->getMessage());
        }
    }

    public function deleteCartDetail($cartDetailID)
    {
        try {
            if (!is_numeric($cartDetailID) || $cartDetailID <= 0) {
                throw new Exception("Invalid cart detail ID");
            }

            return $this->cartDetailRepository->delete($cartDetailID);
        } catch (Exception $e) {
            throw new Exception("Failed to delete cart detail: " . $e->getMessage());
        }
    }
    public function deleteAllCartDetail($cartID)
    {
        try {
            return $this->cartDetailRepository->deleteAll($cartID);
        } catch (Exception $e) {
            throw new Exception("Failed to delete cart detail: " . $e->getMessage());
        }
    }

    
    
}
