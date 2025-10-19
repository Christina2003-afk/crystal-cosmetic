<?php
session_start();
echo "User ID: " . ($_SESSION['user_id'] ?? 'NOT SET');
echo "<br>Username: " . ($_SESSION['username'] ?? 'NOT SET');
?>