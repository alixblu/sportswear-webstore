<?php
require_once dirname(__FILE__) . '/../repository/couponrepository.php';

class CouponService
{
    private $couponRepository;

    public function __construct()
    {
        $this->couponRepository = new CouponRepository();
    }

    public function createCoupon($name, $percent, $duration)
    {
        try {
            if (empty($name)) {
                throw new Exception("Coupon name is required");
            }

            if (!is_numeric($percent) || $percent <= 0 || $percent > 100) {
                throw new Exception("Percent must be a number between 1 and 100");
            }

            if (!is_numeric($duration) || $duration <= 0) {
                throw new Exception("Duration must be a positive number (in days)");
            }

            return $this->couponRepository->save($name, $percent, $duration);
        } catch (Exception $e) {
            throw new Exception("Failed to create coupon: " . $e->getMessage());
        }
    }

    public function getAllCoupons()
    {
        try {
            return $this->couponRepository->findAll();
        } catch (Exception $e) {
            throw new Exception("Failed to fetch coupons: " . $e->getMessage());
        }
    }

    public function updateCoupon($id, $name, $percent, $duration, $status)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid coupon ID");
            }

            if (empty($name)) {
                throw new Exception("Coupon name is required");
            }

            if (!is_numeric($percent) || $percent <= 0 || $percent > 100) {
                throw new Exception("Percent must be a number between 1 and 100");
            }

            if (!is_numeric($duration) || $duration <= 0) {
                throw new Exception("Duration must be a positive number");
            }

            if (!in_array($status, ['active', 'inactive'])) {
                throw new Exception("Invalid status");
            }

            return $this->couponRepository->update($id, $name, $percent, $duration, $status);
        } catch (Exception $e) {
            throw new Exception("Failed to update coupon: " . $e->getMessage());
        }
    }

    public function deleteCoupon($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid coupon ID");
            }

            return $this->couponRepository->delete($id);
        } catch (Exception $e) {
            throw new Exception("Failed to delete coupon: " . $e->getMessage());
        }
    }

    public function getCouponById($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid coupon ID");
            }

            return $this->couponRepository->findById($id);
        } catch (Exception $e) {
            throw new Exception("Failed to find coupon: " . $e->getMessage());
        }
    }
}
