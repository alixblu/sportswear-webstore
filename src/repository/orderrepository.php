<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
include dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class OrderRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    public function getAllOrders()
    {
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

    public function getOrderDetails($id)
    {
        $sql = "
            SELECT 
                o.ID, 
                o.createdAt, 
                b.receiverName, 
                b.address, 
                b.phone, 
                b.email, 
                pr.fullname AS productName,     
                od.quantity, 
                od.totalPrice AS productTotal, 
                pm.name AS paymentMethod,
                c.name AS couponName,                  
                c.percent AS couponPercent,           
                c.duration AS couponDuration,         
                c.status AS couponStatus              
            FROM `order` o
            LEFT JOIN billingdetail b ON o.ID = b.orderID
            LEFT JOIN orderdetail od ON o.ID = od.orderID
            LEFT JOIN productvariant pr ON od.productID = pr.ID
            LEFT JOIN payment p ON o.ID = p.orderID
            LEFT JOIN paymentmethod pm ON pm.ID = p.paymentMethodID
            LEFT JOIN coupon c ON o.couponID = c.ID   -- Kết nối với bảng coupon
            WHERE o.ID = ?
        ";
    

        // Chuẩn bị và thực thi câu lệnh SQL
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Lấy kết quả trả về dưới dạng mảng
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($ID, $status)
    {
        $sql = "UPDATE `order` SET status = ? WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $ID);
        return $stmt->execute();
    }

    public function updateBillingDetail($orderID, $receiverName, $address, $phone, $email)
    {
        $sql = "UPDATE billingdetail 
                SET receiverName = ?, address = ?, phone = ?, email = ? 
                WHERE orderID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $receiverName, $address, $phone, $email, $orderID);
        return $stmt->execute();
    }

    // Tìm kiếm theo ID, tên khách hàng, trạng thái, thời gian
   public function searchOrders($orderID = null, $customerName = '', $status = '', $fromDate = '', $toDate = '')
    {
        $sql = "
            SELECT o.ID, o.status, o.totalPrice, o.createdAt, u.fullname AS customerName, pm.name AS paymentMethod
            FROM `order` o
            LEFT JOIN user u ON o.customer = u.ID
            LEFT JOIN payment p ON p.orderID = o.ID
            LEFT JOIN paymentmethod pm ON pm.ID = p.paymentMethodID
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
            $sql .= " AND u.fullname LIKE ?";
            $types .= "s";
            $params[] = "%" . $customerName . "%";
        }

        // Lọc theo trạng thái nếu có
        if (!empty($status)) {
            $sql .= " AND o.status = ?";
            $types .= "s";
            $params[] = $status;
        }

        // Lọc theo ngày bắt đầu nếu có
        if (!empty($fromDate)) {
            $sql .= " AND DATE(o.createdAt) >= ?";
            $types .= "s";
            $params[] = $fromDate;
        }

        // Lọc theo ngày kết thúc nếu có
        if (!empty($toDate)) {
            $sql .= " AND DATE(o.createdAt) <= ?";
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
    public function createOrder($customerId, $couponId, $totalPrice)
    {
        $query = "INSERT INTO `order` (`customer`, `couponID`, `totalPrice`) VALUES (?, ?, ?)";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Lỗi chuẩn bị truy vấn: ' . $this->conn->error];
        }
    
        if ($couponId === null) {
            $couponId = null;
            $types = "iid";
            $stmt->bind_param($types, $customerId, $couponId, $totalPrice);
        } else {
            $types = "iid"; 
            $stmt->bind_param($types, $customerId, $couponId, $totalPrice);
        }
    
        if ($stmt->execute()) {
            return ['success' => true, 'order_id' => $stmt->insert_id]; 
        } else {
            return ['success' => false, 'message' => 'Lỗi khi tạo đơn hàng: ' . $stmt->error];
        }
    }
    public function insertBillingDetail($orderID, $receiverName, $address, $phone, $email = null)
    {
        if ($orderID <= 0) {
            throw new Exception("Invalid orderID: $orderID");
        }

        $stmt = $this->conn->prepare("
            INSERT INTO billingdetail (orderID, receiverName, address, phone, email)
            VALUES (?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("issss", $orderID, $receiverName, $address, $phone, $email);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    }


    public function insertPayment($paymentMethodID, $orderID, $bankAccountID = null, $status = 'pending')
    {
        $query = "
            INSERT INTO payment (paymentMethodID, orderID, bankAccountID, status)
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Lỗi chuẩn bị truy vấn: ' . $this->conn->error];
        }

        $stmt->bind_param("iiis", $paymentMethodID, $orderID, $bankAccountID, $status);

        if ($stmt->execute()) {
            return ['success' => true, 'payment_id' => $stmt->insert_id];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi thêm thanh toán: ' . $stmt->error];
        }
    }
    public function insertOrderDetail($orderID, $productID, $quantity, $totalPrice)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO orderdetail (orderID, productID, quantity, totalPrice)
            VALUES (?, ?, ?, ?)
        ");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("iiid", $orderID, $productID, $quantity, $totalPrice);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    }
    public function getOrdersByCustomer($customerId)
    {
        $query = "
            SELECT 
                o.*, 
                c.name AS couponName, 
                c.percent AS couponPercent, 
                c.duration AS couponDuration, 
                c.status AS couponStatus
            FROM `order` o
            LEFT JOIN coupon c ON o.couponID = c.ID
            WHERE o.customer = ?
        ";
    
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $orders = [];
                while ($row = $result->fetch_assoc()) {
                    $orders[] = $row;
                }
                return $orders;
            } else {
                return null;
            }
    
            $stmt->close();
        } else {
            return null;
        }
    }
    
    
}
