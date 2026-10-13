<?php
$conn = mysqli_connect("localhost", "root", "", "inventory",3307);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);
    $sale_date = date('Y-m-d H:i:s');

    if (!empty($product_id) && !empty($customer_id) && !empty($quantity) && !empty($total_amount)) {
        // 1. Pehla check karo ke inventory ma purto stock chhe ke nahi
        $check_stock = mysqli_query($conn, "SELECT quantity_in_stock FROM inventorytable WHERE product_id = '$product_id'");
        $stock_row = mysqli_fetch_assoc($check_stock);
        
        if ($stock_row && $stock_row['quantity_in_stock'] >= $quantity) {
            
            // 2. Sales table ma entry karo
            $sql = "INSERT INTO sales (product_id, customer_id, quantity, total_amount, sale_date) 
                    VALUES ('$product_id', '$customer_id', '$quantity', '$total_amount', '$sale_date')";
            
            if (mysqli_query($conn, $sql)) {
                // 3. Inventory mathi stock minus (-) karo
                $update_inv = "UPDATE inventorytable SET quantity_in_stock = quantity_in_stock - $quantity, last_updated = NOW() WHERE product_id = '$product_id'";
                mysqli_query($conn, $update_inv);

                $msg = "Sale recorded successfully and stock updated!";
            } else {
                $msg = "Error: " . mysqli_error($conn);
            }
        } else {
            $msg = "Error: Insufficient stock available in inventory!";
        }
    } else {
        $msg = "Badha fields bharva jaruri chhe!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('inventory.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 450px;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            border: 3px solid #111;
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
            margin: 30px auto;
        }
        h3 { color: #111; text-align: center; border-bottom: 2px solid #ccc; padding-bottom: 8px; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input, select {
            width: 100%; padding: 8px 10px; border: 1px solid #aaa; border-radius: 4px; box-sizing: border-box; font-size: 14px;
        }
        .btn {
            background-color: #dc3545; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 15px;
        }
        .btn:hover { background-color: #c82333; }
        .msg { text-align: center; color: green; font-weight: bold; margin-bottom: 15px; }
        .btn-back {
            display: block; text-align: center; background-color: #555; color: white; padding: 8px; text-decoration: none; border-radius: 4px; margin-top: 10px; font-weight: bold;
        }
        .btn-back:hover { background-color: #333; }
    </style>
</head>
<body>

<div class="container">
    <h3>Add Sales Order</h3>
    
    <?php if(!empty($msg)) { echo "<p class='msg' style='color: ".(strpos($msg, 'Error') !== false ? 'red' : 'green').";'>$msg</p>"; } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Select Product:</label>
            <select name="product_id" required>
                <option value="">-- Choose Product --</option>
                <?php
                $prod_res = mysqli_query($conn, "SELECT product_id, product_name FROM product");
                while($p = mysqli_fetch_assoc($prod_res)) {
                    echo "<option value='{$p['product_id']}'>{$p['product_name']} (ID: {$p['product_id']})</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Select Customer:</label>
            <select name="customer_id" required>
                <option value="">-- Choose Customer --</option>
                <?php
                $cust_res = mysqli_query($conn, "SELECT customer_id, customer_name FROM customer");
                while($c = mysqli_fetch_assoc($cust_res)) {
                    echo "<option value='{$c['customer_id']}'>{$c['customer_name']} (ID: {$c['customer_id']})</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="quantity" placeholder="Enter Sales Quantity" required>
        </div>
        
        <div class="form-group">
            <label>Total Amount (₹):</label>
            <input type="number" step="0.01" name="total_amount" placeholder="Enter Total Amount" required>
        </div>
        
        <button type="submit" class="btn">Submit Sale</button>
    </form>

    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
</div>

</body>
</html>