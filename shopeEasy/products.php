<?php
require_once 'includes/header.php';

// Get categories for filter
$sql = "SELECT * FROM categories";
$categories = mysqli_query($conn, $sql);

// Product images array
$product_images = [
    1 => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=60', // Smartphone
    2 => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&auto=format&fit=crop&q=60', // Laptop
    3 => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&auto=format&fit=crop&q=60', // T-Shirt
    4 => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=800&auto=format&fit=crop&q=60', // Dress
    5 => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800&auto=format&fit=crop&q=60', // Books
    6 => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=800&auto=format&fit=crop&q=60', // Coffee Maker
    7 => 'https://images.unsplash.com/photo-1592432678016-e910b452f9a2?w=800&auto=format&fit=crop&q=60', // Yoga Mat
    8 => 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=800&auto=format&fit=crop&q=60', // Facial Cleanser
    9 => 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=800&auto=format&fit=crop&q=60', // Earbuds
    10 => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&auto=format&fit=crop&q=60', // Smart Watch
    11 => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=60', // Running Shoes
    12 => 'data:image/jpeg;base64,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'  // Cookware
];

// Build query based on filters
$where_conditions = [];
$params = [];
$types = "";

if(isset($_GET['category']) && !empty($_GET['category'])) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $_GET['category'];
    $types .= "i";
}

if(isset($_GET['min_price']) && !empty($_GET['min_price'])) {
    $where_conditions[] = "p.price >= ?";
    $params[] = $_GET['min_price'];
    $types .= "d";
}

if(isset($_GET['max_price']) && !empty($_GET['max_price'])) {
    $where_conditions[] = "p.price <= ?";
    $params[] = $_GET['max_price'];
    $types .= "d";
}

if(isset($_GET['search']) && !empty($_GET['search'])) {
    $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $search_term = "%" . $_GET['search'] . "%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= "ss";
}

// Sort options
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
$order_by = match($sort) {
    'price_asc' => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'name_desc' => 'p.name DESC',
    default => 'p.name ASC'
};

// Build the final query
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id";

if(!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}

$sql .= " ORDER BY " . $order_by;

// Prepare and execute the query
$stmt = mysqli_prepare($conn, $sql);
if(!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);
?>

<div class="container">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Filters</h5>
                    <form action="" method="GET">
                        <!-- Search -->
                        <div class="mb-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?php echo $_GET['search'] ?? ''; ?>">
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Categories</option>
                                <?php while($category = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'selected' : ''; ?>>
                                        <?php echo $category['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price" placeholder="Min" value="<?php echo $_GET['min_price'] ?? ''; ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price" placeholder="Max" value="<?php echo $_GET['max_price'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort -->
                        <div class="mb-3">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                                <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
                                <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price (Low to High)</option>
                                <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price (High to Low)</option>
                            </select>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-md-9">
            <div class="row">
                <?php if(mysqli_num_rows($products) > 0): ?>
                    <?php while($product = mysqli_fetch_assoc($products)): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card product-card h-100 shadow-sm border-0">
                                <div class="position-relative">
                                    <img src="<?php echo isset($product_images[$product['id']]) ? $product_images[$product['id']] : 'assets/images/product-placeholder.jpg'; ?>" 
                                         class="card-img-top product-img" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         onerror="this.src='assets/images/product-placeholder.jpg'">
                                    <?php if($product['price'] < 50): ?>
                                        <span class="position-absolute top-0 end-0 badge bg-danger m-2">Sale!</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-1 text-truncate" title="<?php echo htmlspecialchars($product['name']); ?>"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text text-muted small mb-2 text-truncate" title="<?php echo htmlspecialchars($product['category_name']); ?>"><?php echo htmlspecialchars($product['category_name']); ?></p>
                                    <p class="card-text fw-bold mb-3 price-tag">$<?php echo number_format($product['price'], 2); ?></p>
                                    <div class="mt-auto">
                                        <form action="add_to_cart.php" method="POST" class="d-flex align-items-center mb-2">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" 
                                                   class="form-control form-control-sm me-2" style="width: 70px;">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            No products found matching your criteria.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Show success/error messages
<?php if(isset($_SESSION['success'])): ?>
    alert('<?php echo $_SESSION['success']; ?>');
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    alert('<?php echo $_SESSION['error']; ?>');
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
</script>

<?php require_once 'includes/footer.php'; ?> 