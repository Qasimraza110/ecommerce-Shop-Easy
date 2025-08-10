<?php
require_once 'includes/header.php';

// Get featured products
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.featured = 1 
        LIMIT 8";
$featured_products = mysqli_query($conn, $sql);

// Get categories
$sql = "SELECT * FROM categories LIMIT 6";
$categories = mysqli_query($conn, $sql);
?>

<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 mb-4">Welcome to Shop Easy</h1>
        <p class="lead mb-4">Discover amazing products at great prices</p>
        <a href="products.php" class="btn btn-primary btn-lg">Shop Now</a>
    </div>
</section>

<!-- Categories Section -->
<section class="mb-5">
    <div class="container">
        <h2 class="text-center mb-4">Shop by Category</h2>
        <div class="row">
            <?php 
            $category_images = [
                'Electronics' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=60', // Smartphone
                'Clothing' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=800&auto=format&fit=crop&q=60', // Dress
                'Footwear' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=60', // Shoes
                'Accessories' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800&auto=format&fit=crop&q=60', // Watch
                'Books' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800&auto=format&fit=crop&q=60', // Books
                'Home & Kitchen' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=800&auto=format&fit=crop&q=60', // Coffee Maker
            ];
            
            while($category = mysqli_fetch_assoc($categories)): 
                $image_url = $category_images[$category['name']] ?? 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=800&auto=format&fit=crop&q=60';
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card category-card">
                        <img src="<?php echo $image_url; ?>" class="card-img-top" alt="<?php echo $category['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $category['name']; ?></h5>
                            <p class="card-text"><?php echo $category['description']; ?></p>
                            <a href="products.php?category=<?php echo $category['id']; ?>" class="btn btn-primary">View Products</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="mb-5">
    <div class="container">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            <?php 
            $product_images = [
                'Smartphone X'    => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=60',
                'Laptop Pro'      => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&auto=format&fit=crop&q=60',
                "Women's Dress"   => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=800&auto=format&fit=crop&q=60',
                'Coffee Maker'    => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=800&auto=format&fit=crop&q=60',
                'Facial Cleanser' => 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=800&auto=format&fit=crop&q=60',
                'Earbuds'         => 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=800&auto=format&fit=crop&q=60',
                'Running Shoes'   => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=60',
                // Add more as needed
            ];
            
            while($product = mysqli_fetch_assoc($featured_products)): 
                $image_url = $product_images[$product['name']] ?? 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=800&auto=format&fit=crop&q=60';
            ?>
                <div class="col-md-3 mb-4">
                    <div class="card product-card h-100">
                        <img src="<?php echo $image_url; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <p class="card-text text-muted"><?php echo $product['category_name']; ?></p>
                            <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                            <div class="d-grid">
                                <button onclick="addToCart(event, <?php echo $product['id']; ?>)" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-4">
            <a href="products.php" class="btn btn-outline-primary">View All Products</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-truck fa-3x mb-3 text-primary"></i>
                <h4>Free Shipping</h4>
                <p>On orders over $50</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-undo fa-3x mb-3 text-primary"></i>
                <h4>Easy Returns</h4>
                <p>30 days return policy</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-lock fa-3x mb-3 text-primary"></i>
                <h4>Secure Payment</h4>
                <p>100% secure checkout</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 