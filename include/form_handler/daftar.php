<?php
//Declare Variable
$namad = "";//Nama Depan
$namab = "";//Nama Belakang
$email = "";//Email
$email2 = "";//Email 2
$pass = "";//Password 
$pass2 = "";//Password 2
$tanggal = "";//Tanggal Sign Up
$error_array = array();//Error Massage
$recaptcha; //Google Recaptcha

if (isset($_POST['reg_button'])){
	//Nama Depan
	$namad = strip_tags($_POST['reg_namadepan']);//Remove HTML Tags
	$namad = str_replace(' ', '', $namad);//Hilangin Spasi
	$namad = ucfirst(strtolower($namad));//Semua Huruf Kecil, Huruf Pertama Kapital
	$_SESSION['reg_namadepan'] = $namad;

	//Nama Belakang
	$namab = strip_tags($_POST['reg_namabelakang']);//Remove HTML Tags
	$namab = str_replace(' ', '', $namab);//Hilangin Spasi
	$namab = ucfirst(strtolower($namab));//Semua Huruf Kecil, Huruf Pertama Kapital
	$_SESSION['reg_namabelakang'] = $namab;

	//Email
	$email = strip_tags($_POST['reg_email']);//Remove HTML Tags
	$email = str_replace(' ', '', $email);//Hilangin Spasi
	$email = ucfirst(strtolower($email));//Semua Huruf Kecil, Huruf Pertama Kapital
	$_SESSION['reg_email'] = $email;

	//Email 2
	$email2 = strip_tags($_POST['reg_email2']);//Remove HTML Tags
	$email2 = str_replace(' ', '', $email2);//Hilangin Spasi
	$email2 = ucfirst(strtolower($email2));//Semua Huruf Kecil, Huruf Pertama Kapital
	$_SESSION['reg_email2'] = $email2;

	//Password
	$pass = strip_tags($_POST['reg_pass']);//Remove HTML Tags

	//Password
	$pass2 = strip_tags($_POST['reg_pass2']);//Remove HTML Tags

	$date = date("Y-m-d");//Tanggal Sekarang

	//Pastiin Email Sama
	if($email == $email2){
		//Pastiin Format Email Bener
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);
			//Cek Email Dipake Belum
			$cek_email = mysqli_query($con, "SELECT email FROM user WHERE email = '$email'");
			$num_rows = mysqli_num_rows($cek_email);

			if($num_rows > 0){
				array_push($error_array, "Email Already In Use<br>");
			}
		}
		else{
			array_push($error_array, "Invalid Email Format<br>");
		}
	}
	else{
		array_push($error_array, "Email Don't Match<br>");
	}

	if(strlen($namad) > 25 || strlen($namad) < 2){
		array_push($error_array, "First Name Must Be Between 2 - 25<br>");
	}

	if(strlen($namab) > 25 || strlen($namab) < 2){
		array_push($error_array, "Last Name Must Be Between 2 - 25<br>");
	}

	if($pass != $pass2){
		array_push($error_array, "Password Do Not Match<br>");
	}
	else{
		if(preg_match('/[^A-Za-z0-9]/', $pass)){
			array_push($error_array, "Password Can Only Contain Character or Number<br>");
		}
	}

	if(strlen($pass) > 30 || strlen($pass) < 5){
		array_push($error_array, "Password Must Be Between 5 - 30<br>");
	}

	if(isset($_POST['g-recaptcha-response'])) $recaptcha = $_POST['g-recaptcha-response'];

	$str = "https://www.google.com/recaptcha/api/siteverify?secret=6LferN0UAAAAAGfKbLXCfFiykHBQ2afYLcy8Xn9d"."&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR'];

	$response = file_get_contents($str);
	$response_arr = (array) json_decode($response);

	if($response_arr["success"]==false)
		array_push($error_array, "Please Check The Captcha Form<br>");


	if(empty($error_array)){
		$pass = md5($pass); //Encrypt Password
		//Generate Username
		$username = strtolower($namad . "_" . $namab);
		$cek_username = mysqli_query($con, "SELECT username FROM user WHERE username = '$username'");
		$i = 0;
		//IF Usernam Udh DiPake ADD Number
		while(mysqli_num_rows($cek_username) != 0){
			$i = $i++;
			$username = $username . $i;
			$cek_username = mysqli_query($con, "SELECT username FROM user WHERE username = '$username'");
		}
		//Pofile Pic Default
		$foto = "assets/images/profilepic/default/flash.png";

		//Insert Ke DataBase
		$query = mysqli_query($con, "INSERT INTO user VALUES('', '$namad', '$namab', '$username', '$email', '$pass', '$date', '$foto', '0', '0', 'No', ',')");

		array_push($error_array, "<span>Register Succes</span><br>");

		//Clear Session
		$_SESSION['reg_namadepan'] = "";
		$_SESSION['reg_namabelakang'] = "";
		$_SESSION['reg_email'] = "";
		$_SESSION['reg_email2'] = "";
	}
}
?>