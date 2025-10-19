<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

// Include class files
include_once 'classes/Database.php';
include_once 'classes/Product.php';

// Create Product object
$productObj = new Product();

// Check if product ID is provided in URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$product = $productObj->getProductById($id);

// Show message if product not found
if (!$product) {
    echo "<h2 style='text-align:center;margin-top:50px;'>Product not found!</h2>";
    echo "<p style='text-align:center;'><a href='index.php'>Back to Shop</a></p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - Cosmetic Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .product-image { width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; }
        .card { margin-top: 50px; padding: 20px; }
        .btn-buy { margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="btn btn-secondary mt-4">← Back to Products</a>

    <div class="card shadow-sm">
        <div class="row">
            <div class="col-md-6">
                <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
            </div>
            <div class="col-md-6">
                <h2><?php echo $product['name']; ?></h2>
                <p><?php echo $product['description']; ?></p>
                <h4 class="text-success">$<?php echo number_format($product['price'], 2); ?></h4>

                <!-- Buy Now button -->
                <a href="payment.php?id=<?php echo $product['id']; ?>" class="btn btn-success btn-buy w-100">Buy Now</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
