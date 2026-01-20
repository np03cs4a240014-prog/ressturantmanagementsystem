<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginresturant.php");
    exit;
}

/* Fetch all menu items */
$menuItems = $pdo->query("SELECT * FROM menu ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resturant Dashboard</title>
    <style>
        body{
            background-image: url("images/stafffoodimage.jpg");
        }
        h1{
            padding:25px;
            background-color:#404E3B;
            color:#E5EDF0;
            margin-top:-20px;
            margin-right:-20px;
            margin-left:-20px;
            margin-bottom:50px;
            text-align: center;
        }
        a{
            padding:20px;
            border-radius:3px;
            background-color:white;
            color:#404E3B;
            text-decoration:none;
            font-weight:bold;

        }

         a:hover{
            padding:20px;
            border-radius:3px;
            background-color:#404E3B;
            color:white;
            text-decoration:none;
            font-weight:bold;

        }

        
        h2{
            margin-top: 200px;
            margin-right: 200px;
            margin-left:500px;
            padding:30px;
            background-color: white;
            max-height: 300px;
            border-radius: 20px;
            max-width:300px;
            align-items:center;
            text-align: center;
            color:#404E3B;
        }
        table { border-collapse: collapse;
         width:100%;
          }
        th, td { 
            border:1px solid #ccc;
             padding:10px;
              }
        th { background:#404E3B; 
            color:white; 
        }
    
    </style>
</head>
<body>

<h1>Resturant Dashboard</h1>
<a href="addfoodstaff.php">Add New Menu Item</a>
<div class="highlight">
<h2>Lets make the customers happy <br> with Good Food</h2>
</div>
<a href="logoutstaff.php">Logout as a Staff</a>




</body>
</html>
