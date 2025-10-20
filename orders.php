<?php 
session_start();
include_once 'classes/Order.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$orderObj = new Order();
$user_id = $_SESSION['user_id'];
$orders = $orderObj->getOrdersByUser($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Orders - Cosmetic Shop</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #fafafa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
.container { margin-top: 80px; max-width: 1000px; }
.page-title { text-align: center; font-size: 36px; font-weight: 700; margin-bottom: 50px; color: #333; }
.order-card { background: white; border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); padding: 25px; margin-bottom: 25px; transition: 0.3s; }
.order-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(212,165,212,0.2); }
.order-header { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 10px; }
.order-id { font-weight: 700; color: #b3789b; }
.order-total { font-weight: 600; font-size: 18px; }
.status { padding: 6px 12px; border-radius: 8px; font-weight: 600; }
.status.Paid { background: #d4f8d4; color: green; }
.status.Pending { background: #fff3cd; color: #856404; }
.btn-view { background: linear-gradient(135deg, #d4a5d4 0%, #b3789b 100%); color: white; border: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; text-decoration: none; transition: 0.3s; }
.btn-view:hover { opacity: 0.9; }
</style>
</head>
<body>

<div class="container">
    <h2 class="page-title">Your Orders</h2>

    <?php if(!empty($orders)): ?>
        <?php foreach($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="order-id">Order #<?php echo htmlspecialchars($order['id']); ?></span><br>
                        <small>Date: <?php echo htmlspecialchars($order['created_at']); ?></small>
                    </div>
                    <div>
                        <span class="order-total">$<?php echo number_format($order['total'], 2); ?></span><br>
                        <span class="status <?php echo htmlspecialchars($order['payment_status']); ?>">
                            <?php echo htmlspecialchars($order['payment_status']); ?>
                        </span>
                    </div>
                </div>

                <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($order['razorpay_payment_id']); ?></p>
                <a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="btn-view">
                    View Products
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">You have no orders yet.</div>
    <?php endif; ?>
</div>

</body>
</html>
