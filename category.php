<?php
// Prevent PHP warnings from breaking JSON response
error_reporting(0);
ini_set('display_errors', 0);

$conn = mysqli_connect("localhost", "root", "", "inventory",3307);

// Handle AJAX Requests
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if (!$conn) {
        echo json_encode(["status" => "error", "message" => "Database Connection Failed: " . mysqli_connect_error()]);
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'fetch') {
        $result = mysqli_query($conn, "SELECT id, category_name, description, status FROM categories");
        $categories = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $categories[] = [
                    "id" => $row['id'],
                    "name" => $row['category_name'],
                    "description" => $row['description'],
                    "status" => $row['status']
                ];
            }
            echo json_encode(["status" => "success", "data" => $categories]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
        }
        exit;
    }

    if ($action === 'save') {
        $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $sql = "INSERT INTO categories (category_name, description, status) VALUES ('$category_name', '$description', '$status')";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Category Saved Successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($conn)]);
        }
        exit;
    }

    if ($action === 'update') {
        $id = intval($_POST['id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $sql = "UPDATE categories SET category_name='$name', description='$description', status='$status' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Category Updated Successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($conn)]);
        }
        exit;
    }

    if ($action === 'delete') {
        $id = intval($_POST['id']);
        $sql = "DELETE FROM categories WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Category Deleted Successfully!"]);
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
    <title>Category Details</title>
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
            width: 500px;
            margin: 50px auto 30px auto;
            background: rgba(255,255,255,0.96);
            padding: 25px;
            border-radius: 10px;
            border: 8px solid black;
            box-shadow: 0 4px 8px rgba(0,0,0,0.54);
        }
        h2{ text-align: center; color: #333; margin: 0 0 20px 0; }
        label{ display: block; font-size: 14px; font-weight: bold; color: black; margin-top: 8px; }
        input, select, textarea{ width: 100%; padding: 10px; margin: 5px 0 12px 0; border: 1px solid #ccc; border-radius: 5px; font-family: Arial, sans-serif; font-size: 14px; }
        textarea{ resize: vertical; }
        input:focus, select:focus, textarea:focus{ outline: none; border: 2px solid #007bff; }
        button{ padding: 10px 18px; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 15px; margin: 3px; }
        .save-button{ background: green; width: 100%; }
        .save-button:hover{ background: darkgreen; }
        .back-button{ background: #555; width: 100%; }
        .back-button:hover{ background: #333; }
        #message{ text-align: center; margin-top: 15px; font-weight: bold; }
        .table-container{ width: 95%; max-width: 1200px; margin: 30px auto 50px auto; background: rgba(255,255,255,0.96); padding: 25px; border-radius: 10px; border: 8px solid black; box-shadow: 0 4px 8px rgba(0,0,0,0.54); overflow-x: auto; }
        .table-container h2{ margin-bottom: 15px; }
        table{ width: 100%; border-collapse: collapse; background: white; }
        th, td{ border: 1px solid #999; padding: 10px; text-align: center; vertical-align: middle; }
        th{ background: #333; color: white; font-weight: bold; }
        tr:nth-child(even){ background: #f2f2f2; }
        .edit-button{ background: #007bff; padding: 7px 14px; }
        .edit-button:hover{ background: #0056b3; }
        .update-button{ background: green; padding: 7px 14px; }
        .update-button:hover{ background: darkgreen; }
        .delete-button{ background: #dc3545; padding: 7px 14px; }
        .delete-button:hover{ background: #a71d2a; }
        .cancel-button{ background: #777; padding: 7px 14px; }
        .cancel-button:hover{ background: #555; }
        .edit-input{ width: 100%; padding: 7px; border: 2px solid #007bff; border-radius: 4px; font-size: 14px; }
        .no-data{ text-align: center; color: #777; padding: 20px; }
        @media(max-width:600px){
            .form-container{ width: 95%; margin-top: 25px; }
            .table-container{ width: 95%; padding: 15px; }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Category Details</h2>
    <form id="categoryForm">
        <label>Category Name</label>
        <input type="text" id="category_name" list="categoryList" placeholder="Enter or Select Category" required>
        <datalist id="categoryList">
            <option value="Mobile">
            <option value="Laptop">
            <option value="Television">
            <option value="Refrigerator">
            <option value="Washing Machine">
        </datalist>

        <label>Description</label>
        <textarea id="description" placeholder="Enter Description" rows="4" required></textarea>

        <label>Status</label>
        <select id="status" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>

        <button type="submit" class="save-button">Save Category</button>
        <button type="button" class="back-button" onclick="goBack()">Back</button>
    </form>
    <p id="message"></p>
</div>

<div class="table-container">
    <h2>Saved Categories</h2>
    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Category Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="categoryTableBody">
        </tbody>
    </table>
</div>

<script>
// Dynamic file target to prevent fetch routing errors
const PHP_FILE = "<?php echo basename($_SERVER['PHP_SELF']); ?>";
let categories = [];

function fetchCategories(){
    let formData = new FormData();
    formData.append("action", "fetch");

    fetch(PHP_FILE, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            categories = result.data;
            displayCategories();
        } else {
            console.error("Fetch Error:", result.message);
        }
    })
    .catch(error => console.error("Error fetching data:", error));
}

document.getElementById("categoryForm").addEventListener("submit", function(event){
    event.preventDefault();

    let formData = new FormData();
    formData.append("action", "save");
    formData.append("category_name", document.getElementById("category_name").value.trim());
    formData.append("description", document.getElementById("description").value.trim());
    formData.append("status", document.getElementById("status").value);

    fetch(PHP_FILE, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            showMessage(result.message, "green");
            document.getElementById("categoryForm").reset();
            fetchCategories();
        } else {
            showMessage(result.message, "red");
        }
    })
    .catch(error => console.error("Error saving data:", error));
});

function displayCategories(){
    let tableBody = document.getElementById("categoryTableBody");
    tableBody.innerHTML = "";

    if(!categories || categories.length === 0){
        tableBody.innerHTML = `<tr><td colspan="5" class="no-data">No category records found.</td></tr>`;
        return;
    }

    categories.forEach(function(category, index){
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${escapeHTML(category.name)}</td>
            <td>${escapeHTML(category.description)}</td>
            <td>${escapeHTML(category.status)}</td>
            <td>
                <button class="edit-button" onclick="editCategory(${category.id})">Edit</button>
                <button class="delete-button" onclick="deleteCategory(${category.id})">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function editCategory(id){
    let category = categories.find(c => c.id == id);
    if(!category) return;

    let index = categories.indexOf(category);
    let rows = document.getElementById("categoryTableBody").children;
    let row = rows[index];

    row.innerHTML = `
        <td>${index + 1}</td>
        <td>
            <input type="text" id="editName${id}" class="edit-input" list="categoryList" value="${escapeHTML(category.name)}">
        </td>
        <td>
            <textarea id="editDescription${id}" class="edit-input" rows="2">${escapeHTML(category.description)}</textarea>
        </td>
        <td>
            <select id="editStatus${id}" class="edit-input">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </td>
        <td>
            <button class="update-button" onclick="updateCategory(${id})">Update</button>
            <button class="cancel-button" onclick="fetchCategories()">Cancel</button>
        </td>
    `;

    document.getElementById("editStatus" + id).value = category.status;
}

function updateCategory(id){
    let formData = new FormData();
    formData.append("action", "update");
    formData.append("id", id);
    formData.append("name", document.getElementById("editName" + id).value.trim());
    formData.append("description", document.getElementById("editDescription" + id).value.trim());
    formData.append("status", document.getElementById("editStatus" + id).value);

    fetch(PHP_FILE, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            showMessage(result.message, "green");
            fetchCategories();
        } else {
            alert(result.message);
        }
    })
    .catch(error => console.error("Error updating data:", error));
}

function deleteCategory(id){
    let category = categories.find(c => c.id == id);
    let catName = category ? category.name : "this item";
    if(!confirm("Are you sure you want to delete " + catName + "?")) return;

    let formData = new FormData();
    formData.append("action", "delete");
    formData.append("id", id);

    fetch(PHP_FILE, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if(result.status === "success"){
            showMessage(result.message, "green");
            fetchCategories();
        } else {
            alert(result.message);
        }
    })
    .catch(error => console.error("Error deleting data:", error));
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

// Initial Data Load on Page Refresh
fetchCategories();
</script>

</body>
</html>