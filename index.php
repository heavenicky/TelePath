<?php 
include("include/header.php");

if(isset($_POST['post'])){
	$post = new Post($con, $userLoggedIn);
	$post->submitPost($_POST['post_text'], 'None');
	header("Location: index.php");
}
 
 ?>


<div class="user_details column">
	<a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['fotoprofile']; ?>"></a>
	<div class="user_details_left_right">
		<a href= "<?php echo $userLoggedIn; ?>">
		<?php 
		echo $user['namadepan'] . " " . $user['namabelakang']. "<br>" ;
		?>
		</a>
		<?php 
		echo "Posts : " . $user['jumlahpost'] . "<br>";
		echo "Likes : " . $user['jumlahlike'];
		?>
	</div>

</div> 
<div class="main_column column">
	<form class="post_form" action="index.php" method="POST">
		<textarea name="post_text" id="post_text" placeholder="Got something to say?"></textarea>
		<input type="submit" name="post" id="post_button" value="Post">
		<hr>
	</form>

	
	 <div class="post_area"></div>
	 <img id="loading" src="assets/icon/loading.gif">

</div>

<script>
$(function(){
 
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';
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
			url: "include/handler/ajax_load_post.php",
			type: "POST",
			data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
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