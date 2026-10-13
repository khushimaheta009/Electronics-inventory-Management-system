<?php
$conn = mysqli_connect("localhost", "root", "", "inventory",3307);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
    $email_address = mysqli_real_escape_string($conn, $_POST['email_address']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if (!empty($customer_name) && !empty($contact_no)) {
        $query = "INSERT INTO customer (customer_name, contact_no, email_address, address) VALUES ('$customer_name', '$contact_no', '$email_address', '$address')";
        if (mysqli_query($conn, $query)) {
            $success_msg = "Customer successfully added!";
        } else {
            $error_msg = "Error: " . mysqli_error($conn);
        }
    } else {
        $error_msg = "Customer name and contact number are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('inventory.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 650px;
            background: #ffffff;
            padding: 25px;
            border-radius: 6px;
            border: 3px solid #111;
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
            margin: 20px auto;
        }
        h2, h3 { color: #111; border-bottom: 2px solid #ccc; padding-bottom: 8px; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #222; font-size: 14px; }
        input[type="text"], input[type="email"], textarea {
            width: 100%; padding: 10px; border: 1px solid #aaa; border-radius: 4px; box-sizing: border-box; font-size: 14px;
        }
        input:focus, textarea:focus {
            border-color: #28a745; outline: none;
        }
        .btn-submit {
            width: 100%;
            background-color: #28a745; 
            color: white; 
            padding: 12px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 15px;
            font-weight: bold;
            margin-top: 5px;
        }
        .btn-submit:hover { background-color: #218838; }
        
        .btn-back {
            display: block; 
            width: 100%;
            text-align: center;
            background-color: #555; 
            color: white; 
            padding: 10px; 
            text-decoration: none; 
            border-radius: 4px; 
            margin-top: 10px; 
            font-size: 15px;
            font-weight: bold;
            box-sizing: border-box;
        }
        .btn-back:hover { background-color: #333; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 8px 10px; text-align: left; font-size: 14px; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        .msg { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Customers</h2>

    <?php if(!empty($success_msg)) { echo "<div class='msg success'>$success_msg</div>"; } ?>
    <?php if(!empty($error_msg)) { echo "<div class='msg error'>$error_msg</div>"; } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Customer Name</label>
            <input type="text" name="customer_name" placeholder="Enter Customer Name" required>
        </div>
        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact_no" placeholder="Enter Contact Number" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email_address" placeholder="Enter Email Address">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" rows="3" placeholder="Enter Customer Address"></textarea>
        </div>
        <button type="submit" class="btn-submit">Add Customer</button>
    </form>

    <a href="dashboard.php" class="btn-back">Back</a>
</div>

<div class="container">
    <h3>Customer List</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact No</th>
            <th>Email</th>
            <th>Address</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM customer ORDER BY customer_id DESC");
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['customer_id']}</td>
                    <td>{$row['customer_name']}</td>
                    <td>{$row['contact_no']}</td>
                    <td>{$row['email_address']}</td>
                    <td>{$row['address']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>No customers found.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>