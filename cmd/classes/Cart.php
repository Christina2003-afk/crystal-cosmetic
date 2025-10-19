<?php
class Cart {
    private $db;
    private $table = 'classes/cart';

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Add item to cart or update quantity if already exists
     */
    public function addToCart($userId, $productId, $quantity) {
        // Check if product already in cart
        $sql = "SELECT id, quantity FROM " . $this->table . " 
                WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update quantity
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $newQuantity = $row['quantity'] + $quantity;
            
            $updateSql = "UPDATE " . $this->table . " 
                         SET quantity = :quantity 
                         WHERE user_id = :user_id AND product_id = :product_id";
            $updateStmt = $this->db->prepare($updateSql);
            $updateStmt->bindParam(':quantity', $newQuantity);
            $updateStmt->bindParam(':user_id', $userId);
            $updateStmt->bindParam(':product_id', $productId);
            return $updateStmt->execute();
        } else {
            // Insert new item
            $insertSql = "INSERT INTO " . $this->table . " 
                         (user_id, product_id, quantity) 
                         VALUES (:user_id, :product_id, :quantity)";
            $insertStmt = $this->db->prepare($insertSql);
            $insertStmt->bindParam(':user_id', $userId);
            $insertStmt->bindParam(':product_id', $productId);
            $insertStmt->bindParam(':quantity', $quantity);
            return $insertStmt->execute();
        }
    }

    /**
     * Get all cart items for a user with product details
     */
    public function getCartItems($userId) {
        $sql = "SELECT c.id, c.user_id, c.product_id, c.quantity, 
                       p.name, p.price, p.image, p.description
                FROM " . $this->table . " c
                INNER JOIN products p ON c.product_id = p.id
                WHERE c.user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get single cart item
     */
    public function getCartItem($userId, $productId) {
        $sql = "SELECT * FROM " . $this->table . " 
                WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update quantity for a cart item
     */
    public function updateQuantity($userId, $productId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($userId, $productId);
        }
        
        $sql = "UPDATE " . $this->table . " 
                SET quantity = :quantity 
                WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        return $stmt->execute();
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($userId, $productId) {
        $sql = "DELETE FROM " . $this->table . " 
                WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        return $stmt->execute();
    }

    /**
     * Clear entire cart for a user
     */
    public function clearCart($userId) {
        $sql = "DELETE FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    /**
     * Get cart total
     */
    public function getCartTotal($userId) {
        $sql = "SELECT SUM(c.quantity * p.price) as total 
                FROM " . $this->table . " c
                INNER JOIN products p ON c.product_id = p.id
                WHERE c.user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Get cart item count
     */
    public function getCartCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM " . $this->table . " 
                WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
?>