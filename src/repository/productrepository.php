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
                    pv.id AS productVariantID,
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
     * Get product by id
     */
    public function getProductById($id)
    {
        try {
            $query = "SELECT p.*, MIN(pv.price) as basePrice FROM product as p LEFT JOIN productvariant as pv ON p.ID = pv.productID WHERE p.ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getProductById:" . $e->getMessage());
            throw new Exception("Failed to get product bby id !!!");
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
    public function getFilteredProductsAdmin($page, $limit, $search, $category, $brand, $status, $rating)
    {
        try {
            $offset = ($page - 1) * $limit;
            // Get product list
            $query = "SELECT p.*, MIN(pv.price) as basePrice FROM product as p LEFT JOIN productvariant as pv ON pv.productID=p.ID WHERE 1=1";
            $params = [];
            // Get total amount
            $count_query = "SELECT COUNT(DISTINCT p.ID) as total from product as p WHERE 1=1";
            $count_params = [];

            if ($category) {
                $count_query .= " AND p.categoryID=?";
                $query .= " AND p.categoryID=?";
                $params[] = $count_params[] = $category;
            }
            if ($brand) {
                $count_query .= " AND p.brandID=?";
                $query .= " AND p.brandID=?";
                $params[] = $count_params[] = $brand;
            }
            if ($status) {
                $count_query .= " AND p.status=?";
                $query .= " AND p.status=?";
                $params[] = $count_params[] = $status;
            }
            if ($rating) {
                $count_query .= ($rating == 1) ? " AND p.rating=?" : " AND p.rating>=? AND p.rating<=?";
                $query .= ($rating == 1) ? " AND p.rating=?" : " AND p.rating>=? AND p.rating<=?";
                $params[] = $count_params[] = $rating - 1;
                if ($rating != 1)
                    $params[] = $count_params[] = $rating;
            }
            if ($search) {
                $count_query .= " AND p.name LIKE ?";
                $query .= " AND p.name LIKE ?";
                $params[] = $count_params[] = '%' . $search . '%';
            }
            $query .= " GROUP BY p.ID LIMIT ?, ?";
            $params[] = (int)$offset;
            $params[] = (int)$limit;

            // Execute get poructs query
            $stmt1 = $this->conn->prepare($query);
            if (!$stmt1)
                throw new Exception('Prepare get product list failed!!!');
            $stmt1->execute($params);
            $result1 = $stmt1->get_result();

            $products = [];
            while ($row = $result1->fetch_assoc()) {
                $products[] = $row;
            }
            // Execute get amount of poructs query
            $stmt2 = $this->conn->prepare($count_query);
            if (!$stmt2)
                throw new Exception('Prepare get amount of products failed!!!');
            $stmt2->execute($count_params);
            $total = ($stmt2->get_result()->fetch_assoc()['total']);

            return [
                'data' => $products,
                'total' => $total,
                'page' => $page,
                'limit' => $limit
            ];
        } catch (Exception $e) {
            error_log("Error in getFilteredProductsAdmin: " . $e->getMessage());
            throw new Exception("Failed to get filtered products ADMIN: " . $e->getMessage());
        } finally {
            $this->conn->close();
        }
    }

    /**
     *  Get products with options (CLIENT)
     * @return array List of products
     * @param $category : category of product
     * @param $brand : brand of product
     * @param $status : status of product
     * @param $min_price : min price of product
     * @param $max_price : max price of product
     * @param $sort: sort option of product
     * @param $search : name of product
     */
    public function getFilteredProducts($category, $brand, $status, $min_price, $max_price, $sort = 'newest', $search = null)
    {
        try {
            $query = "
                SELECT 
                    p.*, 
                    pv.id AS productVariantID,
                    MIN(pv.price) AS price,
                    COALESCE(p.rating, 0) AS rating,
                    COALESCE(p.markup_percentage, 0) AS markup_percentage
                FROM product p
                LEFT JOIN productvariant pv ON p.ID = pv.productID
                WHERE 1=1
            ";
            $types = "";
            $params = [];

            if ($category && is_numeric($category)) {
                $query .= " AND p.categoryID = ?";
                $types .= "i";
                $params[] = $category;
            }
            if ($brand && is_numeric($brand)) {
                $query .= " AND p.brandID = ?";
                $types .= "i";
                $params[] = $brand;
            }
            if ($status) {
                $query .= " AND p.status = ?";
                $types .= "s";
                $params[] = $status;
            }
            if ($min_price && is_numeric($min_price)) {
                $query .= " AND (pv.price * (1 + COALESCE(p.markup_percentage, 0) / 100)) >= ?";
                $types .= "d";
                $params[] = $min_price;
            }
            if ($max_price && is_numeric($max_price)) {
                $query .= " AND (pv.price * (1 + COALESCE(p.markup_percentage, 0) / 100)) <= ?";
                $types .= "d";
                $params[] = $max_price;
            }
            if ($search && !empty($search)) {
                $query .= " AND p.name LIKE ?";
                $types .= "s";
                $params[] = "%" . $search . "%";
            }

            // Xử lý sắp xếp
            $sort_map = [
                'newest' => 'p.ID DESC',
                'price_asc' => 'MIN(pv.price * (1 + COALESCE(p.markup_percentage, 0) / 100)) ASC',
                'price_desc' => 'MIN(pv.price * (1 + COALESCE(p.markup_percentage, 0) / 100)) DESC',
                'rating_desc' => 'COALESCE(p.rating, 0) DESC'
            ];
            $sort_order = isset($sort_map[$sort]) ? $sort_map[$sort] : 'p.ID DESC';
            $query .= " GROUP BY p.ID ORDER BY $sort_order";

            $stmt = $this->conn->prepare($query);
            if (!empty($types) && !empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            return $products;
        } catch (Exception $e) {
            error_log("Error in getFilteredProducts: " . $e->getMessage());
            throw new Exception("Failed to get filtered products: " . $e->getMessage());
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
            $query = "SELECT c.* FROM category as c WHERE c.parent IS NOT NULL ORDER BY name";
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
    /**
     * Get all discounts
     * @return array List of discounts
     * @throws Exception If database error occurs
     */
    public function getAllDiscounts()
    {
        try {
            $query = "SELECT * FROM discount";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            $discounts = [];
            while ($row = $result->fetch_assoc()) {
                $discounts[] = $row;
            }

            return $discounts;
        } catch (Exception $e) {
            error_log("Error in getAllDiscounts: " . $e->getMessage());
            throw new Exception("Failed to get discounts");
        }
    }
    /**
     * Get discount by id
     * @return $discount
     * @param int $id
     * @throws Exception If database error occurs
     */
    public function getDiscountByID($id)
    {
        try {
            $query = "SELECT * FROM discount WHERE ID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getDiscountByID: " . $e->getMessage());
            throw new Exception("Failed to get discount");
        }
    }

    public function createProduct($data)
    {
        try {
            $query = "INSERT INTO product (name, categoryID, brandID, discountID, markup_percentage, description, image)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param(
                "siiidss",
                $data['name'],
                $data['categoryID'],
                $data['brandID'],
                $data['discountID'],
                $data['markup_percentage'],
                $data['description'],
                $data['image']
            );
            if ($stmt->execute()) {
                // Get the last inserted ID (product ID)
                $productID = $this->conn->insert_id;
                return $productID; // Return the new product ID
            } else {
                throw new Exception("Error inserting product: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error in getDiscountByID: " . $e->getMessage());
            throw new Exception("Failed to get discount");
        }
    }
}
