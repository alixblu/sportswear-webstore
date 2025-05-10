<?php
require_once dirname(__FILE__) . '/../service/searchservice.php';
require_once dirname(__FILE__) . '/../config/response/apiresponse.php';

class SearchController
{
    private $searchService;

    public function __construct()
    {
        $this->searchService = new SearchService();
    }

    public function searchProducts($category, $brand, $status, $min_price, $max_price, $sort, $search)
    {
        try {
            $products = $this->searchService->searchProducts($category, $brand, $status, $min_price, $max_price, $sort, $search);
            ApiResponse::customApiResponse($products, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
    }

    public function getAllBrands()
    {
        try {
            $brands = $this->searchService->getAllBrands();
            ApiResponse::customApiResponse($brands, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
    }

    public function getAllCategories()
    {
        try {
            $categories = $this->searchService->getAllCategories();
            ApiResponse::customApiResponse($categories, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
    }

    public function getBrandByName($name)
    {
        try {
            $brand = $this->searchService->getBrandByName($name);
            if (!$brand) {
                ApiResponse::customApiResponse(null, 404, 'Thương hiệu không tìm thấy');
                return;
            }
            ApiResponse::customApiResponse($brand, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
    }

    public function getCategoryByName($name)
    {
        try {
            $category = $this->searchService->getCategoryByName($name);
            if (!$category) {
                ApiResponse::customApiResponse(null, 404, 'Danh mục không tìm thấy');
                return;
            }
            ApiResponse::customApiResponse($category, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
    }

    /**
     * Lấy thương hiệu theo ID
     * @param int $id
     */
    public function getBrandById($id)
    {
        try {
            $brand = $this->searchService->getBrandById($id);
            if (!$brand) {
                ApiResponse::customApiResponse(null, 404, 'Thương hiệu không tìm thấy');
                return;
            }
            ApiResponse::customApiResponse($brand, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
    }

    /**
     * Lấy danh mục theo ID
     * @param int $id
     */
    public function getCategoryById($id)
    {
        try {
            $category = $this->searchService->getCategoryById($id);
            if (!$category) {
                ApiResponse::customApiResponse(null, 404, 'Danh mục không tìm thấy');
                return;
            }
            ApiResponse::customApiResponse($category, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
    }
    public function searchByName($name)
    {
        try {
            $brand = $this->searchService->getBrandByName($name);
            $category = $this->searchService->getCategoryByName($name);
            ApiResponse::customApiResponse([
                'brand' => $brand,
                'category' => $category
            ], 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
}
}