<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, username, phone, address FROM users WHERE id = :user_id";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['user_id' => $user_id]);
// $user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $sql = "UPDATE users SET phone = :phone, address = :address WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'phone' => $phone,
        'address' => $address,
        'user_id' => $user_id
    ]);

    $success = "Cập nhật thông tin thành công!";
    // Làm mới trang để hiển thị thông tin mới
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Catering Vietnam</title>
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
                    <li><a href="#">Settings</a></li>
                </ul>
            </div>
            <div class="user-content">
                <h1>Thông tin cá nhân</h1>
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <form action="profile.php" method="POST">
                    <div class="form-group">
                        <label for="name">Họ và tên:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="username">Tên tài khoản:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ:</label>
                        <input type="text" id="address" name="address" value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>">
                    </div>
                    <button type="submit" class="btn">Cập nhật</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2025 Catering Vietnam. All rights reserved.</p>
    </footer>
</body>

</html>