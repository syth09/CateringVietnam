<?php
session_start();
require_once '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND role = 'admin'");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = 'admin';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../../frontend/css/sign_in.css">
    <link rel="stylesheet" href="../../frontend/css/normalize.css">
</head>

<body class="bgc">
    <!-- Header -->
    <header>
        <div>
            <div class="flex items-center">
                <a class="mr-auto logo" href="../frontend/index.html">CateringVietnam<span>.</span></a>
                <a rel="noopener noreferrer" href="register.php" class="btn">Sign Up</a>
            </div>
        </div>
    </header>

    <!-- Login Form -->
    <section>
        <div class="signinForm">
            <form method="POST" action="login.php" class="form">
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <h1 class="title logo">Admin Login<span>.</span></h1>
                <div id="error-message" style="color: red; text-align: center"></div>
                <div class="inputContainer">
                    <input type="text" name="username" class="input" placeholder="Username" required />
                    <label for="" class="label">Username</label>
                </div>
                <div class="inputContainer">
                    <input type="password" name="password" class="input" placeholder="Password" required />
                    <label for="" class="label">Password</label><br /><br />
                </div>
                <div>
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div>
            <div class="footer">
                <div class="container">
                    <div class="footer-row">
                        <div class="footer-col">
                            <h4>company</h4>
                            <ul>
                                <li><a href="landing_page.html">Catering VN</a></li>
                                <li><a href="../Introduction/about-us.html">our team</a></li>
                            </ul>
                        </div>
                        <div class="footer-col">
                            <h4>get help</h4>
                            <ul>
                                <li><a href="faq.html">FAQ</a></li>
                            </ul>
                        </div>
                        <div class="footer-col">
                            <h4>our partner</h4>
                            <ul>
                                <li><a href="https://skycorporation.net/">Sky Corporation</a></li>
                                <li><a href="https://www.lua-restaurant.com/home">Lúa</a></li>
                            </ul>
                        </div>
                        <div class="footer-col">
                            <h4>follow us</h4>
                            <div class="social-links">
                                <a href="https://www.facebook.com/GeekCulture/videos/895233388546566">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                                <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                                    <i class="fa-brands fa-youtube"></i>
                                </a>
                                <a href="https://www.linkedin.com/in/phong-ta-06829628a/">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Copyright -->
            <p class="cp-text">© 2025 CateringVN. All rights reserved.</p>
        </div>
    </footer>

    <!-- Hiển thị thông báo lỗi từ PHP -->
    <script src="../frontend/js/login-error.js"></script>
</body>

</html>