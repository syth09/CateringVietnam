<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $date = htmlspecialchars($_POST['date']);
    $time = htmlspecialchars($_POST['time']);
    $people = intval($_POST['people']);

    $sql = "INSERT INTO bookings (name, phone, date, time, people) VALUES (:name, :phone, :date, :time, :people)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'phone' => $phone, 'date' => $date, 'time' => $time, 'people' => $people]);
    header('Location: ../frontend/booking.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Booking Process</title>
</head>

<body>
    <h1>Booking Process</h1>
    <form method="POST" action="booking_process.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required>
        <label for="people">Number of People:</label>
        <input type="number" id="people" name="people" required>
        <button type="submit">Book Now</button>
    </form>
</body>

</html>