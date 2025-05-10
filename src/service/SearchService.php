<?php
require_once dirname(__FILE__) . '/../repository/searchrepository.php';
require_once dirname(__FILE__) . '/productservice.php';

class SearchService
{
    private $searchRepository;
    private $productService;

    public function __construct()
    {
        $this->searchRepository = new SearchRepository();
        $this->productService = new ProductService();
    }

    public function searchProducts($category, $brand, $status, $min_price, $max_price, $sort, $search)
    {
        try {
            return $this->productService->getFilteredProducts($category, $brand, $status, $min_price, $max_price, $sort, $search);
        } catch (Exception $e) {
            error_log("Lỗi trong searchProducts service: " . $e->getMessage());
            throw new Exception("Không thể tìm kiếm sản phẩm: " . $e->getMessage());
        }
    }

    public function getAllBrands()
    {
        try {
            return $this->productService->getAllBrands();
        } catch (Exception $e) {
            error_log("Lỗi trong getAllBrands service: " . $e->getMessage());
            throw new Exception("Không thể lấy danh sách thương hiệu: " . $e->getMessage());
        }
    }

    public function getAllCategories()
    {
        try {
            return $this->productService->getAllCategories();
        } catch (Exception $e) {
            error_log("Lỗi trong getAllCategories service: " . $e->getMessage());
            throw new Exception("Không thể lấy danh sách danh mục: " . $e->getMessage());
        }
    }

    public function getBrandByName($name)
    {
        try {
            return $this->productService->getBrandByName($name);
        } catch (Exception $e) {
            error_log("Lỗi trong getBrandByName service: " . $e->getMessage());
            throw new Exception("Không thể lấy thương hiệu: " . $e->getMessage());
        }
    }

    public function getCategoryByName($name)
    {
        try {
            return $this->productService->getCategoryByName($name);
        } catch (Exception $e) {
            error_log("Lỗi trong getCategoryByName service: " . $e->getMessage());
            throw new Exception("Không thể lấy danh mục: " . $e->getMessage());
        }
    }

    /**
     * Lấy thương hiệu theo ID
     * @param int $id
     * @return array|null Thông tin thương hiệu
     */
    public function getBrandById($id)
    {
        try {
            // Giả định ProductService có phương thức getBrandById
            return $this->productService->getBrandById($id);
        } catch (Exception $e) {
            error_log("Lỗi trong getBrandById service: " . $e->getMessage());
            throw new Exception("Không thể lấy thương hiệu: " . $e->getMessage());
        }
    }

    /**
     * Lấy danh mục theo ID
     * @param int $id
     * @return array|null Thông tin danh mục
     */
    public function getCategoryById($id)
    {
        try {
            // Giả định ProductService có phương thức getCategoryById
            return $this->productService->getCategoryById($id);
        } catch (Exception $e) {
            error_log("Lỗi trong getCategoryById service: " . $e->getMessage());
            throw new Exception("Không thể lấy danh mục: " . $e->getMessage());
        }
    }
}