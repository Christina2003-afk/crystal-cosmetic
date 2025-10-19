<?php
session_start();

if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit;
}

$orderId = $_GET['order_id'];
$successMessage = $_SESSION['success_message'] ?? "Order placed successfully!";
unset($_SESSION['success_message']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-success text-center" role="alert">
        <h2><i class="fas fa-check-circle"></i> Payment Successful!</h2>
        <p><?php echo $successMessage; ?></p>
        <p>Your order #<?php echo $orderId; ?> has been placed.</p>
        <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
    </div>
</div>
</body>
</html>