<?php
include dirname(__FILE__) . '/../service/reviewservice.php';
include_once  dirname(__FILE__) . '/../config/response/apiresponse.php';

class ReviewController
{
    private $reviewService;

    public function __construct()
    {
        $this->reviewService = new ReviewService();
    }

    public function getAllReviews()
    {
        $reviews = $this->reviewService->getAllReviews();
        ApiResponse::customApiResponse($reviews, 200);
    }

    public function createReview($userId, $productId, $rating, $commentContent)
    {
        $result = $this->reviewService->createReview($userId, $productId, $rating, $commentContent);
        ApiResponse::customApiResponse($result, 200);
    }
    public function updateReview($id,  $rating, $commentContent)
    {
        $result = $this->reviewService->updateReview($id, $rating, $commentContent);
        ApiResponse::customApiResponse($result, 200);
    }

    public function deleteReview($id)
    {
        $result = $this->reviewService->deleteReview($id);
        ApiResponse::customApiResponse($result, 200);
    }
    public function getReviewByProductId($productId) {
        $result = $this->reviewService->getReviewByProductId($productId);
        ApiResponse::customApiResponse($result, 200);
    }
    public function getPendingReviews() {
        $result = $this->reviewService->getPendingReviews();
        ApiResponse::customApiResponse($result, 200);
    }
}
