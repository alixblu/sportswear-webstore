<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once  dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class CouponRepository {
    private $conn;

    public function __construct() {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    public function save($name, $percent, $duration) {
        $stmt = null;
        try {
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }

            $stmt = $this->conn->prepare("INSERT INTO coupon (name, percent, duration, status) VALUES (?, ?, ?, 'active')");
            if (!$stmt) {
                throw new Exception("Failed to prepare insert: " . $this->conn->error);
            }

            $stmt->bind_param("sii", $name, $percent, $duration);
            if (!$stmt->execute()) {
                throw new Exception("Insert failed: " . $stmt->error);
            }

            return [
                'couponID' => $this->conn->insert_id,
                'name' => $name,
                'percent' => $percent,
                'duration' => $duration
            ];

        } catch (Exception $e) {
            error_log("Save coupon error: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            if ($this->conn) $this->conn->close();
        }
    }

    public function findAll() {
        $query = "SELECT * FROM coupon ORDER BY ID DESC";
        $result = $this->conn->query($query);

        $coupons = [];
        while ($row = $result->fetch_assoc()) {
            $coupons[] = $row;
        }
        $result->close();
        $this->conn->close();
        return $coupons;
    }

    public function update($id, $name, $percent, $duration, $status) {
        try {
            $stmt = $this->conn->prepare("UPDATE coupon SET name = ?, percent = ?, duration = ?, status = ? WHERE ID = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare update: " . $this->conn->error);
            }

            $stmt->bind_param("siisi", $name, $percent, $duration, $status, $id);
            $stmt->execute();
            $stmt->close();
            return true;

        } catch (Exception $e) {
            error_log("Update coupon failed: " . $e->getMessage());
            return false;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM coupon WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Delete coupon failed: " . $e->getMessage());
            return false;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM coupon WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $coupon = $result->fetch_assoc();
            $stmt->close();
            return $coupon;
        } catch (Exception $e) {
            error_log("Find coupon by ID failed: " . $e->getMessage());
            return null;
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }
    public function getCouponByUserId($userID) {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, uc.assignedDate, uc.status AS userCouponStatus
                FROM user_coupon uc
                INNER JOIN coupon c ON uc.couponID = c.ID
                WHERE uc.userID = ?
                ORDER BY uc.assignedDate DESC
            ");
    
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $this->conn->error);
            }
    
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $coupons = [];
            while ($row = $result->fetch_assoc()) {
                $coupons[] = $row;
            }
    
            $stmt->close();
            return $coupons;
    
        } catch (Exception $e) {
            error_log("Get coupons by userID failed: " . $e->getMessage());
            return [];
        } finally {
            if ($this->conn) $this->conn->close();
        }
    }
    
}
?>
