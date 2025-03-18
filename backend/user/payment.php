<?php
session_start();
include "../includes/db_connection.php"; // Kết nối CSDL
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Catering Vietnam</title>
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

    <!-- Payment Section -->
    <section class="payment">
        <div class="container">
            <div class="payment-top-wrap">
                <div class="payment-top">
                    <div class="payment-top-item">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Giỏ hàng</p>
                    </div>
                    <div class="payment-top-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Địa chỉ</p>
                    </div>
                    <div class="payment-top-item active">
                        <i class="fas fa-money-check-alt"></i>
                        <p>Thanh toán</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <?php
            // Kiểm tra xem giỏ hàng có sản phẩm không
            $user_id = $_SESSION['user_id']; // Lấy user_id từ session
            $sql = "SELECT o.id, o.total_amount, o.order_date, o.status, m.dish_name, m.price, oi.quantity 
                    FROM orders o
                    JOIN order_items oi ON o.id = oi.order_id
                    JOIN menu m ON oi.menu_id = m.id
                    WHERE o.user_id = :user_id AND o.status = 'pending'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);
            $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($cart_items) {
            ?>
                <div class="payment-content row">
                    <!-- Phương thức thanh toán -->
                    <div class="payment-content-left">
                        <form action="payment_process.php" method="POST">
                            <div class="payment-content-left-method-delivery">
                                <p style="font-weight: bold;">Phương thức giao hàng</p> <br>
                                <div class="payment-content-left-method-delivery-item">
                                    <input name="deliver-method" value="Giao hàng chuyển phát nhanh" checked type="radio">
                                    <label for="">Giao hàng chuyển phát nhanh</label>
                                </div>
                            </div>
                            <br>
                            <div class="payment-content-left-method-payment">
                                <p style="font-weight: bold;">Phương thức thanh toán</p> <br>
                                <p>Mọi giao dịch đều được bảo mật và mã hóa. Thông tin thẻ tín dụng sẽ không bao giờ được lưu lại.</p> <br>
                                <div class="payment-content-left-method-payment-item">
                                    <input name="method-payment" value="credit_card" type="radio">
                                    <label for="">Thanh toán bằng thẻ tín dụng (OnePay)</label>
                                </div>
                                <div class="payment-content-left-method-payment-item-img">
                                    <img src="../frontend/images/payment/visa.png" alt="Visa">
                                </div>
                                <div class="payment-content-left-method-payment-item">
                                    <input name="method-payment" value="atm_card" type="radio">
                                    <label for="">Thanh toán bằng thẻ ATM (OnePay)</label>
                                </div>
                                <div class="payment-content-left-method-payment-item-img">
                                    <img src="../frontend/images/payment/vcb.png" alt="VCB">
                                </div>
                                <div class="payment-content-left-method-payment-item">
                                    <input name="method-payment" value="momo" type="radio">
                                    <label for="">Thanh toán Momo</label>
                                </div>
                                <div class="payment-content-left-method-payment-item-img">
                                    <img src="../frontend/images/payment/momo.png" alt="Momo">
                                </div>
                                <div class="payment-content-left-method-payment-item">
                                    <input name="method-payment" value="cod" checked type="radio">
                                    <label for="">Thu tiền tận nơi (COD)</label>
                                </div>
                            </div>
                            <div class="payment-content-right-payment">
                                <button type="submit">HOÀN THÀNH</button>
                            </div>
                        </form>
                    </div>

                    <!-- Chi tiết giỏ hàng -->
                    <div class="payment-content-right">
                        <div class="payment-content-right-button">
                            <input type="text" placeholder="Mã giảm giá/Quà tặng">
                            <button><i class="fas fa-check"></i></button>
                        </div>
                        <div class="payment-content-right-button">
                            <input type="text" placeholder="Mã cộng tác viên">
                            <button><i class="fas fa-check"></i></button>
                        </div>
                        <div class="payment-content-right-mnv">
                            <select name="" id="">
                                <option value="">Chọn mã nhân viên thân thiết</option>
                                <option value="">Dluan</option>
                                <option value="">Cduy</option>
                                <option value="">Tvinh</option>
                            </select>
                        </div>
                        <br>
                        <table>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                            <?php
                            $total_amount = 0;
                            foreach ($cart_items as $item) {
                                $subtotal = $item['price'] * $item['quantity'];
                                $total_amount += $subtotal;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['dish_name']); ?></td>
                                    <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr style="border-top: 2px solid red">
                                <td style="font-weight: bold; border-top: 2px solid #dddddd" colspan="3">Tổng</td>
                                <td style="font-weight: bold; border-top: 2px solid #dddddd">
                                    <?php echo number_format($total_amount, 0, ',', '.'); ?>đ
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php
            } else {
                echo "<p>Bạn vẫn chưa thêm sản phẩm nào vào giỏ hàng. Vui lòng chọn sản phẩm nhé!</p>";
            }
            ?>
        </div>

        <footer>
            <p>© 2025 Catering Vietnam. All rights reserved.</p>
        </footer>

        <!-- sticky header scrolling js code -->
        <script src="js/sticky-header.js"></script>
    </section>
</body>

</html>