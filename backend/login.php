<?php
session_start();
require_once 'includes/db_connection.php'; // Đường dẫn đúng từ /backend

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Truy vấn người dùng với role = 'user'
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND role = 'user'");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = 'user';
        header("Location: user/dashboard.php"); // Chuyển hướng đến dashboard
        exit();
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../frontend/css/normalize.css" />
    <link rel="stylesheet" href="../frontend/css/sign_in.css" />
    <link href="https://fonts.cdnfonts.com/css/nunito" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>Login - CateringVN</title>
</head>
<body class="bgc">
    <header>
        <div>
            <div class="flex items-center">
                <a class="mr-auto logo" href="../frontend/index.html">CateringVietnam<span>.</span></a>
                <a rel="noopener noreferrer" href="register.php" class="btn">Sign Up</a>
            </div>
        </div>
    </header>

    <section>
        <div class="signinForm">
            <form method="POST" action="" class="form"> <!-- Sửa action thành "" -->
                <h1 class="title logo">Login<span>.</span></h1>
                <?php if (isset($error)): ?>
                    <div style="color: red; text-align: center"><?php echo $error; ?></div>
                <?php endif; ?>
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
                                <a href="https://www.facebook.com/GeekCulture/videos/895233388546566"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"><i class="fa-brands fa-youtube"></i></a>
                                <a href="https://www.linkedin.com/in/phong-ta-06829628a/"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="cp-text">© 2025 CateringVN. All rights reserved.</p>
        </div>
    </footer>

    <script src="../frontend/js/login-error.js"></script>
</body>
</html>