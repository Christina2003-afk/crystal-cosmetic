<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

include_once 'classes/Database.php';
include_once 'classes/Product.php';

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$productObj = new Product();
$product = $productObj->getProductById($_GET['id']);

if(!$product){
    die("Product not found");
}

// Razorpay Test Key (use your test key here)
$razorpayKey = "rzp_test_RUByJsLhpfS9YR";

// Amount in paise
$amount = $product['price'] * 100;

// Unique order reference (just for display)
$orderRef = "ORDER_" . time() . "_" . $product['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment - <?php echo htmlspecialchars($product['name']); ?></title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
<h2><?php echo htmlspecialchars($product['name']); ?> - ₹<?php echo number_format($product['price'], 2); ?></h2>

<button id="rzp-button">Pay Now</button>

<script>
var options = {
    "key": "<?php echo $razorpayKey; ?>", 
    "amount": <?php echo $amount; ?>, // in paise
    "currency": "INR",
    "name": "Cosmetic Shop",
    "description": "<?php echo htmlspecialchars($product['name']); ?>",
    "handler": function (response){
        alert("Payment Successful!\nPayment ID: " + response.razorpay_payment_id);
        // You can redirect to success page
        window.location.href = "success.php?payment_id=" + response.razorpay_payment_id + "&product_id=<?php echo $product['id']; ?>";
    },
    "prefill": {
        "name": "<?php echo htmlspecialchars($_SESSION['username']); ?>",
        "email": "<?php echo htmlspecialchars($_SESSION['email'] ?? 'user@example.com'); ?>"
    },
    "theme": {
        "color": "#667eea"
    }
};

var rzp = new Razorpay(options);
document.getElementById('rzp-button').onclick = function(e){
    rzp.open();
    e.preventDefault();
}
</script>

</body>
</html>
