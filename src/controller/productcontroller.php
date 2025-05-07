<?php
require_once dirname(__FILE__) . '/../service/productservice.php';
include_once  dirname(__FILE__) . '/../config/response/apiresponse.php';

class ProductController
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * Handle GET request to get all products
     */
    public function getAllProducts()
    {
        $products = null;
        try {
            $products = $this->productService->getAllProducts();
            ApiResponse::customApiResponse($products, 200);
            return $products;
        } catch (Exception $e) {
            ApiResponse::customResponse($products, 500, $e->getMessage());
        }
    }

    /**
     * Get products with options
     * @return array List of products
     * @param $category : category of product
     * @param $brand : brand of product
     * @param $status : status of product
     * @param $min_price : min price of product
     * @param $max_price : max price of product
     */
    public function getFilteredProducts($category, $brand, $status, $rating, $min_price, $max_price)
    {
        $products = null;
        try {
            $products =  $this->productService->getFilteredProducts($category, $brand, $status, $rating, $min_price, $max_price);
            ApiResponse::customApiResponse($products, 200);
            return $products;
        } catch (Exception $e) {
            ApiResponse::customApiResponse($products, 500, $e->getMessage());
        }
    }

    /**
     * Handle GET request to get a product by ID
     * @param int $id Product ID
     */
    public function getProductById($id)
    {
        try {
            $product = $this->productService->getProductById($id);
            if (!$product) {
                ApiResponse::customResponse($product, 404, 'Product not found');
                return;
            }
            ApiResponse::customResponse($product, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse($product, 500, $e->getMessage());
        }
    }

    /**
     * Handle PUT request to update a product
     * @param int $id Product ID
     * @param array $data Product data to update
     */
    public function updateProduct($id, $data)
    {
        try {
            if (!isset($id) || !is_numeric($id)) {
                ApiResponse::customResponse($data, 400, 'Invalid product ID');
                return;
            }

            if (empty($data)) {
                /*
                $this->sendJsonResponse(400, [
                    'success' => false,
                    'message' => 'No data provided for update'
                ]);
                */
                ApiResponse::customResponse(null, 400, 'No data provided for update');
                return;
            }

            $result = $this->productService->updateProduct($id, $data);

            if ($result) {
                ApiResponse::customResponse($data, 200, 'Product updated successfully');
            } else {
                ApiResponse::customResponse($data, 500, 'Failed to update product');
            }
        } catch (Exception $e) {
            ApiResponse::customResponse($data, 500, $e->getMessage());
        }
    }

    /**
     * Handle DELETE request to delete a product
     * @param int $id Product ID
     */
    public function deleteProduct($id)
    {
        try {
            if (!isset($id) || !is_numeric($id)) {
                ApiResponse::customResponse($id, 400, 'Invalid product ID');
                return;
            }

            $product = $this->productService->getProductById($id);
            if (!$product) {
                ApiResponse::customResponse($id, 404, 'Product not found');
                return;
            }

            $result = $this->productService->deleteProduct($id);

            if ($result) {
                ApiResponse::customResponse($id, 200, 'Product deleted successfully');
            } else {
                ApiResponse::customResponse($id, 500, 'Failed to delete product');
            }
        } catch (Exception $e) {
            ApiResponse::customResponse($id, 500, $e->getMessage());
        }
    }

    /**
     * Handle PUT request to update product stock
     * @param int $productId Product ID
     */
    public function updateProductStock($productId)
    {
        try {
            if (!isset($productId) || !is_numeric($productId)) {
                ApiResponse::customResponse($productId, 400, 'Invalid product ID');
                return;
            }

            $result = $this->productService->updateProductStock($productId);

            if ($result) {
                ApiResponse::customResponse($productId, 200, 'Product stock updated successfully');
            } else {
                ApiResponse::customResponse($productId, 500, 'Failed to update product stock');
            }
        } catch (Exception $e) {
            ApiResponse::customResponse($productId, 500, $e->getMessage());
        }
    }

    // private function sendJsonResponse($statusCode, $data) {
    //     http_response_code($statusCode);
    //     header('Content-Type: application/json');
    //     echo json_encode($data);
    // }

    public function getProductVariants($productId)
    {
        try {
            if (!isset($productId)) {
                return ApiResponse::customResponse($productId, 400, 'Invalid product ID');
            }

            $variants = $this->productService->getProductVariants($productId);

            return ApiResponse::customResponse($variants, 200, 'Product variants fetched successfully');
        } catch (Exception $e) {
            error_log("Error in getProductVariants controller: " . $e->getMessage());
            return ApiResponse::customResponse($productId, 500, 'Failed to fetch product variants: ' . $e->getMessage());
        }
    }

    /**
     * Handle GET request to get a category by ID
     * @param int $id Category ID
     */
    public function getCategoryById($id)
    {
        try {
            if (!isset($id) || !is_numeric($id)) {
                ApiResponse::customResponse($id, 400, 'Invalid category ID');
                return;
            }

            $category = $this->productService->getCategoryById($id);
            if (!$category) {
                ApiResponse::customResponse($category, 404, 'Category not found');
                return;
            }

            ApiResponse::customResponse($category, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse($id, 500, $e->getMessage());
        }
    }
    public function getBrandByName($name)
    {
        try {
            if (!isset($name)) {
                return ApiResponse::customResponse($name, 400, 'Invalid brand name');
            }

            $brand = $this->productService->getBrandByName($name);

            return ApiResponse::customResponse($brand, 200, 'Brand fetched successfully');
        } catch (Exception $e) {
            error_log("Error in getBrandByName controller: " . $e->getMessage());
            return ApiResponse::customResponse($name, 500, 'Failed to fetch brand: ' . $e->getMessage());
        }
    }
    public function getCategoryByName($name)
    {
        try {
            if (!isset($name)) {
                return ApiResponse::customResponse($name, 400, 'Invalid category name');
            }

            $category = $this->productService->getCategoryByName($name);

            return ApiResponse::customResponse($category, 200, 'Category fetched successfully');
        } catch (Exception $e) {
            error_log("Error in getCategoryByName controller: " . $e->getMessage());
            return ApiResponse::customResponse($name, 500, 'Failed to fetch category: ' . $e->getMessage());
        }
    }
    /**
     * Handle GET request to get a brand by ID
     * @param int $id Brand ID
     */
    public function getBrandById($id)
    {
        try {
            if (!isset($id) || !is_numeric($id)) {
                ApiResponse::customResponse($id, 400, 'Invalid brand ID');
                return;
            }

            $brand = $this->productService->getBrandById($id);
            if (!$brand) {
                ApiResponse::customResponse($brand, 404, 'Brand not found');
                return;
            }

            ApiResponse::customResponse($brand, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse($id, 500, $e->getMessage());
        }
    }

    /**
     * Get all categories
     * @return void
     */
    public function getAllCategories()
    {
        try {
            $categories = $this->productService->getAllCategories();
            ApiResponse::customResponse($categories, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }

    /**
     * Get all brands
     * @return void
     */
    public function getAllBrands()
    {
        try {
            $brands = $this->productService->getAllBrands();
            ApiResponse::customResponse($brands, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }
}
