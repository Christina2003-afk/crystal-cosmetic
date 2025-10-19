<?php 
session_start(); 
include_once 'classes/Product.php';  

// Create Product object and fetch products
$productObj = new Product(); 
$products = $productObj->getAllProducts(); 
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmetic Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #fafafa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar */
        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            background: linear-gradient(135deg, #d4a5d4 0%, #b3789b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            font-weight: 500;
            color: #333 !important;
            margin-left: 20px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #d4a5d4 !important;
        }

        .btn-logout {
            background: linear-gradient(135deg, #d4a5d4 0%, #b3789b 100%);
            border: none;
            color: white;
            padding: 6px 16px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 165, 212, 0.4);
        }

        /* Banner Section */
        .banner-section {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .banner-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: none;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(212, 165, 212, 0.2) 0%, rgba(179, 120, 155, 0.2) 100%);
        }

        .banner-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 2;
            animation: fadeInUp 1s ease-out;
        }

        .banner-text h1 {
            font-size: 72px;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: -1px;
        }

        .banner-text p {
            font-size: 28px;
            font-weight: 600;
            color: #ffd7e8;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
            margin: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate(-50%, -40%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        /* Products Section */
        .products-section {
            padding: 80px 0;
            background: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            font-size: 42px;
            font-weight: 700;
            color: #333;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #d4a5d4 0%, #b3789b 100%);
            margin: 20px auto 0;
            border-radius: 2px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 35px;
            padding: 0 40px;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(212, 165, 212, 0.2);
        }

        .product-image-container {
            position: relative;
            width: 100%;
            height: 350px;
            overflow: hidden;
            background: linear-gradient(135deg, #f5e6e8 0%, #ece2f0 100%);
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.08);
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .overlay-icon {
            font-size: 40px;
            color: white;
        }

        .product-body {
            padding: 25px;
        }

        .product-name {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .product-price {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #d4a5d4 0%, #b3789b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }

        .btn-view-details {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #d4a5d4 0%, #b3789b 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-view-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 165, 212, 0.4);
        }

        .no-products {
            text-align: center;
            padding: 60px 20px;
            font-size: 18px;
            color: #999;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
            color: white;
            text-align: center;
            padding: 30px 0;
            margin-top: 80px;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 25px;
                padding: 0 20px;
            }

            .banner-text h1 {
                font-size: 54px;
            }

            .banner-text p {
                font-size: 22px;
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 0 15px;
            }

            .banner-text h1 {
                font-size: 42px;
            }

            .banner-text p {
                font-size: 18px;
            }

            .product-image-container {
                height: 280px;
            }

            .section-title {
                font-size: 32px;
            }

            .nav-link {
                margin-left: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-sparkles"></i> Cosmetic Shop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i class="fas fa-shopping-cart"></i> Cart
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-logout" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Fullscreen Banner -->
    <div class="banner-section">
        <img src="images/banner.jpg" alt="Cosmetic Shop Banner">
        <div class="banner-overlay"></div>
        <div class="banner-text">
            <h1>Crystal cosmetic shop</h1>
            <p>Explore the best cosmetics and beauty essentials</p>
        </div>
    </div>

    <!-- Products Section -->
    <div class="products-section">
        <div class="container-fluid">
            <h2 class="section-title">Featured Products</h2>
            
            <?php if(!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image-container">
                                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" 
                                     class="product-image" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div class="product-overlay">
                                    <i class="overlay-icon fas fa-search-plus"></i>
                                </div>
                            </div>
                            <div class="product-body">
                                <h5 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn-view-details">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 20px; color: #ddd;"></i>
                    <p>No products available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Cosmetic Shop. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>