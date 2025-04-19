<?php
require_once dirname(__FILE__) . '/../repository/reviewrepository.php';

class ReviewService
{
    private $reviewRepository;

    public function __construct()
    {
        $this->reviewRepository = new ReviewRepository();
    }

    public function createReview($userId, $productId, $rating, $commentContent = null)
{
    try {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new Exception("Invalid user ID");
        }
        if (!is_numeric($productId) || $productId <= 0) {
            throw new Exception("Invalid product ID");
        }
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            throw new Exception("Rating must be between 1 and 5");
        }

        return $this->reviewRepository->save($userId, $productId, $rating, $commentContent);
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

}
