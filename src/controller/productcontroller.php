<?php
require_once dirname(__FILE__) . '/../service/productservice.php';

class ProductController {
    private $productService;

    public function __construct() {
        $this->productService = new ProductService();
    }

    /**
     * Handle GET request to get all products
     */
    public function getAllProducts() {
        try {
            $products = $this->productService->getAllProducts();
            $this->sendJsonResponse(200, [
                'success' => true,
                'data' => $products
            ]);
        } catch (Exception $e) {
            $this->sendJsonResponse(500, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle GET request to get a product by ID
     * @param int $id Product ID
     */
    public function getProductById($id) {
        try {
            $product = $this->productService->getProductById($id);
            if (!$product) {
                $this->sendJsonResponse(404, [
                    'success' => false,
                    'message' => 'Product not found'
                ]);
                return;
            }
            
            $this->sendJsonResponse(200, [
                'success' => true,
                'data' => $product
            ]);
        } catch (Exception $e) {
            $this->sendJsonResponse(500, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle PUT request to update a product
     * @param int $id Product ID
     * @param array $data Product data to update
     */
    public function updateProduct($id, $data) {
        try {
            if (!isset($id) || !is_numeric($id)) {
                $this->sendJsonResponse(400, [
                    'success' => false,
                    'message' => 'Invalid product ID'
                ]);
                return;
            }

            if (empty($data)) {
                $this->sendJsonResponse(400, [
                    'success' => false,
                    'message' => 'No data provided for update'
                ]);
                return;
            }

            $result = $this->productService->updateProduct($id, $data);
            
            if ($result) {
                $this->sendJsonResponse(200, [
                    'success' => true,
                    'message' => 'Product updated successfully'
                ]);
            } else {
                $this->sendJsonResponse(500, [
                    'success' => false,
                    'message' => 'Failed to update product'
                ]);
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(500, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle DELETE request to delete a product
     * @param int $id Product ID
     */
    public function deleteProduct($id) {
        try {
            if (!isset($id) || !is_numeric($id)) {
                $this->sendJsonResponse(400, [
                    'success' => false,
                    'message' => 'Invalid product ID'
                ]);
                return;
            }

            $result = $this->productService->deleteProduct($id);
            
            if ($result) {
                $this->sendJsonResponse(200, [
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ]);
            } else {
                $this->sendJsonResponse(500, [
                    'success' => false,
                    'message' => 'Failed to delete product'
                ]);
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(500, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle PUT request to update product stock
     * @param int $productId Product ID
     */
    public function updateProductStock($productId) {
        try {
            if (!isset($productId) || !is_numeric($productId)) {
                $this->sendJsonResponse(400, [
                    'success' => false,
                    'message' => 'Invalid product ID'
                ]);
                return;
            }

            $result = $this->productService->updateProductStock($productId);
            
            if ($result) {
                $this->sendJsonResponse(200, [
                    'success' => true,
                    'message' => 'Product stock updated successfully'
                ]);
            } else {
                $this->sendJsonResponse(500, [
                    'success' => false,
                    'message' => 'Failed to update product stock'
                ]);
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(500, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send JSON response with appropriate headers
     * @param int $statusCode HTTP status code
     * @param array $data Response data
     */
    private function sendJsonResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function getProductVariants($productId) {
        try {
            if (!isset($productId)) {
                return json_encode([
                    'success' => false,
                    'message' => 'Product ID is required'
                ]);
            }

            $variants = $this->productService->getProductVariants($productId);

            return json_encode([
                'success' => true,
                'data' => $variants
            ]);
        } catch (Exception $e) {
            error_log("Error in getProductVariants controller: " . $e->getMessage());
            return json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
?> 