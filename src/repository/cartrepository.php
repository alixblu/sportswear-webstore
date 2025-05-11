<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once  dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class CartRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    public function save($userAccID, $totalPrice = 0.0) {
        $stmt = null;
    
        try {
            if (!$this->conn) {
                throw new Exception("Failed to connect to database");
            }
    
            if (!$this->conn->begin_transaction()) {
                throw new Exception("Failed to start transaction");
            }
    
            $stmt = $this->conn->prepare("INSERT INTO cart (userAccID, totalPrice) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception("Failed to prepare cart insert: " . $this->conn->error);
            }
    
            $stmt->bind_param("id", $userAccID, $totalPrice);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert cart: " . $stmt->error);
            }
    
            $insertedId = $this->conn->insert_id;
    
            if (!$this->conn->commit()) {
                throw new Exception("Failed to commit cart transaction");
            }
    
            return [
                'cartID' => $insertedId,
                'userAccID' => $userAccID,
                'totalPrice' => $totalPrice
            ];
        } catch (Exception $e) {
            if ($this->conn) {
                $this->conn->rollback();
            }
            error_log("Save cart error: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
        }
    }
    

    public function findByUserAccId($userAccID) {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    cart.ID as cartID,
                    cart.userAccID,
                    cartdetail.ID as detailID,
                    cartdetail.productID,
                    cartdetail.quantity,
                    productvariant.fullName as productName,
                    productvariant.price as productPrice

                FROM cart
                INNER JOIN cartdetail ON cart.ID = cartdetail.cartID
                INNER JOIN productvariant ON cartdetail.productID = productvariant.ID
                WHERE cart.userAccID = ?
            ");
            $stmt->bind_param("i", $userAccID);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $carts = [];
            while ($row = $result->fetch_assoc()) {
                $carts[] = $row;
            }
    
            $stmt->close();
            return $carts;
        } catch (Exception $e) {
            error_log("findByUserAccId failed: " . $e->getMessage());
            return [];
        }
    }
    
    

    public function update($cartID, $totalPrice) {
        try {
            $this->conn->begin_transaction();

            $stmt = $this->conn->prepare("UPDATE cart SET totalPrice = ? WHERE ID = ?");
            $stmt->bind_param("di", $totalPrice, $cartID);
            $stmt->execute();
            $stmt->close();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Update cart failed: " . $e->getMessage());
            return false;
        } finally {
            $this->conn->close();
        }
    }
    public function findCartIdByUserAccId($userAccID) {
        try {
            $stmt = $this->conn->prepare("
                SELECT ID as cartID
                FROM cart
                WHERE userAccID = ?
                LIMIT 1
            ");
            $stmt->bind_param("i", $userAccID);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $cartID = null;
            if ($row = $result->fetch_assoc()) {
                $cartID = $row['cartID'];
            }
    
            $stmt->close();
            return $cartID; 
        } catch (Exception $e) {
            error_log("findCartIdByUserAccId failed: " . $e->getMessage());
            return null;
        }
    }
    
}
?>
