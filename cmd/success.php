<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

include_once 'classes/Database.php';
include_once 'classes/Product.php';

$payment_id = $_GET['payment_id'] ?? '';
$product_id = $_GET['product_id'] ?? '';

if(!$payment_id || !$product_id){
    die("Invalid payment details.");
}

$productObj = new Product();
$product = $productObj->getProductById($product_id);

if(!$product){
    die("Product not found.");
}

// Insert order into database
try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total, payment_status, razorpay_payment_id) VALUES (:user_id, :total, :payment_status, :payment_id)");
    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':total' => $product['price'],
        ':payment_status' => 'Completed',
        ':payment_id' => $payment_id
    ]);

    $order_id = $conn->lastInsertId();

} catch (PDOException $e) {
    die("Error saving order: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-container img {
            max-width: 200px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .success-container h2 {
            color: #28a745;
        }
    </style>
</head>
<body>
<div class="success-container">
    <!-- Product Image -->
    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

    <h2>Payment Completed ✅</h2>
    <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
    <p><strong>Product:</strong> <?php echo htmlspecialchars($product['name']); ?></p>
    <p><strong>Price:</strong> ₹<?php echo number_format($product['price'], 2); ?></p>
    <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($payment_id); ?></p>

    <a href="index.php" class="btn btn-primary mt-3">Back to Shop</a>
</div>
</body>
</html>
