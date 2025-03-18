<?php
session_start();
require_once 'includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Kiểm tra dữ liệu đầu vào
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ!";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        // Kiểm tra xem username hoặc email đã tồn tại chưa
        $check_query = "SELECT id FROM users WHERE username = :username OR email = :email";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->execute(['username' => $username, 'email' => $email]);

        if ($check_stmt->rowCount() > 0) {
            $error = "Tên đăng nhập hoặc email đã tồn tại!";
        } else {
            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user'; // Mặc định đăng ký với vai trò user

            // Chèn dữ liệu vào CSDL
            $insert_query = "INSERT INTO users (name, username, email, password, role) VALUES (:name, :username, :email, :password, :role)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->execute([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password,
                'role' => $role
            ]);

            // Lấy ID của người dùng vừa đăng ký
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['role'] = $role;

            // Chuyển hướng đến dashboard user
            header("Location: user/dashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../frontend/css/normalize.css" />
    <link rel="stylesheet" href="../frontend/css/sign_up.css" />
    <link href="https://fonts.cdnfonts.com/css/nunito" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <title>CateringVN</title>
</head>

<body class="bgc">
    <!-- Header -->
    <header>
        <div>
            <div class="flex items-center">
                <a class="mr-auto logo" href="../frontend/index.html">
                    CateringVietnam<span>.</span></a>
            </div>
        </div>
    </header>

    <!-- Sign up form -->
    <section>
        <div class="signupForm">
            <form action="register.php" method="POST" class="form">
                <h1 class="title logo">Sign up<span>.</span></h1>
                <?php if (isset($error)): ?>
                    <div style="color: red; text-align: center"><?php echo $error; ?></div>
                <?php endif; ?>
                <div class="inputContainer">
                    <input type="text" class="input" placeholder="Full Name" name="name" required />
                    <label for="" class="label">Full Name</label>
                </div>
                <div class="inputContainer">
                    <input type="text" class="input" placeholder="Email Address" name="email" required />
                    <label for="" class="label">Email</label>
                </div>
                <div class="inputContainer">
                    <input type="text" class="input" placeholder="Username" name="username" required />
                    <label for="" class="label">Username</label>
                </div>
                <div class="inputContainer">
                    <input type="password" class="input" placeholder="Password" name="password" required />
                    <label for="" class="label">Password</label><br /><br />
                </div>
                <div class="inputContainer">
                    <input type="password" class="input" placeholder="Confirm Password" name="confirm_password" required />
                    <label for="" class="label">Confirm Password</label>
                </div>
                <label>
                    <input type="checkbox" checked="checked" name="remember" style="margin-bottom: 15px" />
                    By creating an account you agree to our
                    <a href="#" style="color: dodgerblue">Terms & Privacy</a>.
                </label>
                <input type="submit" class="submitBtn" value="Sign up" />
                <div class="signin">
                    <p>
                        Already have an account?
                        <a href="login.php">Sign in</a>.
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div>
            <div class="footer">
                <div class="container">
                    <div class="footer-row">
                        <div class="footer-col">
                            <h4>company</h4>
                            <ul>
                                <li><a href="../frontend/index.html">Catering VN</a></li>
                                <li><a href="../frontend/about.html">our team</a></li>
                            </ul>
                        </div>
                        <div class="footer-col">
                            <h4>get help</h4>
                            <ul>
                                <li><a href="../frontend/faq.html">FAQ</a></li>
                            </ul>
                            <ul>
                                <li><a href="../frontend/contact.html">Contact</a></li>
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
    </section>
</body>

</html>