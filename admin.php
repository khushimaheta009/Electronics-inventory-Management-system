<?php

$conn = mysqli_connect("localhost", "root", "", "inventory",3307);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Handle AJAX Requests (Save, Update, Delete, Fetch)
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'fetch') {
        $result = mysqli_query($conn, "SELECT * FROM admins");
        $admins = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $admins[] = $row;
        }
        echo json_encode(["status" => "success", "data" => $admins]);
        exit;
    }

    if ($action === 'save') {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);

        $check = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            echo json_encode(["status" => "error", "message" => "Username already exists!"]);
            exit;
        }

        $sql = "INSERT INTO admins (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Admin Details Saved Successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error."]);
        }
        exit;
    }

    if ($action === 'update') {
        $id = $_POST['id'];
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);

        $sql = "UPDATE admins SET username='$username', password='$password', email='$email', role='$role' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Admin Details Updated Successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Update failed."]);
        }
        exit;
    }

    if ($action === 'delete') {
        $id = $_POST['id'];
        mysqli_query($conn, "DELETE FROM admins WHERE id = $id");
        echo json_encode(["status" => "success", "message" => "Admin Deleted Successfully!"]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Details</title>
    <style>
        *{ box-sizing: border-box; }
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
        .form-container{
            width: 400px;
            margin: 50px auto;
            background: rgba(255,255,255,0.95);
            padding: 25px;
            border-radius: 10px;
            border: 8px solid black;
            box-shadow: 0 4px 8px rgba(0,0,0,0.54);
        }
        h2{ text-align: center; color: #333; margin: 0 0 20px 0; }
        label{ display: block; font-size: 14px; font-weight: bold; color: black; margin-top: 8px; }
        input, select{ width: 100%; padding: 10px; margin: 5px 0 12px 0; border: 1px solid #ccc; border-radius: 5px; font-family: Arial, sans-serif; font-size: 14px; }
        input:focus, select:focus{ border: 2px solid #007bff; outline: none; }
        button{ padding: 10px 18px; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 15px; margin: 3px; }
        .save-button{ width: 100%; background: green; }
        .save-button:hover{ background: darkgreen; }
        .back-button{ width: 100%; background: #555; }
        .back-button:hover{ background: #333; }
        #message{ text-align: center; margin-top: 15px; font-weight: bold; color: green; }
        .table-container{ width: 95%; max-width: 1100px; margin: 30px auto; background: rgba(255,255,255,0.95); padding: 25px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.54); overflow-x: auto; }
        table{ width: 100%; border-collapse: collapse; margin-top: 15px; background: white; }
        th, td{ border: 1px solid #999; padding: 10px; text-align: center; vertical-align: middle; }
        th{ background: #333; color: white; font-weight: bold; }
        tr:nth-child(even){ background: #f2f2f2; }
        .edit-button{ background: #007bff; padding: 7px 12px; }
        .edit-button:hover{ background: #0056b3; }
        .delete-button{ background: #dc3545; padding: 7px 12px; }
        .delete-button:hover{ background: #a71d2a; }
        .update-button{ background: green; padding: 7px 12px; }
        .update-button:hover{ background: darkgreen; }
        .cancel-button{ background: #777; padding: 7px 12px; }
        .cancel-button:hover{ background: #555; }
        .edit-input{ width: 100%; padding: 7px; border: 2px solid #007bff; border-radius: 4px; }
        .no-data{ text-align: center; color: #777; padding: 20px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Admin Details</h2>
    <form id="adminForm">
        <label>Username</label>
        <input type="text" id="username" placeholder="Enter Username" required>

        <label>Password</label>
        <input type="password" id="password" placeholder="Enter Password" required>

        <label>Email</label>
        <input type="email" id="email" placeholder="Enter Email Address" required>

        <label>Role</label>
        <select id="role" required>
            <option value="">Select Role</option>
            <option value="Admin">Admin</option>
            <option value="Manager">Manager</option>
            <option value="Staff">Staff</option>
        </select>

        <button type="submit" class="save-button">Save Admin</button>
        <button type="button" class="back-button" onclick="goBack()">Back</button>
    </form>
    <p id="message"></p>
</div>

<div class="table-container">
    <h2>Admin Records</h2>
    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Username</th>
                <th>Password</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="adminTableBody">
        </tbody>
    </table>
</div>

<script>
let admins = [];

function fetchAdmins(){
    let formData = new FormData();
    formData.append("action", "fetch");

    fetch("admin.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            admins = result.data;
            displayAdmins();
        }
    });
}

document.getElementById("adminForm").addEventListener("submit", function(event){
    event.preventDefault();

    let formData = new FormData();
    formData.append("action", "save");
    formData.append("username", document.getElementById("username").value.trim());
    formData.append("password", document.getElementById("password").value.trim());
    formData.append("email", document.getElementById("email").value.trim());
    formData.append("role", document.getElementById("role").value);

    fetch("admin.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            showMessage(result.message, "green");
            document.getElementById("adminForm").reset();
            fetchAdmins();
        } else {
            showMessage(result.message, "red");
        }
    });
});

function displayAdmins(){
    let tableBody = document.getElementById("adminTableBody");
    tableBody.innerHTML = "";

    if(admins.length === 0){
        tableBody.innerHTML = `<tr><td colspan="6" class="no-data">No admin records found.</td></tr>`;
        return;
    }

    admins.forEach(function(admin, index){
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${escapeHTML(admin.username)}</td>
            <td>${escapeHTML(admin.password)}</td>
            <td>${escapeHTML(admin.email)}</td>
            <td>${escapeHTML(admin.role)}</td>
            <td>
                <button class="edit-button" onclick="editAdmin(${admin.id})">Edit</button>
                <button class="delete-button" onclick="deleteAdmin(${admin.id})">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function editAdmin(id){
    let admin = admins.find(a => a.id == id);
    if(!admin) return;

    let rows = document.getElementById("adminTableBody").children;
    let index = admins.indexOf(admin);
    let row = rows[index];

    row.innerHTML = `
        <td>${index + 1}</td>
        <td><input type="text" id="editUsername${id}" class="edit-input" value="${escapeHTML(admin.username)}"></td>
        <td><input type="password" id="editPassword${id}" class="edit-input" value="${escapeHTML(admin.password)}"></td>
        <td><input type="email" id="editEmail${id}" class="edit-input" value="${escapeHTML(admin.email)}"></td>
        <td>
            <select id="editRole${id}" class="edit-input">
                <option value="Admin">Admin</option>
                <option value="Manager">Manager</option>
                <option value="Staff">Staff</option>
            </select>
        </td>
        <td>
            <button class="update-button" onclick="updateAdmin(${id})">Update</button>
            <button class="cancel-button" onclick="fetchAdmins()">Cancel</button>
        </td>
    `;
    document.getElementById("editRole" + id).value = admin.role;
}

function updateAdmin(id){
    let formData = new FormData();
    formData.append("action", "update");
    formData.append("id", id);
    formData.append("username", document.getElementById("editUsername" + id).value.trim());
    formData.append("password", document.getElementById("editPassword" + id).value.trim());
    formData.append("email", document.getElementById("editEmail" + id).value.trim());
    formData.append("role", document.getElementById("editRole" + id).value);

    fetch("admin.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            showMessage(result.message, "green");
            fetchAdmins();
        } else {
            alert(result.message);
        }
    });
}

function deleteAdmin(id){
    let admin = admins.find(a => a.id == id);
    if(!confirm("Are you sure you want to delete " + admin.username + "?")) return;

    let formData = new FormData();
    formData.append("action", "delete");
    formData.append("id", id);

    fetch("admin.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            showMessage(result.message, "green");
            fetchAdmins();
        }
    });
}

function showMessage(message, color){
    let messageElement = document.getElementById("message");
    messageElement.innerText = message;
    messageElement.style.color = color;
    setTimeout(() => { messageElement.innerText = ""; }, 3000);
}

function goBack(){ window.history.back(); }

function escapeHTML(value){
    return String(value).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

// Initial Data Load
fetchAdmins();
</script>

</body>
</html>