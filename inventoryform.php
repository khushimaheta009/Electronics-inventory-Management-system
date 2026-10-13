<?php
$conn = mysqli_connect("localhost", "root", "", "inventory",3307);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Search filter logic
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT inventorytable.*, product.product_name 
              FROM inventorytable 
              JOIN product ON inventorytable.product_id = product.product_id 
              WHERE product.product_name LIKE '%$search_query%' 
              ORDER BY inventorytable.inventory_id DESC";
} else {
    $query = "SELECT inventorytable.*, product.product_name 
              FROM inventorytable 
              JOIN product ON inventorytable.product_id = product.product_id 
              ORDER BY inventorytable.inventory_id DESC";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Inventory Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('inventory.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 750px;
            background: #ffffff;
            padding: 25px;
            border-radius: 6px;
            border: 3px solid #111;
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
            margin: 20px auto;
        }
        h3 { color: #111; border-bottom: 2px solid #ccc; padding-bottom: 8px; margin-top: 0; }
        .search-box { margin-bottom: 15px; display: flex; gap: 10px; }
        .search-box input {
            flex: 1; padding: 8px 10px; border: 1px solid #aaa; border-radius: 4px; font-size: 14px;
        }
        .search-box button {
            background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;
        }
        .search-box button:hover { background-color: #0056b3; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 8px 10px; text-align: left; font-size: 14px; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-back {
            display: block; width: 100%; text-align: center; background-color: #555; color: white; padding: 10px; text-decoration: none; border-radius: 4px; margin-top: 15px; font-weight: bold; box-sizing: border-box;
        }
        .btn-back:hover { background-color: #333; }
    </style>
</head>
<body>

<div class="container">
    <h3>Inventory Stock List & Search</h3>

    <!-- Search Form -->
    <form method="GET" action="" class="search-box">
        <input type="text" name="search" placeholder="Search by Product Name..." value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Quantity in Stock</th>
            <th>Stock Status</th>
            <th>Last Updated</th>
        </tr>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $qty = $row['quantity_in_stock'];
                
                // Low Stock Alert Condition (< 5 hove etle Low Stock)
                if ($qty <= 5) {
                    $status = "<span style='color: red; font-weight: bold;'>Low Stock ($qty)</span>";
                } else {
                    $status = "<span style='color: green; font-weight: bold;'>In Stock ($qty)</span>";
                }

                echo "<tr>
                    <td>{$row['inventory_id']}</td>
                    <td>{$row['product_name']}</td>
                    <td>{$qty}</td>
                    <td>{$status}</td>
                    <td>{$row['last_updated']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>No inventory records found.</td></tr>";
        }
        ?>
    </table>

    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
</div>

</body>
</html>