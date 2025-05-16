<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once  dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class CartDetailRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    public function add($productID, $quantity, $cartID) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO cartdetail (productID, quantity, cartID) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Failed to prepare insert: " . $this->conn->error);
            }

            $stmt->bind_param("iii", $productID, $quantity, $cartID);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert cart detail: " . $stmt->error);
            }

            $insertId = $this->conn->insert_id;
            $stmt->close();
            $this->conn->close();
            return $insertId;

        } catch (Exception $e) {
            error_log("Add cart detail error: " . $e->getMessage());
            throw $e;
        }
    }

    public function findByCartID($cartID) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM cartdetail WHERE cartID = ?");
            $stmt->bind_param("i", $cartID);
            $stmt->execute();
            $result = $stmt->get_result();

            $details = [];
            while ($row = $result->fetch_assoc()) {
                $details[] = $row;
            }

            $stmt->close();
            $this->conn->close();
            return $details;

        } catch (Exception $e) {
            error_log("Find cart detail error: " . $e->getMessage());
            return [];
        }
    }

    public function findByCartIdAndProductId($cartID, $productID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM cartdetail WHERE cartID = ? AND productID = ? LIMIT 1");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("ii", $cartID, $productID);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = $result->fetch_assoc();
        $stmt->close();

        return $data ? $data : null;
    }


    public function updateQuantity($cartDetailID, $quantity) {
        try {
            $stmt = $this->conn->prepare("UPDATE cartdetail SET quantity = ? WHERE ID = ?");
            $stmt->bind_param("ii", $quantity, $cartDetailID);
            $success = $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return $success;
        } catch (Exception $e) {
            error_log("Update cart detail error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($cartDetailID) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM cartdetail WHERE ID = ?");
            $stmt->bind_param("i", $cartDetailID);
            $success = $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return $success;
        } catch (Exception $e) {
            error_log("Delete cart detail error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteAll($cartID)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM cartdetail WHERE cartID = ?");
            
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $this->conn->error);
            }
            
            $stmt->bind_param("i", $cartID);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Delete all cart details failed: " . $e->getMessage());
            return false;
        } finally {
            if ($this->conn) {
                $stmt->close();
            }
        }
    }

}
?>
