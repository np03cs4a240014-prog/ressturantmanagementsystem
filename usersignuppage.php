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
<body>
<link rel="stylesheet" type="text/css" href="style.css">
<body>
	<div class="topic">
	<h2>Resutrant Management System</h2>
</div>
	<div class="Register">
		<h2>Register</h2>
		<form method="POST" action="">
			<input type="text" name="name" placeholder="Enter your name" required><br>
			<input type="password" name="password" placeholder="Enter your password" required><br>
			<input type="password" name="confirm_password" placeholder="Enter your confirmed password" required><br>
			<input type="tel" name="phone" placeholder="Phone" pattern="[0-9]{10}" title="Enter your number"><br><br>
			<button type="submit" name="Signup">Sign up</button>
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

