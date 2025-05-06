<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
/**============src of products include VARIANT and CATEGORY AND BRAND ====
 * 
 * 
 * Functions in ProductRepository:

 * - getProductVariants($productId) - Get all variants of a specific product
 * - getAllProducts() - Get all products without variants
 * - getProductById($id) - Get a product by ID without variants
 * - updateProduct($id, $data) - Update a product's information
 * - updateProductStock($productId) - Update product stock based on variant quantities
 * ..............
 */
class ProductRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = ConfigMysqli::connectDatabase();
    }


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
            $query = "
                SELECT 
                    p.*, 
                    MIN(pv.price) AS price
                FROM product p
                LEFT JOIN productvariant pv ON p.ID = pv.productID
                GROUP BY p.ID
                ORDER BY p.ID
            ";
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
     *  Get products with options
     * @return array List of products
     * @param $category : category of product
     * @param $brand : brand of product
     * @param $status : status of product
     * @param $min_price : min price of product
     * @param $max_price : max price of product
     */
    public function getFilteredProducts($category, $brand, $status, $min_price, $max_price)
    {
        try {
            $query = "
                SELECT * FROM product AS p
                JOIN productvariant as pv
                WHERE 1=1
            ";
            $params = [];
            if ($category) {
                $query .= "AND p.categoryID=?";
                $params[] = $category;
            }
            if ($brand) {
                $query .= "AND p.brandID=?";
                $params[] = $brand;
            }
            if ($status) {
                $query .= "AND status=?";
                $params[] = $status;
            }
            if ($min_price) {
                $query .= "pv.price>=?";
                $params[] = $min_price;
            }

            if ($max_price) {
                $query .= "pv.price<=?";
                $params[] = $max_price;
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $result = $stmt->get_result();

            $products = [];
            while ($row = $result->fetch_assoc())
                $products[] = $row;
            return $products;
        } catch (Exception $e) {
            throw new Exception('');
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
    public function getBrandByName($name)
    {
        try {
            $query = "SELECT * FROM brand WHERE name = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getBrandByName: " . $e->getMessage());
            throw new Exception("Failed to get brand by name");
        }
    }

    /**
     * Get all categories
     * @return array List of categories
     * @throws Exception If database error occurs
     */
    public function getAllCategories()
    {
        try {
            $query = "SELECT * FROM category ORDER BY name";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            $categories = [];
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }

            return $categories;
        } catch (Exception $e) {
            error_log("Error in getAllCategories: " . $e->getMessage());
            throw new Exception("Failed to get categories");
        }
    }

    public function getCategoryByName($name)
    {
        try {
            $query = "SELECT * FROM category WHERE name = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getCategoryByName: " . $e->getMessage());
            throw new Exception("Failed to get category by name");
        }
    }

    /**
     * Get all brands
     * @return array List of brands
     * @throws Exception If database error occurs
     */
    public function getAllBrands()
    {
        try {
            $query = "SELECT * FROM brand ORDER BY name";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            $brands = [];
            while ($row = $result->fetch_assoc()) {
                $brands[] = $row;
            }

            return $brands;
        } catch (Exception $e) {
            error_log("Error in getAllBrands: " . $e->getMessage());
            throw new Exception("Failed to get brands");
        }
    }

    /**
     * Delete product by ID
     * @param int $id : productID
     * @return bool statement of success
     * @throws Exception If any errors occurs
     */
    public function deleteProduct($id)
    {
        try {
            // Delete all variants first
            $query1 = "DELETE FROM productvariant WHERE productID=?";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bind_param("i", $id);
            $stmt1->execute();

            // Then delete product 
            $query2 = "DELETE FROM product WHERE id=?";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bind_param("i", $id);
            $stmt2->execute();

            return $stmt2->affected_rows > 0;
        } catch (Exception $error) {
            error_log("ProductRepository - An error occurs - Delete product" . $error->getMessage());
            throw new Exception("ProductRepository - Failed to delete product");
        }
    }

    /**
     * Delete productVariant by ID
     * @param int $id : productVariantID
     * @return bool statement of success
     * @throws Exception If any errors occurs
     */
    public function deleteProductVariant($id)
    {
        try {
            $query = "DELETE FROM productVariant WHERE id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            return $stmt->affected_rows > 0;
        } catch (Exception $error) {
            error_log("ProductRepository - An error occurs - Delete productVariant" . $error->getMessage());
            throw new Exception("ProductRepository - Failed to delete product variant");
        }
    }
}
