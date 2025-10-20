<?php 
session_start();
include_once 'classes/Order.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if(!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit();
}

$orderObj = new Order();
$order_id = intval($_GET['order_id']);
$items = $orderObj->getOrderItems($order_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f8f8f8; }
.container { margin-top: 80px; max-width: 900px; }
.card { border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); margin-bottom: 20px; }
.product-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
</style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-5">Order #<?php echo htmlspecialchars($order_id); ?> Details</h2>

    <?php if(!empty($items)): ?>
        <?php foreach($items as $item): ?>
            <div class="card p-3 d-flex flex-row align-items-center">
                <img src="images/<?php echo htmlspecialchars($item['image']); ?>" class="product-img me-4">
                <div>
                    <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                    <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                    <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">No products found for this order.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
    </div>
</div>

</body>
</html>
