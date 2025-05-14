<?php
require_once dirname(__FILE__) . '/../repository/reviewrepository.php';
require_once dirname(__FILE__) . '/../utils/userUtils.php';
require_once dirname(__FILE__) . '/../utils/userUtils.php';
require_once dirname(__FILE__) . '/../service/accountservice.php';

class ReviewService
{
    private $reviewRepository;
    private $userUtils;
    private $accountService;

    public function __construct()
    {
        $this->reviewRepository = new ReviewRepository();
        $this->userUtils = new UserUtils();
        $this->accountService = new AccountService();

    }

    public function createReview( $productId, $rating, $commentContent = null)
    {
        try {
            if (!is_numeric($productId) || $productId <= 0) {
                throw new Exception("Invalid product ID");
            }
            if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
                throw new Exception("Rating must be between 1 and 5");
            }
            $userid = $this->userUtils->getUserId();
            $accountId = $this->accountService->getAccountByUserID($userid);

            return $this->reviewRepository->save($accountId, $productId, $rating, $commentContent);
        } catch (Exception $e) {
            throw new Exception("Failed to create review: " . $e->getMessage());
        }
    }


    public function getAllReviews()
    {
        try {
            return $this->reviewRepository->findAll();
        } catch (Exception $e) {
            throw new Exception("Failed to get reviews: " . $e->getMessage());
        }
    }

    public function getReviewByProductId($productId) {
        try {
            return $this->reviewRepository->getReviewByProductId($productId);
        } catch (Exception $e) {
            throw new Exception("Failed to get reviews: " . $e->getMessage());
        }
    }

    public function updateReview($id, $rating, $commentContent = null)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid review ID");
            }
            if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
                throw new Exception("Invalid rating value");
            }

            return $this->reviewRepository->update($id, $rating, $commentContent);
        } catch (Exception $e) {
            throw new Exception("Failed to update review: " . $e->getMessage());
        }
    }

    public function deleteReview($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid review ID");
            }

            return $this->reviewRepository->delete($id);
        } catch (Exception $e) {
            throw new Exception("Failed to delete review: " . $e->getMessage());
        }
    }


    public function getPendingReviews() {

        try {
            $userid = $this->userUtils->getUserId();
            return $this->reviewRepository->getPendingReviews($userid);
        } catch (Exception $e) {
            throw new Exception("Failed to delete review: " . $e->getMessage());
        }
    }

}
