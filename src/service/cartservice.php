<?php
require_once dirname(__FILE__) . '/../repository/cartrepository.php';
require_once dirname(__FILE__) . '/../utils/userUtils.php';
require_once dirname(__FILE__) . '/../service/cartdetailService.php';
require_once dirname(__FILE__) . '/../service/accountservice.php';

class CartService {
    private $cartRepository;
    private $userUtils;
    private $cartDetailService;
    private $accountService;

    public function __construct() {
        $this->cartRepository = new CartRepository();
        $this->userUtils = new UserUtils();
        $this->cartDetailService = new CartDetailService();
        $this->accountService = new AccountService();

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

    public function getCartByUserId() {
        try {
            $userAccID = $this->userUtils->getUserId();
            $accountId = $this->accountService->getAccountByUserID($userAccID);
            return $this->cartRepository->findByUserAccId($accountId);
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
            $accountId = $this->accountService->getAccountByUserID($userId);
            $cartID = $this->cartRepository->findCartIdByUserAccId($accountId);
            if ($cartID === null) {
                $cartData = $this->cartRepository->save($userId);
                $cartID = $cartData['cartID'];
            }
            return $this->cartDetailService->addCartDetail($productID, $quantity, $cartID);
        } catch (Exception $e) {
            throw new Exception("Failed to add cart detail: " . $e->getMessage());
        }
    }
    public function deleteCartByUserId()
    {
        try{
            $userId = $this->userUtils->getUserId();
            $accountId = $this->accountService->getAccountByUserID($userId);
            $cartID = $this->cartRepository->findCartIdByUserAccId($accountId);
            return $this->cartDetailService->deleteAllCartDetail($cartID);
        } catch (Exception $e) {
            throw new Exception("Failed to add cart detail: " . $e->getMessage());
        }
    }

}
