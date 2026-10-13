<?php
error_reporting(0);
ini_set('display_errors', 0);

$conn = mysqli_connect("localhost", "root", "", "inventory",3307);

// Handle Form Submission (Save Product)
if (isset($_POST['save'])) {
    $product_name = $_POST['product_name'];
    $product_type = $_POST['product_type'];
    $category_id = $_POST['category_id'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $sku = $_POST['sku'];
    $barcode = $_POST['barcode'];
    $price = $_POST['price'];
    $purchase_cost = $_POST['purchase_cost'];
    $quantity = $_POST['quantity'];
    $storage_location = $_POST['storage_location'];
    $warranty_period = $_POST['warranty_period'];

    $sql = "INSERT INTO product (product_name, product_type, category_id, brand, model, sku, barcode, price, purchase_cost, quantity, storage_location, warranty_period) 
            VALUES ('$product_name', '$product_type', '$category_id', '$brand', '$model', '$sku', '$barcode', '$price', '$purchase_cost', '$quantity', '$storage_location', '$warranty_period')";
    
    mysqli_query($conn, $sql);
    header("Location: product.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM product WHERE product_id='$id'");
    header("Location: product.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <style>
        * { box-sizing: border-box; }
        body{
            font-family: Arial, sans-serif;
            background-image: url("inventory.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            min-height: 100vh;
            padding: 20px;
        }
        .container{
            width: 450px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 10px;
            border: 8px solid black;
            box-shadow: 0 4px 8px rgba(0,0,0,0.54);
        }
        h2{ text-align: center; margin: 0 0 20px 0; color: #333333; }
        label{ display: block; font-size: 14px; font-weight: bold; color: black; margin-top: 5px; }
        input{
            width: 100%;
            padding: 9px;
            margin: 5px 0 12px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        input:focus{ border: 2px solid #007bff; outline: none; }
        button{
            width: 100%;
            padding: 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
            margin-bottom: 8px;
        }
        .save-button{ background: green; }
        .save-button:hover{ background: darkgreen; }
        .back-button{ background: #555; display: block; text-align: center; text-decoration: none; padding: 10px; border-radius: 5px; color: white; font-size: 15px; }
        .back-button:hover{ background: #333; }
        
        .table-container{
            width: 98%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 4px solid black;
            overflow-x: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.54);
        }
        table{ width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
        th, td{ border: 1px solid #ccc; padding: 8px; text-align: left; white-space: nowrap; }
        th{ background: green; color: white; }
        tr:nth-child(even){ background: #f2f2f2; }
        .delete-btn { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .delete-btn:hover { background: #a71d2a; }
        .no-data{ text-align: center; color: #777; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Product Details</h2>
    <form method="POST">
        <label>Product Name</label>
        <input type="text" name="product_name" placeholder="Enter Product Name" required>

        <label>Product Type</label>
        <input type="text" name="product_type" placeholder="Enter Type (e.g. Phone)" required>

        <label>Category ID</label>
        <input type="number" name="category_id" placeholder="Enter Category ID" required>

        <label>Brand</label>
        <input type="text" name="brand" placeholder="Enter Brand" required>

        <label>Model</label>
        <input type="text" name="model" placeholder="Enter Model" required>

        <label>SKU</label>
        <input type="text" name="sku" placeholder="Enter SKU" required>

        <label>Barcode</label>
        <input type="text" name="barcode" placeholder="Enter Barcode" required>

        <label>Selling Price</label>
        <input type="number" step="0.01" name="price" placeholder="Enter Selling Price" required>

        <label>Purchase Cost</label>
        <input type="number" step="0.01" name="purchase_cost" placeholder="Enter Purchase Cost" required>

        <label>Quantity in Stock</label>
        <input type="number" name="quantity" placeholder="Enter Quantity" required>

        <label>Storage Location</label>
        <input type="text" name="storage_location" placeholder="Enter Location" required>

        <label>Warranty Period</label>
        <input type="text" name="warranty_period" placeholder="Enter Warranty" required>

        <button type="submit" name="save" class="save-button">Save Product Details</button>
        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </form>
</div>

<div class="table-container">
    <h2>Product Inventory Records</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Category ID</th>
            <th>Brand</th>
            <th>Model</th>
            <th>SKU</th>
            <th>Barcode</th>
            <th>Price</th>
            <th>Purchase Cost</th>
            <th>Stock</th>
            <th>Location</th>
            <th>Warranty</th>
            <th>Action</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM product");
        if($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['product_id']}</td>
                    <td>{$row['product_name']}</td>
                    <td>{$row['product_type']}</td>
                    <td>{$row['category_id']}</td>
                    <td>{$row['brand']}</td>
                    <td>{$row['model']}</td>
                    <td>{$row['sku']}</td>
                    <td>{$row['barcode']}</td>
                    <td>₹{$row['price']}</td>
                    <td>₹{$row['purchase_cost']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['storage_location']}</td>
                    <td>{$row['warranty_period']}</td>
                    <td><a href='product.php?delete={$row['product_id']}' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='14' class='no-data'>No product records found.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>