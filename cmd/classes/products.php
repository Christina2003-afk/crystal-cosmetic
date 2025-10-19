<?php
include_once 'Database.php';

class Product {
    private $conn;

    // Constructor to initialize database connection
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Fetch all products from database
    public function getAllProducts() {
        $query = "SELECT * FROM products ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch single product by ID
    public function getProductById($id) {
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Optional: Add a new product (Admin use)
    public function addProduct($name, $description, $price, $image) {
        $query = "INSERT INTO products (name, description, price, image, created_at)
                  VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $description, $price, $image]);
    }

    // Optional: Delete a product by ID
    public function deleteProduct($id) {
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Optional: Update a product
    public function updateProduct($id, $name, $description, $price, $image) {
        $query = "UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $description, $price, $image, $id]);
    }
}
?>
