<?php
class Order {
    private $db;
    private $table = 'orders';

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Create new order
     * Parameters: user_id, total, payment_status
     */
    public function create($userId, $total, $paymentStatus = 'pending') {
        $sql = "INSERT INTO " . $this->table . " 
                (user_id, total, payment_status, created_at) 
                VALUES (:user_id, :total, :payment_status, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':payment_status', $paymentStatus);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Get order by ID
     */
    public function getOrderById($orderId) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $orderId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all orders for a user
     */
    public function getUserOrders($userId) {
        $sql = "SELECT * FROM " . $this->table . " 
                WHERE user_id = :user_id
                ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($orderId, $paymentStatus) {
        $sql = "UPDATE " . $this->table . " 
                SET payment_status = :payment_status 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':payment_status', $paymentStatus);
        $stmt->bindParam(':id', $orderId);
        return $stmt->execute();
    }

    /**
     * Get total orders count
     */
    public function getTotalOrders($userId) {
        $sql = "SELECT COUNT(*) as count FROM " . $this->table . " 
                WHERE user_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    /**
     * Get total spending
     */
    public function getTotalSpending($userId) {
        $sql = "SELECT SUM(total) as total_spent FROM " . $this->table . " 
                WHERE user_id = :user_id AND payment_status = 'completed'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_spent'] ?? 0;
    }

    /**
     * Delete order
     */
    public function deleteOrder($orderId) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $orderId);
        return $stmt->execute();
    }
}
?>