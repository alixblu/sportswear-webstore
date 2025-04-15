<?php
require_once dirname(__FILE__) . '/../repository/productrepository.php';
/**
 * Functions in ProductService:
 * - getAllProducts() - Get all products without variants
 * - getProductById($id) - Get a product by ID without variants
 * - updateProduct($id, $data) - Update a product's information
 * - updateProductStock($productId) - Update product stock based on variant quantities
 * - updateAllProductStock() - Update stock for all products
 * - getProductVariants($productId) - Get all variants of a specific product
 */
class ProductService
{
    private $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    /**checked
     * Get all products without variants
     * @return array List of products
     * @throws Exception If database error occurs
     */
    public function getAllProducts()
    {
        try {
            return $this->productRepository->getAllProducts();
        } catch (Exception $e) {
            error_log("Error in getAllProducts service: " . $e->getMessage());
            throw new Exception("Failed to get products: " . $e->getMessage());
        }
    }

    /**checked
     * Get a product by ID without variants
     * @param int $id Product ID
     * @return array|null Product data if found, null otherwise
     * @throws Exception If database error occurs
     */
    public function getProductById($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid product ID");
            }

            return $this->productRepository->getProductById($id);
        } catch (Exception $e) {
            error_log("Error in getProductById service: " . $e->getMessage());
            throw new Exception("Failed to get product: " . $e->getMessage());
        }
    }

    /**
     * Update a product
     * @param int $id Product ID
     * @param array $data Product data to update
     * @return bool True if successful, false otherwise
     * @throws Exception If database error occurs
     */
    public function updateProduct($id, $data)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid product ID");
            }

            if (empty($data)) {
                throw new Exception("No data provided for update");
            }

            // Validate required fields
            $requiredFields = ['name', 'categoryID', 'brandID'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            return $this->productRepository->updateProduct($id, $data);
        } catch (Exception $e) {
            error_log("Error in updateProduct service: " . $e->getMessage());
            throw new Exception("Failed to update product: " . $e->getMessage());
        }
    }

    /**checked
     * Update product stock based on variant quantities
     * @param int $productId Product ID
     * @return bool True if successful, false otherwise
     * @throws Exception If database error occurs
     */
    public function updateProductStock($productId)
    {
        try {
            if (!is_numeric($productId) || $productId <= 0) {
                throw new Exception("Invalid product ID");
            }

            // Check if product exists
            $product = $this->productRepository->getProductById($productId);
            if (!$product) {
                throw new Exception("Product not found");
            }

            // Update stock
            $result = $this->productRepository->updateProductStock($productId);

            if (!$result) {
                throw new Exception("Failed to update product stock");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in updateProductStock service: " . $e->getMessage());
            throw new Exception("Failed to update product stock: " . $e->getMessage());
        }
    }

    //checked
    public function updateAllProductStock()
    {
        try {
            $products = $this->productRepository->getAllProducts();
            if (empty($products)) {
                throw new Exception("No products found");
            }

            foreach ($products as $product) {
                $this->updateProductStock($product['ID']);
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in updateAllProductStock service: " . $e->getMessage());
            throw new Exception("Failed to update all product stock: " . $e->getMessage());
        }
    }
    //checked
    public function getProductVariants($productId)
    {
        try {
            if (!is_numeric($productId) || $productId <= 0) {
                throw new Exception("Invalid product ID");
            }

            $variants = $this->productRepository->getProductVariants($productId);

            if (empty($variants)) {
                throw new Exception("No variants found for this product");
            }

            return $variants;
        } catch (Exception $e) {
            error_log("Error in getProductVariants service: " . $e->getMessage());
            throw new Exception("Failed to get product variants: " . $e->getMessage());
        }
    }

    /**
     * Get category by ID
     * @param int $id Category ID
     * @return array|null Category data if found, null otherwise
     * @throws Exception If database error occurs
     */
    public function getCategoryById($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid category ID");
            }

            return $this->productRepository->getCategoryById($id);
        } catch (Exception $e) {
            error_log("Error in getCategoryById service: " . $e->getMessage());
            throw new Exception("Failed to get category: " . $e->getMessage());
        }
    }

    /**
     * Get brand by ID
     * @param int $id Brand ID
     * @return array|null Brand data if found, null otherwise
     * @throws Exception If database error occurs
     */
    public function getBrandById($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid brand ID");
            }

            return $this->productRepository->getBrandById($id);
        } catch (Exception $e) {
            error_log("Error in getBrandById service: " . $e->getMessage());
            throw new Exception("Failed to get brand: " . $e->getMessage());
        }
    }


    public function deleteProduct($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid product ID");
            }

            return $this->productRepository->deleteProduct($id);
        } catch (Exception $e) {
            error_log("Error in deleteProduct service: " . $e->getMessage());
            throw new Exception("Failed to delete product: " . $e->getMessage());
        }
    }
}
