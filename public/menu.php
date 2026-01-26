<?php
include __DIR__ . '/../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// Get cart count from database
$stmt = $pdo->prepare("SELECT SUM(quantity) AS cartCount FROM cart WHERE user_id=?");
$stmt->execute([$_SESSION['user_id']]);
$row = $stmt->fetch();
$cartCount = $row['cartCount'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body{
            background-image:url("../images/backgroundimage.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color:#E5EDF0;
            font-family:Times New Roman;
        }
        h2{
            margin-top:-20px;
            margin-right:-20px;
            margin-left:-20px;
            padding:30px;
            background-color:#404E3B;
            color:#E5EDF0;
            text-align:center;
        }
        .box{
            background:#2f3b2a;
            padding:20px;
            margin:30px;
            border-radius:8px;
        }
        .search {
            width: 100%;
            max-width: 400px;
            margin: 0 auto 20px;
        }
        .search input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
            outline: none;
        }
        table{
            width:100%;
            border-collapse:collapse;
        }
        th,td{
            border:1px solid #E5EDF0;
            padding:10px;
        }
        th{
            background:#E5EDF0;
            color:#404E3B;
        }
        a{
            background-color:#2f3b2a;
            color:#E5EDF0;
            text-decoration:none;
        }
        a:hover{
            padding:8px;
        }
        .back {
            display: inline-block;
            background: #2f3b2a;
            color: #E5EDF0;
            padding: 20px;
            margin: 30px auto;
            border-radius: 10px;
            max-width: 150px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .back:hover {
            background: white;
            color: #2f3b2a;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .add-btn {
            background: #2ecc71;
            color: white;
        }
        .view-btn {
            background: #34495e;
            color: white;
        }
        .view-btn:hover,
        .add-btn:hover {
            opacity: 0.85;
        }
        .cart-count {
            text-align:center;
            margin:10px;
            padding:20px;
            background-color:green;
            color: white;
            max-width:90px;
            border-radius:3px;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>

<h2>Menu</h2>

<div class="search">
    <input type="text" id="searchInput" placeholder="Search food...">
</div>

<div class="cart-count">
     Cart Items: <strong><?= $cartCount ?></strong>
</div>

<div class="box" id="menuContainer"></div>

<div class="back">
    <a href="userdashboard.php"> Back to Dashboard</a>
</div>

<script>
function renderMenu(items){
    let html = `<table>
        <tr>
            <th>Food</th>
            <th>Description</th>
            <th>Price</th>
            <th>Action</th>
        </tr>`;

    items.forEach(item => {
        html += `<tr>
            <td>${item.name}</td>
            <td>${item.description}</td>
            <td>${parseFloat(item.price).toFixed(2)}</td>
            <td>
                <div class="action-buttons">
                    <form method="POST" action="addtocart.php" style="display:inline;">
                        <input type="hidden" name="item_id" value="${item.id}">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <button type="submit" class="btn add-btn">Add to Cart</button>
                    </form>
                    <a href="cartview.php" class="btn view-btn">
                        View Cart
                    </a>
                </div>
            </td>
        </tr>`;
    });

    html += `</table>`;
    $('#menuContainer').html(html);
}

// Fetch menu from server
$.getJSON('search_menu.php', renderMenu);

// Live search
$('#searchInput').on('input', function(){
    $.getJSON('search_menu.php', { q: $(this).val() }, renderMenu);
});
</script>

</body>
</html>