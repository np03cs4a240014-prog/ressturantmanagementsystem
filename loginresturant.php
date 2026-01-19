<?php

include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<link rel="stylesheet" type="text/css" href="style.css">
<body>
<div class="topic">
	<h2>Resutrant Management System</h2>
</div>
	<div class="Register">
		<h2>Log In</h2>
		<form method="POST" action="">
			<input type="text" name="name" placeholder="Enter your account name" required><br>
			
			<input type="password" name="password" placeholder="Enter your account password" required><br><br>
			<button type="submit" name="LogIn">Log In</button>
		</form>
</div>
<!-- <script>
function checkPassword() {
  let password = document.getElementById("password").value;
  let confirmPassword = document.getElementById("confirm_password").value;
  let error = document.getElementById("error");

  if (password !== confirmPassword) {
    error.innerHTML = "Passwords do not match!";
    return false; // stop form submission
  }

  error.innerHTML = "";
  return true;
}
</script> -->
</body>
</html>