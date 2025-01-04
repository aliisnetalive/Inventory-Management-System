<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageExtension, $allowedExtensions)) {
            $newImageName = uniqid() . '.' . $imageExtension;
            $uploadPath = 'uploads/' . $newImageName;

            if (move_uploaded_file($imageTmpPath, $uploadPath)) {
                $stmt = $pdo->prepare("INSERT INTO products (name, quantity, price, description, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $quantity, $price, $description, $newImageName]);
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "Invalid image file type.";
        }
    } else {
        echo "Please upload an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="addProduct.css">
</head>
<body>
    <h1>Add Product</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="text" name="price" placeholder="Price" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Add Product</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
