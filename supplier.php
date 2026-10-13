<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost", "root", "", "inventory",3307);

if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if (!$conn) {
        echo json_encode(["status" => "error", "message" => "Database Connection Failed: " . mysqli_connect_error()]);
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'fetch') {
        $result = mysqli_query($conn, "SELECT supplier_id, supplier_name, contact_no, email, address FROM supplier");
        $suppliers = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $suppliers[] = [
                    "supplierId" => $row['supplier_id'],
                    "supplierName" => $row['supplier_name'],
                    "contact" => $row['contact_no'],
                    "email" => $row['email'],
                    "address" => $row['address']
                ];
            }
            echo json_encode(["status" => "success", "data" => $suppliers]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
        }
        exit;
    }

    if ($action === 'save') {
        $supplier_id = mysqli_real_escape_string($conn, $_POST['supplier_id']);
        $supplier_name = mysqli_real_escape_string($conn, $_POST['supplier_name']);
        $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        $check = mysqli_query($conn, "SELECT * FROM supplier WHERE supplier_id='$supplier_id'");
        if(mysqli_num_rows($check) > 0){
            echo json_encode(["status" => "error", "message" => "This Supplier ID already exists!"]);
            exit;
        }

        $sql = "INSERT INTO supplier (supplier_id, supplier_name, contact_no, email, address) VALUES ('$supplier_id', '$supplier_name', '$contact_no', '$email', '$address')";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Supplier Details Saved Successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($conn)]);
        }
        exit;
    }

    if ($action === 'update') {
        $supplier_id = mysqli_real_escape_string($conn, $_POST['supplier_id']);
        $supplier_name = mysqli_real_escape_string($conn, $_POST['supplier_name']);
        $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        $sql = "UPDATE supplier SET supplier_name='$supplier_name', contact_no='$contact_no', email='$email', address='$address' WHERE supplier_id='$supplier_id'";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Supplier Details Updated Successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($conn)]);
        }
        exit;
    }

    if ($action === 'delete') {
        $supplier_id = mysqli_real_escape_string($conn, $_POST['supplier_id']);
        $sql = "DELETE FROM supplier WHERE supplier_id='$supplier_id'";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Supplier Details Deleted Successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($conn)]);
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Supplier Details</title>
    <style>
        *{
            box-sizing: border-box;
        }
        body{
            font-family: Arial, sans-serif;
            background-image: url("inventory.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            min-height: 100vh;
        }
        .container{
            width: 400px;
            margin: 50px auto;
            background: rgba(255,255,255,0.95);
            padding: 25px;
            border-radius: 10px;
            border: 8px solid black;
            box-shadow: 0 4px 8px rgba(0,0,0,0.54);
        }
        h2{
            text-align: center;
            margin: 0 0 20px 0;
            color: #333333;
        }
        label{
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: black;
            margin-top: 5px;
        }
        input, textarea{
            width: 100%;
            padding: 9px;
            margin: 5px 0 12px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        textarea{
            resize: vertical;
        }
        input:focus, textarea:focus{
            border: 2px solid #007bff;
            outline: none;
        }
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
        .save-button{
            background: green;
        }
        .save-button:hover{
            background: darkgreen;
        }
        .back-button{
            background: #555;
        }
        .back-button:hover{
            background: #333;
        }
        #message{
            text-align: center;
            margin: 10px 0 0 0;
            color: green;
            font-weight: bold;
            font-size: 14px;
        }
        .table-container{
            width: 95%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
        }
        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td{
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th{
            background: green;
            color: white;
        }
        tr:nth-child(even){
            background: #f2f2f2;
        }
        .table-input{
            width: 100%;
            padding: 7px;
            border: 2px solid #007bff;
            border-radius: 4px;
            font-size: 13px;
        }
        .table-input:focus{
            outline: none;
            border-color: #0056b3;
        }
        .edit-btn{
            width: auto;
            padding: 7px 12px;
            background: #007bff;
            margin: 2px;
        }
        .edit-btn:hover{
            background: #0056b3;
        }
        .update-btn{
            width: auto;
            padding: 7px 12px;
            background: green;
            margin: 2px;
        }
        .update-btn:hover{
            background: darkgreen;
        }
        .cancel-btn{
            width: auto;
            padding: 7px 12px;
            background: #777;
            margin: 2px;
        }
        .cancel-btn:hover{
            background: #555;
        }
        .delete-btn{
            width: auto;
            padding: 7px 12px;
            background: #dc3545;
            margin: 2px;
        }
        .delete-btn:hover{
            background: #a71d2a;
        }
        .no-data{
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Supplier Details</h2>
    <form id="supplierForm">
        <label>Supplier ID</label>
        <input type="text" id="supplier_id" placeholder="Enter Supplier ID" required>

        <label>Supplier Name</label>
        <input type="text" id="supplier_name" placeholder="Enter Supplier Name" required>

        <label>Contact No</label>
        <input type="tel" id="contact_no" placeholder="Enter Contact Number" required>

        <label>Email Address</label>
        <input type="email" id="email" placeholder="Enter Email Address" required>

        <label>Address</label>
        <textarea id="address" placeholder="Enter Full Address" rows="3" required></textarea>

        <button type="submit" class="save-button">Save Details</button>
        <button type="button" class="back-button" onclick="goBack()">Back</button>
    </form>
    <p id="message"></p>
</div>

<div class="table-container">
    <h2>Supplier Records</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier ID</th>
                <th>Supplier Name</th>
                <th>Contact No</th>
                <th>Email</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="supplierTableBody">
        </tbody>
    </table>
</div>

<script>
const PHP_FILE = "<?php echo basename($_SERVER['PHP_SELF']); ?>";
let suppliers = [];

function fetchSuppliers(){
    let formData = new FormData();
    formData.append("action", "fetch");

    fetch(PHP_FILE, { method: "POST", body: formData })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            suppliers = result.data;
            displaySuppliers();
        } else {
            console.error("Fetch Error:", result.message);
        }
    })
    .catch(error => console.error("Error fetching data:", error));
}

document.getElementById("supplierForm").addEventListener("submit", function(event){
    event.preventDefault();

    let supplierId = document.getElementById("supplier_id").value.trim();
    let supplierName = document.getElementById("supplier_name").value.trim();
    let contactNo = document.getElementById("contact_no").value.trim();
    let email = document.getElementById("email").value.trim();
    let address = document.getElementById("address").value.trim();

    let formData = new FormData();
    formData.append("action", "save");
    formData.append("supplier_id", supplierId);
    formData.append("supplier_name", supplierName);
    formData.append("contact_no", contactNo);
    formData.append("email", email);
    formData.append("address", address);

    fetch(PHP_FILE, { method: "POST", body: formData })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            showMessage(result.message);
            document.getElementById("supplierForm").reset();
            fetchSuppliers();
        } else {
            alert(result.message);
        }
    })
    .catch(error => console.error("Error saving data:", error));
});

function displaySuppliers(){
    let tableBody = document.getElementById("supplierTableBody");
    tableBody.innerHTML = "";

    if(!suppliers || suppliers.length === 0){
        tableBody.innerHTML = `<tr><td colspan="7" class="no-data">No supplier records found.</td></tr>`;
        return;
    }

    suppliers.forEach(function(supplier, index){
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>${index + 1}</td>
            <td><span id="supplierId-${supplier.supplierId}">${escapeHTML(supplier.supplierId)}</span></td>
            <td><span id="supplierName-${supplier.supplierId}">${escapeHTML(supplier.supplierName)}</span></td>
            <td><span id="contact-${supplier.supplierId}">${escapeHTML(supplier.contact)}</span></td>
            <td><span id="email-${supplier.supplierId}">${escapeHTML(supplier.email)}</span></td>
            <td><span id="address-${supplier.supplierId}">${escapeHTML(supplier.address)}</span></td>
            <td id="action-${supplier.supplierId}">
                <button class="edit-btn" onclick="editSupplier('${supplier.supplierId}')">Edit</button>
                <button class="delete-btn" onclick="deleteSupplier('${supplier.supplierId}')">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function editSupplier(supplierId){
    let supplier = suppliers.find(s => s.supplierId == supplierId);
    if(!supplier) return;

    document.getElementById("supplierName-" + supplierId).innerHTML = `<input type="text" class="table-input" id="edit-name-${supplierId}" value="${escapeHTML(supplier.supplierName)}">`;
    document.getElementById("contact-" + supplierId).innerHTML = `<input type="tel" class="table-input" id="edit-contact-${supplierId}" value="${escapeHTML(supplier.contact)}">`;
    document.getElementById("email-" + supplierId).innerHTML = `<input type="email" class="table-input" id="edit-email-${supplierId}" value="${escapeHTML(supplier.email)}">`;
    document.getElementById("address-" + supplierId).innerHTML = `<input type="text" class="table-input" id="edit-address-${supplierId}" value="${escapeHTML(supplier.address)}">`;

    document.getElementById("action-" + supplierId).innerHTML = `
        <button class="update-btn" onclick="updateSupplier('${supplierId}')">Update</button>
        <button class="cancel-btn" onclick="displaySuppliers()">Cancel</button>
    `;
}

function updateSupplier(supplierId){
    let newName = document.getElementById("edit-name-" + supplierId).value.trim();
    let newContact = document.getElementById("edit-contact-" + supplierId).value.trim();
    let newEmail = document.getElementById("edit-email-" + supplierId).value.trim();
    let newAddress = document.getElementById("edit-address-" + supplierId).value.trim();

    if(newName === "" || newContact === "" || newEmail === "" || newAddress === ""){
        alert("Please fill all supplier fields!");
        return;
    }

    let formData = new FormData();
    formData.append("action", "update");
    formData.append("supplier_id", supplierId);
    formData.append("supplier_name", newName);
    formData.append("contact_no", newContact);
    formData.append("email", newEmail);
    formData.append("address", newAddress);

    fetch(PHP_FILE, { method: "POST", body: formData })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            showMessage(result.message);
            fetchSuppliers();
        } else {
            alert(result.message);
        }
    })
    .catch(error => console.error("Error updating data:", error));
}

function deleteSupplier(supplierId){
    if(!confirm("Are you sure you want to delete Supplier ID " + supplierId + "?")) return;

    let formData = new FormData();
    formData.append("action", "delete");
    formData.append("supplier_id", supplierId);

    fetch(PHP_FILE, { method: "POST", body: formData })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            showMessage(result.message);
            fetchSuppliers();
        } else {
            alert(result.message);
        }
    })
    .catch(error => console.error("Error deleting data:", error));
}

function showMessage(message){
    let messageElement = document.getElementById("message");
    messageElement.innerText = message;
    setTimeout(() => { messageElement.innerText = ""; }, 3000);
}

function goBack(){ window.history.back(); }

function escapeHTML(value){
    return String(value).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

fetchSuppliers();
</script>

</body>
</html>