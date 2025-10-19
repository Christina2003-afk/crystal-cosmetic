<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Order Class</h2>";

try {
    echo "1. Including Database class...<br>";
    include_once 'classes/Database.php';
    echo "✓ Database class loaded<br>";
    
    echo "2. Including Order class...<br>";
    include_once 'classes/Order.php';
    echo "✓ Order class loaded<br>";
    
    echo "3. Creating Database connection...<br>";
    $db = new Database();
    $conn = $db->connect();
    echo "✓ Database connected<br>";
    
    echo "4. Creating Order object...<br>";
    $orderObj = new Order();
    echo "✓ Order object created<br>";
    
    echo "5. Creating test order...<br>";
    $userId = 1;
    $total = 99.99;
    $status = 'completed';
    
    $orderId = $orderObj->create($userId, $total, $status);
    
    if ($orderId) {
        echo "<span style='color:green;'><b>✓ SUCCESS! Order created with ID: " . $orderId . "</b></span><br>";
        echo "Check your database - a new order should exist.<br>";
    } else {
        echo "<span style='color:red;'><b>✗ FAILED! Order was not created.</b></span><br>";
    }
    
} catch (Exception $e) {
    echo "<span style='color:red;'><b>✗ ERROR: " . $e->getMessage() . "</b></span><br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<br><a href='javascript:history.back()'>← Go Back</a>";
?>