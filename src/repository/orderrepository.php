<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
include dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class OrderRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    public function getAllOrders() {
        try {
        $query = "
            SELECT 
                o.ID, 
                o.status, 
                o.totalPrice, 
                o.createdAt,
                customer.fullname AS customerName, 
                approver.fullname AS approverName, 
                pm.name AS paymentMethod
            FROM `order` o
            LEFT JOIN user customer ON o.customer = customer.ID
            LEFT JOIN user approver ON o.approver = approver.ID
            LEFT JOIN payment p ON p.orderID = o.ID
            LEFT JOIN paymentmethod pm ON pm.ID = p.paymentMethodID
            ORDER BY o.createdAt DESC
        ";
        $result = $this->conn->query($query);

        $order = [];
        while ($row = $result->fetch_assoc()) {
            $order[] = $row;
        }
        return $order;
    } catch (Exception $e) {
        error_log("Lỗi lấy danh sách đơn hàng: " . $e->getMessage());
        throw $e;
    } finally {
        if ($this->conn) $this->conn->close();
    }
    }
    
    public function getOrderDetails($id) {
        $sql = "
            SELECT 
                o.ID, 
                o.createdAt, 
                b.receiverName, 
                b.address, 
                b.phone, 
                b.email, 
                pr.name AS productName,     
                od.quantity, 
                od.totalPrice AS productTotal, 
                pm.name AS paymentMethod                 
            FROM `order` o
            LEFT JOIN billingdetail b ON o.ID = b.orderID
            LEFT JOIN orderdetail od ON o.ID = od.orderID
            LEFT JOIN product pr ON od.productID = pr.ID
            LEFT JOIN payment p ON o.ID = p.orderID
            LEFT JOIN paymentmethod pm ON pm.ID = p.paymentMethodID
            WHERE o.ID = ?
        ";
    
        // Chuẩn bị và thực thi câu lệnh SQL
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        // Lấy kết quả trả về dưới dạng mảng
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
        //cập nhật trạng thái đơn hànghàng
    public function updateOrderStatus($orderID, $status) {
        $sql = "UPDATE `order` SET status = ? WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $orderID);
        return $stmt->execute();
    }

    public function updateBillingDetail($orderID, $receiverName, $address, $phone, $email) {
        $sql = "UPDATE billingdetail 
                SET receiverName = ?, address = ?, phone = ?, email = ? 
                WHERE orderID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $receiverName, $address, $phone, $email, $orderID);
        return $stmt->execute();
    }
    

    // 🔍 Tìm kiếm theo ID, tên khách hàng, thời gian
    public function searchOrders($orderID = null, $customerName = '', $fromDate = '', $toDate = '') {
        $sql = "
            SELECT o.ID, o.status, o.totalPrice, o.createdAt, u.fullname AS customerName
            FROM `order` o
            LEFT JOIN user u ON o.customer = u.ID  
            WHERE 1=1
        ";
    
        $params = [];
        $types = "";
    
        // Lọc theo orderID nếu có
        if (!empty($orderID)) {
            $sql .= " AND o.ID = ?";
            $types .= "i";
            $params[] = $orderID;
        }
    
        // Lọc theo tên khách hàng nếu có
        if (!empty($customerName)) {
            $sql .= " AND u.fullname LIKE ?";  // Lọc theo fullname của khách hàng
            $types .= "s";
            $params[] = "%" . $customerName . "%";
        }
    
        // Lọc theo ngày bắt đầu nếu có
        if (!empty($fromDate)) {
            $sql .= " AND o.createdAt >= ?";
            $types .= "s";
            $params[] = $fromDate;
        }
    
        // Lọc theo ngày kết thúc nếu có
        if (!empty($toDate)) {
            $sql .= " AND o.createdAt <= ?";
            $types .= "s";
            $params[] = $toDate;
        }
    
        // Sắp xếp theo ngày tạo đơn giảm dần
        $sql .= " ORDER BY o.createdAt DESC";
    
        // Chuẩn bị và thực thi câu lệnh SQL
        $stmt = $this->conn->prepare($sql);
    
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }
    
        // Bind tham số nếu có
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
    
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
