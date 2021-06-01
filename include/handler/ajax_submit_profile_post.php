<?php 
require '../../config/config.php';
include("../class/User.php");
include("../class/Post.php");

if(isset($_POST['post_body'])){
	$post = new Post($con, $_POST['user_from']);
	$post->SubmitPost($_POST['post_body'], $_POST['user_to']);
}
 

 ?>