<?php
require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
require_once  dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';
class WarehouseRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = ConfigMysqli::connectDatabase();
    }

    public function getAll()
    {
        try {
            $query = "
                    SELECT p.* 
                    FROM purchaseorder as p
                    ORDER BY p.createdAt DESC
                ";
            $result = $this->conn->query($query);

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->close();
            $this->conn->close();
            return $data;
        } catch (Exception $e) {
            error_log("Error in get all pur order: " . $e->getMessage());
            throw new Exception("Failed to get all pur order");
        }
    }

    public function getFilterdPurOrder($page, $limit, $search, $status)
    {
        try {
            $offset = ($page - 1) * $limit;
            // Get product list
            $query = "SELECT p.* COUNT(p.ID) as total FROM purchaseorder as p WHERE 1=1";
            $params = [];
            // Get total amount
            $count_query = "SELECT COUNT(DISTINCT p.ID) as total from purchaseorder as p WHERE 1=1";
            $count_params = [];

            if ($status) {
                $count_query .= " AND p.status=?";
                $query .= " AND p.status=?";
                $params[] = $count_params[] = $status;
            }
            if ($search) {
                $count_query .= " AND p.name LIKE ?";
                $query .= " AND p.name LIKE ?";
                $params[] = $count_params[] = '%' . $search . '%';
            }
            $query .= " GROUP BY p.ID LIMIT ?, ?";
            $params[] = (int)$offset;
            $params[] = (int)$limit;

            // Execute data
            $stmt1 = $this->conn->prepare($query);
            if (!$stmt1)
                throw new Exception('Prepare get product list failed!!!');
            $stmt1->execute($params);
            $result1 = $stmt1->get_result();

            $data = [];
            while ($row = $result1->fetch_assoc()) {
                $data[] = $row;
            }
            // Execute get amount
            $stmt2 = $this->conn->prepare($count_query);
            if (!$stmt2)
                throw new Exception('Prepare get amount of products failed!!!');
            $stmt2->execute($count_params);
            $total = ($stmt2->get_result()->fetch_assoc()['total']);

            return [
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'limit' => $limit
            ];
        } catch (Exception $e) {
            error_log("Error in getFilterdPurOrder: " . $e->getMessage());
            throw new Exception("Failed to get filtered pur order ADMIN: " . $e->getMessage());
        }
    }

    public function getLots($id)
    {
        try {
            $query = "
                    SELECT l.* 
                    FROM lot as l
                    JOIN purcha
                    ORDER BY p.createdAt DESC
                ";
            $result = $this->conn->query($query);

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->close();
            $this->conn->close();
            return $data;
        } catch (Exception $e) {
            error_log("Error in get lots: " . $e->getMessage());
            throw new Exception("Failed to get lots");
        }
    }

    public function getPurById($id)
    {
        try {
            $query = "
                    SELECT p.*
                    FROM purchaseorder as p
                    ORDER BY p.ID = ?
                ";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $this->conn->close();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in get pur order by id: " . $e->getMessage());
            throw new Exception("Failed to get pur order by id");
        }
    }

    public function update($id, $data)
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
            error_log("Error in update pur order: " . $e->getMessage());
            throw new Exception("Failed to update pur order");
        }
    }

    public function delete($id)
    {
        try {
            $this->conn->begin_transaction();

            $stmt = $this->conn->prepare("DELETE FROM purchaseorder WHERE ID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Delete review failed: " . $e->getMessage());
            return false;
        } finally {
            $this->conn->close();
        }
    }
}
