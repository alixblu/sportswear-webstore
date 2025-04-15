<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
/**=======src of product include VARIANT and CATEGORY AND BRAND====
 * 
 * 
 * Functions in ProductRepository:
 * - findAll() - Get all products with their variants
 * - findById($id) - Find a product by ID with its variants
 * - getProductVariants($productId) - Get all variants of a specific product
 * - getAllProducts() - Get all products without variants
 * - getProductById($id) - Get a product by ID without variants
 * - updateProduct($id, $data) - Update a product's information
 * - updateProductStock($productId) - Update product stock based on variant quantities
 */
class ProductRepository
{

    public function __construct()
    {
        $this->conn = ConfigMysqli::connectDatabase();
    }
    /**
     * Get all products with their variants
     * @return array Products with their variants
     * @throws Exception If database error occurs
     */
    public function findAll()
    {
        $conn = null;
        $stmt = null;
        try {
            $mysql = new configMysqli();
            $conn = $mysql->connectDatabase();

            $stmt = $conn->prepare("
                SELECT 
                    p.ID as productID,
                    p.categoryID,
                    p.discountID,
                    p.brandID,
                    p.name as productName,
                    p.markup_percentage,
                    p.rating,
                    p.image,
                    p.description,
                    p.stock,
                    p.status as productStatus,
                    pv.ID as variantID,
                    pv.Code,
                    pv.fullName,
                    pv.quantity,
                    pv.color,
                    pv.size,
                    pv.weight,
                    pv.price,
                    pv.status as variantStatus
                FROM product p
                LEFT JOIN productvariant pv ON p.ID = pv.productID
                ORDER BY p.ID, pv.Code
            ");

            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $products = [];
            while ($row = $result->fetch_assoc()) {
                $productId = $row['productID'];
                if (!isset($products[$productId])) {
                    $products[$productId] = [
                        'id' => $row['productID'],
                        'categoryID' => $row['categoryID'],
                        'discountID' => $row['discountID'],
                        'brandID' => $row['brandID'],
                        'name' => $row['productName'],
                        'markup_percentage' => $row['markup_percentage'],
                        'rating' => $row['rating'],
                        'image' => $row['image'],
                        'description' => $row['description'],
                        'stock' => $row['stock'],
                        'status' => $row['productStatus'],
                        'variants' => []
                    ];
                }

                if ($row['variantID']) {
                    $products[$productId]['variants'][] = [
                        'id' => $row['variantID'],
                        'code' => $row['Code'],
                        'fullName' => $row['fullName'],
                        'quantity' => $row['quantity'],
                        'color' => $row['color'],
                        'size' => $row['size'],
                        'weight' => $row['weight'],
                        'price' => $row['price'],
                        'status' => $row['variantStatus']
                    ];
                }
            }

            return array_values($products);
        } catch (Exception $e) {
            error_log("Database error in findAll: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            if ($conn) $conn->close();
        }
    }

    /**checked
     * Find a product by ID with its variants
     * @param int $id Product ID
     * @return array|null Product with variants if found, null otherwise
     * @throws Exception If database error occurs
     */
    public function findById($id)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    p.ID as productID,
                    p.categoryID,
                    p.discountID,
                    p.brandID,
                    p.name as productName,
                    p.markup_percentage,
                    p.rating,
                    p.image,
                    p.description,
                    p.stock,
                    p.status as productStatus,
                    pv.ID as variantID,
                    pv.Code,
                    pv.fullName,
                    pv.quantity,
                    pv.color,
                    pv.size,
                    pv.weight,
                    pv.price,
                    pv.status as variantStatus
                FROM product p
                LEFT JOIN productvariant pv ON p.ID = pv.productID
                WHERE p.ID = ?
                ORDER BY pv.Code
            ");

            if (!$stmt) {
                throw new Exception("Database error: " . $this->conn->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $product = null;
            while ($row = $result->fetch_assoc()) {
                if (!$product) {
                    $product = [
                        'id' => $row['productID'],
                        'categoryID' => $row['categoryID'],
                        'discountID' => $row['discountID'],
                        'brandID' => $row['brandID'],
                        'name' => $row['productName'],
                        'markup_percentage' => $row['markup_percentage'],
                        'rating' => $row['rating'],
                        'image' => $row['image'],
                        'description' => $row['description'],
                        'stock' => $row['stock'],
                        'status' => $row['productStatus'],
                        'variants' => []
                    ];
                }

                if ($row['variantID']) {
                    $product['variants'][] = [
                        'id' => $row['variantID'],
                        'code' => $row['Code'],
                        'fullName' => $row['fullName'],
                        'quantity' => $row['quantity'],
                        'color' => $row['color'],
                        'size' => $row['size'],
                        'weight' => $row['weight'],
                        'price' => $row['price'],
                        'status' => $row['variantStatus']
                    ];
                }
            }

            return $product;
        } catch (Exception $e) {
            error_log("Database error in findById: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            if ($this->conn) $this->conn->close();
        }
    }

    private $conn;

    //checked
    public function getProductVariants($productId)
    {
        try {
            $query = "SELECT v.*
                      FROM productvariant v

                      WHERE v.productID = ?
                      ORDER BY v.ID";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();

            $variants = [];
            while ($row = $result->fetch_assoc()) {
                $variants[] = $row;
            }

            return $variants;
        } catch (Exception $e) {
            error_log("Error in getProductVariants: " . $e->getMessage());
            throw new Exception("Failed to get product variants");
        }
    }

    /**
     * Get all products without variants
     * @return array List of products
     * @throws Exception If database error occurs
     */
    public function getAllProducts()
    {
        try {
            $query = "SELECT * FROM product ORDER BY ID";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            return $products;
        } catch (Exception $e) {
            error_log("Error in getAllProducts: " . $e->getMessage());
            throw new Exception("Failed to get products");
        }
    }

    /**
     * Get a product by ID without variants
     * @param int $id Product ID
     * @return array|null Product data if found, null otherwise
     * @throws Exception If database error occurs
     */
    public function getProductById($id)
    {
        try {
            $query = "SELECT * FROM product WHERE ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getProductById: " . $e->getMessage());
            throw new Exception("Failed to get product");
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
            $fields = [];
            $types = "";
            $values = [];

            foreach ($data as $key => $value) {
                $fields[] = "$key = ?";
                $types .= is_int($value) ? "i" : (is_float($value) ? "d" : "s");
                $values[] = $value;
            }

            $query = "UPDATE product SET " . implode(", ", $fields) . " WHERE ID = ?";
            $types .= "i";
            $values[] = $id;

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param($types, ...$values);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateProduct: " . $e->getMessage());
            throw new Exception("Failed to update product");
        }
    }

    /**
     * Update product stock based on variant quantities
     * @param int $productId Product ID
     * @return bool True if successful, false otherwise
     * @throws Exception If database error occurs
     */
    public function updateProductStock($productId)
    {
        try {
            // Get all variants for the product
            $variants = $this->getProductVariants($productId);
            if (empty($variants)) {
                throw new Exception("No variants found for product ID: $productId");
            }

            // Calculate total stock from variants
            $totalStock = 0;
            foreach ($variants as $variant) {
                $totalStock += $variant['quantity'];
            }

            // Update product stock
            $query = "UPDATE product SET stock = ? WHERE ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $totalStock, $productId);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateProductStock: " . $e->getMessage());
            throw new Exception("Failed to update product stock");
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
            $query = "SELECT * FROM category WHERE ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getCategoryById: " . $e->getMessage());
            throw new Exception("Failed to get category");
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
            $query = "SELECT * FROM brand WHERE ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getBrandById: " . $e->getMessage());
            throw new Exception("Failed to get brand");
        }
    }

    public function deleteProduct($id)
    {
        try {
            $query = "DELETE FROM product WHERE ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in deleteProduct: " . $e->getMessage());
            throw new Exception("Failed to delete product");
        }
    }
}
