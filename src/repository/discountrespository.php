<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
include dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class DiscountRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    public function save($name, $discountRate, $status = 'active') {
        try {
            $stmt = $this->conn->prepare("INSERT INTO discount (name, discountRate, status) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            $stmt->bind_param("sis", $name, $discountRate, $status);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $discountID = $this->conn->insert_id;
            $stmt->close();
            $this->conn->close();

            return [
                'ID' => $discountID,
                'name' => $name,
                'discountRate' => $discountRate,
                'status' => $status
            ];
        } catch (Exception $e) {
            error_log("Save discount failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function findAll() {
        try {
            $query = "SELECT * FROM discount ORDER BY ID DESC";
            $result = $this->conn->query($query);

            $discounts = [];
            while ($row = $result->fetch_assoc()) {
                $discounts[] = $row;
            }

            $result->close();
            $this->conn->close();

            return $discounts;
        } catch (Exception $e) {
            error_log("Find all discounts failed: " . $e->getMessage());
            return [];
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM discount WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $discount = $result->fetch_assoc();

            $stmt->close();
            $this->conn->close();

            return $discount ?: null;
        } catch (Exception $e) {
            error_log("Find discount by ID failed: " . $e->getMessage());
            return null;
        }
    }

    public function update($id, $name, $discountRate, $status) {
        try {
            $stmt = $this->conn->prepare("UPDATE discount SET name = ?, discountRate = ?, status = ? WHERE ID = ?");
            $stmt->bind_param("sisi", $name, $discountRate, $status, $id);
            $success = $stmt->execute();

            $stmt->close();
            $this->conn->close();

            return $success;
        } catch (Exception $e) {
            error_log("Update discount failed: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM discount WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $success = $stmt->execute();

            $stmt->close();
            $this->conn->close();

            return $success;
        } catch (Exception $e) {
            error_log("Delete discount failed: " . $e->getMessage());
            return false;
        }
    }
}
?>
