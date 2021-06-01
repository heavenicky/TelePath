<?php 
include("../../config/config.php");
include("../class/User.php");
include("../class/Post.php");

$limit = 10;

$post = new Post($con, $_REQUEST['userLoggedIn']);
$post->loadProfilePosts($_REQUEST, $limit);
?>
