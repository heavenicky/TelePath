<?php
ob_start();
session_start();
$timezone = date_default_timezone_set("Asia/Jakarta");
$con = mysqli_connect("localhost", "root", "", "socialmedia");

if(mysqli_connect_errno()){
	echo "Error" . mysqli_connect_errno();
}
?>