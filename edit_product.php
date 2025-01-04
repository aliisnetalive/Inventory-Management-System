<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$product = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$product->execute([$id]);
$product = $product->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, quantity = ?, price = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $quantity, $price, $description, $id]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="editProduct.css">
</head>
<body>
    <h1>Edit Product</h1>
    <form method="POST">
        <input type="text" name="name" value="<?= $product['name'] ?>" required>
        <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>
        <input type="text" name="price" value="<?= $product['price'] ?>" required>
        <textarea name="description"><?= $product['description'] ?></textarea>
        <button type="submit">Update Product</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
