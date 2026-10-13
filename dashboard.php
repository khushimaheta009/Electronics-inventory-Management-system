<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management Dashboard</title>

    <style>
        *{
            box-sizing: border-box;
        }

        body{
            margin: 0;
            font-family: Arial, sans-serif;

            background-image: url("inventory.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;

            min-height: 100vh;
        }

        /* Dark overlay */
        body::before{
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            background: rgba(0, 0, 0, 0.35);

            z-index: -1;
        }

        .dashboard{
            width: 90%;
            max-width: 1000px;

            margin: 40px auto;

            background: rgba(255, 255, 255, 0.95);

            padding: 30px;

            border-radius: 15px;
            border: 8px solid black;

            box-shadow: 0 5px 15px rgba(0,0,0,0.6);
        }

        h1{
            text-align: center;
            margin: 0;
            color: #222;
        }

        .subtitle{
            text-align: center;
            color: #666;
            margin-top: 8px;
            margin-bottom: 30px;
        }

        .menu{
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 20px;
        }

        .menu a{
            text-decoration: none;
        }

        .menu-button{
            height: 110px;

            display: flex;
            flex-direction: column;

            justify-content: center;
            align-items: center;

            background: #f5f5f5;

            border: 2px solid #222;
            border-radius: 10px;

            color: #222;

            cursor: pointer;

            transition: 0.3s;
        }

        .menu-button:hover{
            background: green;
            color: white;

            transform: translateY(-5px);

            box-shadow: 0 5px 10px rgba(0,0,0,0.3);
        }

        .icon{
            font-size: 35px;
            margin-bottom: 8px;
        }

        .name{
            font-size: 16px;
            font-weight: bold;
        }

        .logout{
            display: block;

            width: 200px;

            margin: 30px auto 0;

            padding: 12px;

            text-align: center;

            background: #d32f2f;

            color: white;

            text-decoration: none;

            border-radius: 7px;

            font-size: 16px;
            font-weight: bold;
        }

        .logout:hover{
            background: #b71c1c;
        }

        @media(max-width: 800px){

            .menu{
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media(max-width: 500px){

            .dashboard{
                width: 95%;
                padding: 20px;
            }

            .menu{
                grid-template-columns: 1fr;
            }

        }
    </style>
</head>

<body>

<div class="dashboard">

    <h1>Inventory Management System</h1>

    <p class="subtitle">
        Dashboard
    </p>

    <div class="menu">

        <!-- Admin -->
        <a href="admin.php">
            <div class="menu-button">
                <div class="icon">👨‍💼</div>
                <div class="name">Admin</div>
            </div>
        </a>

        <!-- Category -->
        <a href="category.php">
            <div class="menu-button">
                <div class="icon">📂</div>
                <div class="name">Category</div>
            </div>
        </a>

        <!-- Customer -->
        <a href="customer.php">
            <div class="menu-button">
                <div class="icon">👤</div>
                <div class="name">Customer</div>
            </div>
        </a>

        <!-- Product -->
        <a href="product.php">
            <div class="menu-button">
                <div class="icon">📦</div>
                <div class="name">Product</div>
            </div>
        </a>

        <!-- Supplier -->
        <a href="supplier.php">
            <div class="menu-button">
                <div class="icon">🚚</div>
                <div class="name">Supplier</div>
            </div>
        </a>

        <!-- Inventory -->
        <a href="inventoryform.php">
            <div class="menu-button">
                <div class="icon">🏪</div>
                <div class="name">Inventory</div>
            </div>
        </a>

        <!-- Purchase -->
        <a href="purchase.php">
            <div class="menu-button">
                <div class="icon">🛒</div>
                <div class="name">Purchase</div>
            </div>
        </a>

        <!-- Sales -->
        <a href="sales.php">
            <div class="menu-button">
                <div class="icon">💰</div>
                <div class="name">Sales</div>
            </div>
        </a>

    </div>

    <a href="login.html" class="logout">
        Logout
    </a>

</div>

</body>
</html>