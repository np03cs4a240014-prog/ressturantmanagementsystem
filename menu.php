<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logincustomer.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Menu</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body{background:#404E3B;color:#E5EDF0;font-family:Times New Roman;}
.box{background:#2f3b2a;padding:20px;margin:30px;border-radius:8px;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #E5EDF0;padding:10px;}
th{background:#E5EDF0;color:#404E3B;}
a{color:#E5EDF0;text-decoration:none;}
</style>
</head>
<body>


    <h2>Menu</h2>
    

<div class="search"><input type="text" id="searchInput" placeholder="Search food..."></div>
<div class="box" id="menuContainer"></div>

<div class="box">
    <a href="userdashboard.php"> Back to Dashboard</a>
</div>

<script>
function renderMenu(items){
    let html = `<table>
        <tr><th>Food</th><th>Description</th><th>Price</th><th>Action</th></tr>`;
    items.forEach(item => {
        html += `<tr>
            <td>${item.name}</td>
            <td>${item.description}</td>
            <td>${parseFloat(item.price).toFixed(2)}</td>
            <td>
                <a href="add_to_cart.php?item_id=${item.id}&csrf_token=<?= $csrf ?>">Add to Cart</a>
            </td>
        </tr>`;
    });
    html += `</table>`;
    $('#menuContainer').html(html);
}

$.getJSON('search_menu.php', renderMenu);

$('#searchInput').on('input', function(){
    $.getJSON('search_menu.php', { q: $(this).val() }, renderMenu);
});
</script>

</body>
</html>
