<?php 
include("include/header.php");


if(isset($_GET['profile_username'])){
	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($con, "SELECT *FROM user WHERE username = '$username'");
	$user_array = mysqli_fetch_array($user_details_query);

	$num_friends = (substr_count($user_array['teman'], ",")) - 1;
}

if(isset($_POST['remove_friend'])){
	$user = new User($con, $userLoggedIn);
	$user->removeFriend($username);
}

if(isset($_POST['add_friend'])){
	$user = new User($con, $userLoggedIn);
	$user->sendRequest($username);
}

if(isset($_POST['respond_request'])){
	header("Location: requests.php");
}

?>

<style type="text/css">
	.model{
		margin-left: 0px;
		padding-left: 0px;
	}	
</style>

<div class="profile_left">
	<img src="<?php echo $user_array['fotoprofile']; ?>">
	<div class="profile_info">
		<p><?php echo "Posts: " . $user_array['jumlahpost']; ?></p>
		<p><?php echo "Likes: " . $user_array['jumlahlike']; ?></p>
		<p><?php echo "Friends: " . $num_friends; ?></p>
	</div>

	<form action="<?php echo $username; ?>" method = "POST">
		<?php 

		$profile_user_obj = new User($con, $username); 
		if($profile_user_obj->IsClosed()){
			header("Location: user_closed.php");
		}

		$logged_in_user_obj = new User($con, $userLoggedIn); 

		if($userLoggedIn != $username){
			if($logged_in_user_obj->IsFriend($username)){
				echo '<input type = "submit" name = "remove_friend" class = "danger" value = "Remove Friend"><br>';
			}
			else if($logged_in_user_obj->didReceiveRequest($username)){
				echo '<input type = "submit" name = "respond_request" class = "warning" value = "Wait Respond"><br>';
			}
			else if($logged_in_user_obj->didSendRequest($username)){
				echo '<input type = "submit" name = "" class = "default" value = "Request Sent"><br>';
			}
			else{
				echo '<input type = "submit" name = "add_friend" class = "success" value = "Add Friend"><br>';
			}
		}

		?>

	</form>

	<input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something!">

	<?php 

	if($userLoggedIn != $username){
		echo '<div class = "profile_info_bottom">';
			echo $logged_in_user_obj->getMutualFriends($username) . " Mutual Friends";
		echo '</div>';
	}

	 ?>

</div>

<div class="profile_main_column column">
	<div class="post_area"></div>
	<img id="loading" src="assets/icon/loading.gif">

</div>

<!-- Modal -->
<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Post Something!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>This Will Appear on User's Profile Page And NewsFeed!</p>
        <form class="profile_post" action="" method="POST">
        	<div class="form_group">
 				<textarea class="form-control" name="post_body"></textarea>
 				<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
 				<input type="hidden" name="user_to" value="<?php echo $username; ?>">
        	</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name = "post_button" id="submit_profile_post">Post</button>
      </div>
    </div>
  </div>
</div>

<script>
$(function(){
 
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	var profileUsername = '<?php echo $username; ?>';
	var inProgress = false;
 
	loadPosts(); 
    $(window).scroll(function() {
    	var bottomElement = $(".status_post").last();
    	var NoMorePost = $('.post_area').find('.NoMorePost').val();
 
      
        if (isElementInView(bottomElement[0]) && NoMorePost == 'false') {
            loadPosts();
        }
    });
 
    function loadPosts() {
        if(inProgress) {
			return;
		}
		
		inProgress = true;
		$('#loading').show();
 
		var page = $('.post_area').find('.nextPage').val() || 1; 
		$.ajax({
			url: "include/handler/ajax_load_profile.php",
			type: "POST",
			data: "page="+page+"&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
			cache:false,
 
			success: function(response) {
				$('.post_area').find('.nextPage').remove(); 
				$('.post_area').find('.NoMorePost').remove(); 
				$('.post_area').find('.NoMorePostText').remove();  
 
				$('#loading').hide();
				$(".post_area").append(response);
 
				inProgress = false;
			}
		});
    }
 

    function isElementInView (el) {
        var rect = el.getBoundingClientRect();
 
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && 
            rect.right <= (window.innerWidth || document.documentElement.clientWidth) //
        );
    }
});
 
</script>


</div>
</body>
</html>