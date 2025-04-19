<?php
    require_once dirname(__FILE__) . '/../config/mysqli/mysqli.php';
    include dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';
    class ReviewRepository{
        private $conn;

        public function __construct()
        {
            $this->conn = ConfigMysqli::connectDatabase();
        }

        public function save($userAccID, $productID, $rating, $commentContent = null) {
            $stmt = null;
        
            try {
                if (!$this->conn) {
                    throw new Exception("Failed to connect to database");
                }
        
                if (!$this->conn->begin_transaction()) {
                    throw new Exception("Failed to start transaction");
                }
        
                $commentID = null;
        
                if (!empty($commentContent)) {

                    $stmt = $this->conn->prepare("INSERT INTO comment (content) VALUES (?)");
                    if (!$stmt) {
                        throw new Exception("Failed to prepare comment insert: " . $this->conn->error);
                    }
        
                    $stmt->bind_param("s", $commentContent);
                    if (!$stmt->execute()) {
                        throw new Exception("Failed to insert comment: " . $stmt->error);
                    }
        
                    $commentID = $this->conn->insert_id;
                    $stmt->close();
                }
        
                if ($commentID) {
                    $stmt = $this->conn->prepare("INSERT INTO review (userAccID, commentID, productID, rating, status) VALUES (?, ?, ?, ?, 'active')");
                    $stmt->bind_param("iiii", $userAccID, $commentID, $productID, $rating);
                } else {
                    $stmt = $this->conn->prepare("INSERT INTO review (userAccID, productID, rating, status) VALUES (?, ?, ?, 'active')");
                    $stmt->bind_param("iii", $userAccID, $productID, $rating);
                }
        
                if (!$stmt) {
                    throw new Exception("Failed to prepare review insert: " . $this->conn->error);
                }
        
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert review: " . $stmt->error);
                }
        
                if (!$this->conn->commit()) {
                    throw new Exception("Failed to commit review transaction");
                }
        
                return [
                    'reviewID' => $this->conn->insert_id,
                    'userAccID' => $userAccID,
                    'productID' => $productID,
                    'rating' => $rating,
                    'commentID' => $commentID
                ];
        
            } catch (Exception $e) {
                if ($this->conn) {
                    $this->conn->rollback();
                }
                error_log("Save review error: " . $e->getMessage());
                throw $e;
            } finally {
                if ($stmt) $stmt->close();
                if ($this->conn) $this->conn->close();
            }
        }
        public function findAll() {
            $query = "
                SELECT r.*, c.content AS commentContent 
                FROM review r
                LEFT JOIN comment c ON r.commentID = c.ID
                ORDER BY r.createdAt DESC
            ";
            $result = $this->conn->query($query);
    
            $reviews = [];
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
            $result->close();
            $this->conn->close();
            return $reviews;
        }
    
        public function update($reviewID, $rating, $commentContent = null) {
            try {
                $this->conn->begin_transaction();
    
                if ($commentContent !== null) {
                    $stmt = $this->conn->prepare("SELECT commentID FROM review WHERE ID = ?");
                    $stmt->bind_param("i", $reviewID);
                    $stmt->execute();
                    $stmt->bind_result($commentID);
                    $stmt->fetch();
                    $stmt->close();
    
                    if ($commentID) {
                        $stmt = $this->conn->prepare("UPDATE comment SET content = ? WHERE ID = ?");
                        $stmt->bind_param("si", $commentContent, $commentID);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
    
                $stmt = $this->conn->prepare("UPDATE review SET rating = ? WHERE ID = ?");
                $stmt->bind_param("ii", $rating, $reviewID);
                $stmt->execute();
                $stmt->close();
    
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                error_log("Update review failed: " . $e->getMessage());
                return false;
            } finally {
                $this->conn->close();
            }
        }

        public function getReviewByProductId($productId) {
            try {
                $stmt = $this->conn->prepare("
                    SELECT r.*, c.content AS commentContent 
                    FROM review r
                    LEFT JOIN comment c ON r.commentID = c.ID
                    WHERE r.productID = ?
                    ORDER BY r.createdAt DESC
                ");
        
                if (!$stmt) {
                    throw new Exception("Failed to prepare statement: " . $this->conn->error);
                }
        
                $stmt->bind_param("i", $productId);
                $stmt->execute();
                $result = $stmt->get_result();
        
                $reviews = [];
                while ($row = $result->fetch_assoc()) {
                    $reviews[] = $row;
                }
        
                $stmt->close();
                $this->conn->close();
                return $reviews;
        
            } catch (Exception $e) {
                error_log("getReviewByProductId failed: " . $e->getMessage());
                return [];
            }
        }
        
        public function delete($reviewID) {
            try {
                $this->conn->begin_transaction();
    
                $stmt = $this->conn->prepare("SELECT commentID FROM review WHERE ID = ?");
                $stmt->bind_param("i", $reviewID);
                $stmt->execute();
                $stmt->bind_result($commentID);
                $stmt->fetch();
                $stmt->close();
    
                $stmt = $this->conn->prepare("DELETE FROM review WHERE ID = ?");
                $stmt->bind_param("i", $reviewID);
                $stmt->execute();
                $stmt->close();
    
                if ($commentID) {
                    $stmt = $this->conn->prepare("DELETE FROM comment WHERE ID = ?");
                    $stmt->bind_param("i", $commentID);
                    $stmt->execute();
                    $stmt->close();
                }
    
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
        
?>