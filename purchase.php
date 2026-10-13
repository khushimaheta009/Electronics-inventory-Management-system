<?php
$conn = mysqli_connect("localhost", "root", "", "inventory",3307);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $supplier_id = mysqli_real_escape_string($conn, $_POST['supplier_id']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);
    $purchase_date = date('Y-m-d H:i:s');

    if (!empty($product_id) && !empty($supplier_id) && !empty($quantity) && !empty($total_amount)) {
        // 1. Purchase table ma entry karva mate
        $sql = "INSERT INTO purchase (product_id, supplier_id, quantity, total_amount, purchase_date) 
                VALUES ('$product_id', '$supplier_id', '$quantity', '$total_amount', '$purchase_date')";
        
        if (mysqli_query($conn, $sql)) {
            // 2. Check karo ke inventory table ma aa product ni row chhe ke nahi
            $check_inv = mysqli_query($conn, "SELECT * FROM inventorytable WHERE product_id = '$product_id'");
            
            if (mysqli_num_rows($check_inv) > 0) {
                // Jo row pehla thi hase to stock plus (UPDATE) karse
                $update_inv = "UPDATE inventorytable SET quantity_in_stock = quantity_in_stock + $quantity, last_updated = NOW() WHERE product_id = '$product_id'";
                mysqli_query($conn, $update_inv);
            } else {
                // Jo row na hase to navi entry (INSERT) karse
                $insert_inv = "INSERT INTO inventorytable (product_id, quantity_in_stock, last_updated) VALUES ('$product_id', '$quantity', NOW())";
                mysqli_query($conn, $insert_inv);
            }

            $msg = "Purchase added successfully and inventory updated!";
        } else {
            $msg = "Error: " . mysqli_error($conn);
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
    <title>Purchase Form</title>
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
            background-color: #28a745; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 15px;
        }
        .btn:hover { background-color: #218838; }
        .msg { text-align: center; color: green; font-weight: bold; margin-bottom: 15px; }
        .btn-back {
            display: block; text-align: center; background-color: #555; color: white; padding: 8px; text-decoration: none; border-radius: 4px; margin-top: 10px; font-weight: bold;
        }
        .btn-back:hover { background-color: #333; }
    </style>
</head>
<body>

<div class="container">
    <h3>Add Purchase Order</h3>
    
    <?php if(!empty($msg)) { echo "<p class='msg'>$msg</p>"; } ?>

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
            <label>Select Supplier:</label>
            <select name="supplier_id" required>
                <option value="">-- Choose Supplier --</option>
                <?php
                $supp_res = mysqli_query($conn, "SELECT supplier_id, supplier_name FROM supplier");
                while($s = mysqli_fetch_assoc($supp_res)) {
                    echo "<option value='{$s['supplier_id']}'>{$s['supplier_name']} (ID: {$s['supplier_id']})</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="quantity" placeholder="Enter Purchase Quantity" required>
        </div>
        
        <div class="form-group">
            <label>Total Amount (₹):</label>
            <input type="number" step="0.01" name="total_amount" placeholder="Enter Total Amount" required>
        </div>
        
        <button type="submit" class="btn">Submit Purchase Order</button>
    </form>

    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
</div>

</body>
</html>