<?php
session_start();
include "../includes/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $deliver_method = $_POST['deliver-method'];
    $method_payment = $_POST['method-payment'];
    $payment_date = date("Y-m-d H:i:s");

    // Cập nhật trạng thái đơn hàng
    $sql = "UPDATE orders SET status = 'completed', payment_method = :method_payment, payment_date = :payment_date 
            WHERE user_id = :user_id AND status = 'pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'method_payment' => $method_payment,
        'payment_date' => $payment_date,
        'user_id' => $user_id
    ]);

    exit();
}
