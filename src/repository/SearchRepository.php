<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';

class SearchRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    // Repository này có thể được mở rộng trong tương lai nếu cần thêm truy vấn tìm kiếm đặc biệt
    // Hiện tại, các truy vấn liên quan đến tìm kiếm được xử lý bởi ProductRepository thông qua ProductService

    public function getBrandById($id)
    {
        $query = "SELECT * FROM brands WHERE ID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $brand = $result->fetch_assoc();
        $stmt->close();
        return $brand;
    }

    /**
     * Lấy danh mục theo ID
     * @param int $id
     * @return array|null
     */
    public function getCategoryById($id)
    {
        $query = "SELECT * FROM categories WHERE ID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();
        $stmt->close();
        return $category;
    }
}

