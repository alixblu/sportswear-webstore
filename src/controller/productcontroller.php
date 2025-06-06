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
     *  Get products with options (ADMIN)
     * @return array List of products
     * @param $category : category of product
     * @param $brand : brand of product
     * @param $status : status of product
     * @param $min_price : min price of product
     * @param $max_price : max price of product
     * @param $sort: sort option of product
     * @param $search : name of product
     */
    public function getFilteredProductsAdmin($page, $productsPerPage, $search, $category, $brand, $status, $rating)
    {
        $products = null;
        try {
            $products =  $this->productService->getFilteredProductsAdmin($page, $productsPerPage, $search, $category, $brand, $status, $rating);
            ApiResponse::customApiResponse($products, 200);
            return $products;
        } catch (Exception $e) {
            ApiResponse::customApiResponse($products, 500, $e->getMessage());
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
    public function getFilteredProducts($category, $brand, $status, $min_price, $max_price, $sort, $search)
    {
        $products = null;
        try {
            $products =  $this->productService->getFilteredProducts($category, $brand, $status, $min_price, $max_price, $sort, $search);
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
                if (isset($result['action']) && $result['action'] === 'discontinued') {
                    ApiResponse::customResponse(
                        ['id' => $id, 'action' => 'discontinued'],
                        200,
                        'Product marked as discontinued because it exists in order history'
                    );
                } else {
                    ApiResponse::customResponse(
                        ['id' => $id, 'action' => 'deleted'],
                        200,
                        'Product deleted successfully'
                    );
                }
            } else {
                ApiResponse::customResponse($id, 500, 'Failed to process product');
            }
        } catch (Exception $e) {
            ApiResponse::customResponse($id, 500, $e->getMessage());
        }
    }

    /**
     * Handle POST request to restore a discontinued product
     * @param int $id Product ID
     */
    public function restoreProduct($id)
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

            $result = $this->productService->restoreProduct($id);

            if ($result) {
                if (isset($result['action'])) {
                    switch ($result['action']) {
                        case 'restored':
                            ApiResponse::customResponse(['id' => $id, 'action' => 'restored'], 200, 
                                'Product restored successfully');
                            break;
                        case 'not_discontinued':
                            ApiResponse::customResponse(['id' => $id, 'action' => 'not_discontinued'], 400, 
                                'Product is not discontinued');
                            break;
                        case 'not_found':
                            ApiResponse::customResponse(['id' => $id, 'action' => 'not_found'], 404, 
                                'Product not found');
                            break;
                        default:
                            ApiResponse::customResponse($id, 500, 'Unknown action result');
                    }
                } else {
                    ApiResponse::customResponse($id, 500, 'Invalid response from service');
                }
            } else {
                ApiResponse::customResponse($id, 500, 'Failed to restore product');
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
            return ApiResponse::customResponse(null, 500, 'Failed to fetch product variants: ' . $e->getMessage());
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
    /**
     * Get all discounts
     * @return void
     */
    public function getAllDiscounts()
    {
        try {
            $discounts = $this->productService->getAllDiscounts();
            ApiResponse::customApiResponse($discounts, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
    }
    /**
     * Get discount by id
     * @param int $id
     * @return void
     */
    public function getDiscountByID($id)
    {
        try {
            $discount = $this->productService->getDiscountByID($id);
            ApiResponse::customApiResponse($discount, 200);
        } catch (Exception $e) {
            ApiResponse::customApiResponse(null, 500, $e->getMessage());
        }
    }

    /**
     * Insert new product
     * @return message result_message, int $id of new product
     * @throws Exception IF db error occurs 
     */
    public function createProduct($jsonData)
    {
        try {
            if (!$jsonData) {
                ApiResponse::customResponse(null, 400, "Không nhận được dữ liệu JSON");
                return;
            }
            $result = $this->productService->createProduct($jsonData);
            ApiResponse::customApiResponse($result, 200);
        } catch (Exception $e) {
            ApiResponse::customResponse(null, 500, $e->getMessage());
        }
    }
}
