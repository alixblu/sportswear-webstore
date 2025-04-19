const REVIEW_API_URL = '../../src/router/reviewRouter.php';

const getAllReviews = async () => {
    const response = await fetch(`${REVIEW_API_URL}?action=getAllReviews`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Không thể lấy danh sách đánh giá');
    }

    return await response.json();
};
const getReviewsByProductId = async (productId) => {
    const response = await fetch(`${REVIEW_API_URL}?action=getReviewByProductId&productId=${productId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Không thể lấy đánh giá theo sản phẩm');
    }

    return await response.json();
};

const createReview = async (userId, productId, rating, commentContent) => {
    const formData = new URLSearchParams();
    formData.append('action', 'createReview');
    formData.append('userId', userId);
    formData.append('productId', productId);
    formData.append('rating', rating);
    formData.append('commentContent', commentContent);

    const response = await fetch(REVIEW_API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể tạo đánh giá');
    }

    return await response.json();
};

const updateReview = async (reviewId, rating, commentContent) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateReview');
    formData.append('reviewId', reviewId);
    formData.append('rating', rating);
    formData.append('commentContent', commentContent);

    const response = await fetch(REVIEW_API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể cập nhật đánh giá');
    }

    return await response.json();
};

const deleteReview = async (reviewId) => {
    const response = await fetch(`${REVIEW_API_URL}?action=deleteReview&reviewId=${reviewId}`, {
        method: 'DELETE',
    });

    if (!response.ok) {
        throw new Error('Không thể xóa đánh giá');
    }

    return await response.json();
};
