<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dish_name = htmlspecialchars($_POST['dish_name']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $image = $_FILES['image'];

    if ($image['error'] == 0) {
        $target_dir = "../assets/uploads/menu_images/";
        $target_file = $target_dir . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $target_file);

        $sql = "INSERT INTO menu (dish_name, description, price, image) VALUES (:dish_name, :description, :price, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['dish_name' => $dish_name, 'description' => $description, 'price' => $price, 'image' => $target_file]);
        header('Location: ../admin/dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Menu Item</title>
</head>

<body>
    <h1>Add Menu Item</h1>
    <form method="POST" action="menu_process.php" enctype="multipart/form-data">
        <label for="dish_name">Dish Name:</label>
        <input type="text" id="dish_name" name="dish_name" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" required>
        <button type="submit">Add Item</button>
    </form>
</body>

</html>