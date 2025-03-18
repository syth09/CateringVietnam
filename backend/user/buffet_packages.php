<?php
session_start();
require_once '../includes/db_connection.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Lấy danh sách các món ăn từ bảng menu
$sql = "SELECT * FROM menu";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buffet Packages - Catering Vietnam</title>
    <link rel="stylesheet" href="../../frontend/css/user_dashboard.css"> <!-- Sử dụng CSS của user dashboard -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> <!-- Font Awesome -->
</head>

<body>
    <!-- Header -->
    <header>
        <div class="flex items-center">
            <a class="mr-auto logo" href="/frontend/index.html">CateringVietnam<span>.</span></a>
            <ul class="flex nav">
                <li><a class="mr-60px" href="../frontend/index.html">Home</a></li>
                <li><a class="mr-60px" href="../frontend/about.html">About</a></li>
                <li><a class="mr-60px" href="../frontend/contact.html">Contact</a></li>
            </ul>
            <a href="#" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">3</span>
            </a>
        </div>
    </header>

    <main>
        <div class="user-dashboard">
            <div class="user-sidebar">
                <h2>Dashboard</h2>
                <ul>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="payment.php">Payments</a></li>
                    <li><a href="buffet_packages.php">Buffet Packages</a></li>
                </ul>
            </div>
            <div class="user-content">
                <h1>Buffet Packages</h1>
                <div class="buffet-packages">
                    <?php if ($menu_items): ?>
                        <?php foreach ($menu_items as $item): ?>
                            <div class="buffet-item">
                                <img src="../assets/uploads/menu_images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['dish_name']); ?>">
                                <h3><?php echo htmlspecialchars($item['dish_name']); ?></h3>
                                <p><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</p>
                                <button class="btn" onclick="addToCart(<?php echo $item['id']; ?>)">Thêm vào giỏ hàng</button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Không có gói buffet nào được tìm thấy.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2025 Catering Vietnam. All rights reserved.</p>
    </footer>

    <script>
        function addToCart(itemId) {
            fetch('../backend/process/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        item_id: itemId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Đã thêm vào giỏ hàng!');
                        // Cập nhật số lượng giỏ hàng
                        const cartCount = document.querySelector('.cart-count');
                        cartCount.textContent = parseInt(cartCount.textContent) + 1;
                    } else {
                        alert('Có lỗi xảy ra: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</body>

</html>