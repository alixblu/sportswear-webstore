<?php
require_once dirname(__FILE__) . '/../repository/discountrepository.php';

class DiscountService
{
    private $discountRepository;

    public function __construct()
    {
        $this->discountRepository = new DiscountRepository();
    }

    public function createDiscount($name, $discountRate, $status)
    {
        try {
            if (empty($name)) {
                throw new Exception("Discount name cannot be empty");
            }

            if (!is_numeric($discountRate) || $discountRate < 0 || $discountRate > 100) {
                throw new Exception("Discount rate must be between 0 and 100");
            }

            if (!in_array($status, ['active', 'inactive'])) {
                throw new Exception("Invalid discount status");
            }

            return $this->discountRepository->save($name, $discountRate, $status);
        } catch (Exception $e) {
            throw new Exception("Failed to create discount: " . $e->getMessage());
        }
    }

    public function getAllDiscounts()
    {
        try {
            return $this->discountRepository->findAll();
        } catch (Exception $e) {
            throw new Exception("Failed to get discounts: " . $e->getMessage());
        }
    }

    public function getDiscountById($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid discount ID");
            }

            return $this->discountRepository->findById($id);
        } catch (Exception $e) {
            throw new Exception("Failed to get discount: " . $e->getMessage());
        }
    }

    public function updateDiscount($id, $name, $discountRate, $status)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid discount ID");
            }

            if (empty($name)) {
                throw new Exception("Discount name cannot be empty");
            }

            if (!is_numeric($discountRate) || $discountRate < 0 || $discountRate > 100) {
                throw new Exception("Discount rate must be between 0 and 100");
            }

            if (!in_array($status, ['active', 'inactive'])) {
                throw new Exception("Invalid discount status");
            }

            return $this->discountRepository->update($id, $name, $discountRate, $status);
        } catch (Exception $e) {
            throw new Exception("Failed to update discount: " . $e->getMessage());
        }
    }

    public function deleteDiscount($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid discount ID");
            }

            return $this->discountRepository->delete($id);
        } catch (Exception $e) {
            throw new Exception("Failed to delete discount: " . $e->getMessage());
        }
    }
}
