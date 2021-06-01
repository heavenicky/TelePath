<?php
require 'config/config.php';
require 'include/form_handler/daftar.php';
require 'include/form_handler/masuk.php';
?>

<html>
<head>
	<title>Welcome to Telepath</title>
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="assets/js/register.js"></script>
	<script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>

<?php  
if(isset($_POST['reg_button'])){
	echo '
	<script>
		$(document).ready(function(){
			$("#first").hide();
			$("#second").show();
			});
	</script>
	';
}
?>

<div class="model">
<div class="login_box">
	<div class="login_header">
	<h1>Telepath</h1>
	Login or Signup Below
	</div>

	<div id="first">
		<form action="register.php" method="POST">
		<input type="email" name="log_email" placeholder="Email Address" value="<?php
		if(isset($_SESSION['log_email'])){
			echo $_SESSION['log_email'];
		}
		?>" required>
		<br>
		<input type="password" name="log_pass" placeholder="Password">
		<br>
		<?php if(in_array("Email or Password Was Incorrect<br>", $error_array)) echo "Email or Password Was Incorrect<br>"; ?>
		<input type="submit" name="log_button" value="Login">
		<br>
		<a href="#" id="signup" class="signup">Register Here!</a>
	</form>
	</div>

	<div id="second">
		<form action="register.php" method="POST">
		<input type="text" name="reg_namadepan" placeholder="First Name" 
		value="<?php
		if(isset($_SESSION['reg_namadepan'])){
			echo $_SESSION['reg_namadepan'];
		}
		?>" required>
		<br>
		<?php if(in_array("First Name Must Be Between 2 - 25<br>", $error_array)) echo "First Name Must Be Between 2 - 25<br>"; ?>
		
		<input type="text" name="reg_namabelakang" placeholder="Last Name"
		value="<?php
		if(isset($_SESSION['reg_namabelakang'])){
			echo $_SESSION['reg_namabelakang'];
		}
		?>" required>
		<br>
		<?php if(in_array("Last Name Must Be Between 2 - 25<br>", $error_array)) echo "Last Name Must Be Between 2 - 25<br>"; ?>
		
		<input type="email" name="reg_email" placeholder="Email"
		value="<?php
		if(isset($_SESSION['reg_email'])){
			echo $_SESSION['reg_email'];
		}
		?>" required>
		<br>
		
		<input type="email" name="reg_email2" placeholder="Confirm Email"
		value="<?php
		if(isset($_SESSION['reg_email2'])){
			echo $_SESSION['reg_email2'];
		}
		?>" required>
		<br>
		<?php if(in_array("Email Already In Use<br>", $error_array)) echo "Email Already In Use<br>";
		 else if(in_array("Invalid Email Format<br>", $error_array)) echo "Invalid Email Format<br>"; 
		 else if(in_array("Email Don't Match<br>", $error_array)) echo "Email Don't Match<br>"; ?>
		
		<input type="password" name="reg_pass" placeholder="Password" required>
		<br>
		
		<input type="password" name="reg_pass2" placeholder="Confirm Password" required>
		<br>
		<?php if(in_array("Password Do Not Match<br>", $error_array)) echo "Password Do Not Match<br>";
		 else if(in_array("Password Can Only Contain Character or Number<br>", $error_array)) echo "Password Can Only Contain Character or Number<br>"; 
		 else if(in_array("Password Must Be Between 5 - 30<br>", $error_array)) echo "Password Must Be Between 5 - 30<br>"; ?>
		
		<div class="g-recaptcha" data-sitekey="6LferN0UAAAAACePahqadIRw1V0fyRZx4LeQirIZ" style="margin-bottom: 10px;"></div>
		<?php if(in_array("Please Check The Captcha Form<br>", $error_array)) echo "Please Check The Captcha Form<br>"; ?>
		<input type="submit" name="reg_button" value="Register">
		<br>
		<?php if(in_array("<span>Register Succes</span><br>", $error_array)) echo "<span>Register Succes</span><br>"; ?>
		<a href="#" id="signin" class="signin">Already Have An Account? Login Here!</a>
	</form>
	</div>

</div>
</div>
</body>
</html>