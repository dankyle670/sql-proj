<?php

namespace Controllers;

use Models\Review;

class ReviewController
{
    private $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new Review();
    }

    // Add a review
    public function addReview($data)
    {
        $validationResult = $this->validateReviewData($data);
        if (!$validationResult['success']) {
            return $validationResult;
        }

        if ($this->reviewModel->addReview($data['spectacle_id'], $data['subscriber_id'], $data['comment'], $data['rating'])) {
            return ['success' => true, 'message' => 'Review added successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to add review. Please try again later.'];
    }

    // Get reviews for a spectacle
    public function getReviews($spectacleId)
    {
        $reviews = $this->reviewModel->getReviewsBySpectacle($spectacleId);
        if ($reviews) {
            return ['success' => true, 'data' => $reviews];
        }

        return ['success' => false, 'message' => 'No reviews found for this spectacle.'];
    }

    // Delete a review
    public function deleteReview($reviewId)
    {
        if ($this->reviewModel->deleteReview($reviewId)) {
            return ['success' => true, 'message' => 'Review deleted successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to delete review.'];
    }

    // Validate review data
    private function validateReviewData($data)
    {
        if (empty($data['spectacle_id']) || empty($data['subscriber_id']) || empty($data['comment']) || empty($data['rating'])) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        if (!is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.'];
        }

        if (strlen($data['comment']) > 1000) {
            return ['success' => false, 'message' => 'Comment cannot exceed 1000 characters.'];
        }

        return ['success' => true];
    }
}

