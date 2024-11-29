<?php
require 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $unit_price = $_POST['unit_price'];
    $quantity_in_stock = $_POST['quantity_in_stock'];

    $sql = "UPDATE products SET name='$name', type='$type', unit_price='$unit_price', quantity_in_stock='$quantity_in_stock', 
            inventory_value = unit_price * quantity_in_stock WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: inventory.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Edit Product</h1>
</header>

<div class="container">
    <form method="POST" action="edit.php?id=<?php echo $product['id']; ?>">
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
        <input type="text" name="type" value="<?php echo $product['type']; ?>" required>
        <input type="number" name="unit_price" value="<?php echo $product['unit_price']; ?>" required>
        <input type="number" name="quantity_in_stock" value="<?php echo $product['quantity_in_stock']; ?>" required>
        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
