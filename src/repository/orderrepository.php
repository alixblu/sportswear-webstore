<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
include dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class OrderRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    public function save($customerID, $totalPrice, $paymentMethodID, $receiverName, $address, $phone, $email, $products) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }

            // Bắt đầu transaction
            $this->conn->begin_transaction();

            // Thêm vào bảng order
            $stmt = $this->conn->prepare("INSERT INTO `order` (customer, orderDate, totalPrice, status) VALUES (?, NOW(), ?, 'pending')");
            if (!$stmt) {
                throw new Exception("Failed to prepare insert order: " . $this->conn->error);
            }
            $stmt->bind_param("id", $customerID, $totalPrice);
            if (!$stmt->execute()) {
                throw new Exception("Insert order failed: " . $stmt->error);
            }
            $orderID = $this->conn->insert_id;
            $stmt->close();

            // Thêm vào bảng payment
            $stmt = $this->conn->prepare("INSERT INTO payment (orderID, paymentMethodID, paymentDate) VALUES (?, ?, NOW())");
            if (!$stmt) {
                throw new Exception("Failed to prepare insert payment: " . $this->conn->error);
            }
            $stmt->bind_param("ii", $orderID, $paymentMethodID);
            if (!$stmt->execute()) {
                throw new Exception("Insert payment failed: " . $stmt->error);
            }
            $stmt->close();

            // Thêm vào bảng billingdetail
            $stmt = $this->conn->prepare("INSERT INTO billingdetail (orderID, receiverName, address, phone, email) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Failed to prepare insert billingdetail: " . $this->conn->error);
            }
            $stmt->bind_param("issss", $orderID, $receiverName, $address, $phone, $email);
            if (!$stmt->execute()) {
                throw new Exception("Insert billingdetail failed: " . $stmt->error);
            }
            $stmt->close();

            // Thêm vào bảng orderdetail
            foreach ($products as $product) {
                $stmt = $this->conn->prepare("INSERT INTO orderdetail (orderID, productID, quantity, price) VALUES (?, ?, ?, ?)");
                if (!$stmt) {
                    throw new Exception("Failed to prepare insert orderdetail: " . $this->conn->error);
                }
                $stmt->bind_param("iiid", $orderID, $product['productID'], $product['quantity'], $product['price']);
                if (!$stmt->execute()) {
                    throw new Exception("Insert orderdetail failed: " . $stmt->error);
                }
                $stmt->close();
            }

            // Commit transaction
            $this->conn->commit();

            return [
                'orderID' => $orderID,
                'customerID' => $customerID,
                'totalPrice' => $totalPrice,
                'status' => 'pending'
            ];

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Save order error: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            if ($this->conn) $this->conn->close();
        }
    }

    public function findAll() {
        $query = "
 avvi            SELECT 
                o.ID AS orderID,
                u.name AS customerName,
                o.orderDate,
                o.totalPrice,
                pm.name AS paymentMethod,
                o.status
            FROM `order` o
            INNER JOIN user u ON o.customer = u.ID
            LEFT JOIN payment p ON o.ID = p.orderID
            LEFT JOIN paymentmethod pm ON p.paymentMethodID = pm.ID
            ORDER BY o.orderDate DESC
        ";

        $result = $this->conn->query($query);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        $result->close();
        $this->conn->close();
        return $orders;
    }

    public function updateStatus($orderID, $status) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }

            $validStatuses = ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'];
            if (!in_array($status, $validStatuses)) {
                throw new Exception("Invalid status value");
            }

            $stmt = $this->conn->prepare("UPDATE `order` SET status = ? WHERE ID = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare update: " . $this->conn->error);
            }

            $stmt->bind_param("si", $status, $orderID);
            if (!$stmt->execute()) {
                throw new Exception("Update failed: " . $stmt->error);
            }

            return true;

        } catch (Exception $e) {
            error_log("Update order status failed: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            if ($this->conn) $this->conn->close();
        }
    }

    public function findById($orderID) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }

            $stmt = $this->conn->prepare("
                SELECT 
                    o.ID AS orderID,
                    u.name AS customerName,
                    o.orderDate,
                    o.totalPrice,
                    pm.name AS paymentMethod,
                    o.status,
                    bd.receiverName,
                    bd.address,
                    bd.phone,
                    bd.email,
                    od.quantity,
                    pv.Code AS productCode,
                    p.name AS productName,
                    od.price AS unitPrice
                FROM `order` o
                INNER JOIN user u ON o.customer = u.ID
                LEFT JOIN payment p ON o.ID = p.orderID
                LEFT JOIN paymentmethod pm ON p.paymentMethodID = pm.ID
                LEFT JOIN billingdetail bd ON o.ID = bd.orderID
                LEFT JOIN orderdetail od ON o.ID = od.orderID
                LEFT JOIN productvariant pv ON od.productID = pv.ID
                LEFT JOIN product p ON pv.productID = p.ID
                WHERE o.ID = ?
            ");

            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $this->conn->error);
            }

            $stmt->bind_param("i", $orderID);
            if (!$stmt->execute()) {
                throw new Exception("Query execution failed: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $orderDetails = [];
            while ($row = $result->fetch_assoc()) {
                $orderDetails[] = $row;
            }

            return $orderDetails;

        } catch (Exception $e) {
            error_log("Find order by ID failed: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            if ($this->conn) $this->conn->close();
        }
    }

    public function delete($orderID) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }

            // Bắt đầu transaction
            $this->conn->begin_transaction();

            // Xóa orderdetail
            $stmt = $this->conn->prepare("DELETE FROM orderdetail WHERE orderID = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare delete orderdetail: " . $this->conn->error);
            }
            $stmt->bind_param("i", $orderID);
            if (!$stmt->execute()) {
                throw new Exception("Delete orderdetail failed: " . $stmt->error);
            }
            $stmt->close();

            // Xóa billingdetail
            $stmt = $this->conn->prepare("DELETE FROM billingdetail WHERE orderID = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare delete billingdetail: " . $this->conn->error);
            }
            $stmt->bind_param("i", $orderID);
            if (!$stmt->execute()) {
                throw new Exception("Delete billingdetail failed: " . $stmt->error);
            }
            $stmt->close();

            // Xóa payment
            $stmt = $this->conn->prepare("DELETE FROM payment WHERE orderID = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare delete payment: " . $this->conn->error);
            }
            $stmt->bind_param("i", $orderID);
            if (!$stmt->execute()) {
                throw new Exception("Delete payment failed: " . $stmt->error);
            }
            $stmt->close();

            // Xóa order
            $stmt = $this->conn->prepare("DELETE FROM `order` WHERE ID = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare delete order: " . $this->conn->error);
            }
            $stmt->bind_param("i", $orderID);
            if (!$stmt->execute()) {
                throw new Exception("Delete order failed: " . $stmt->error);
            }
            $stmt->close();

            // Commit transaction
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Delete order failed: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            if ($this->conn) $this->conn->close();
        }
    }
}
?>