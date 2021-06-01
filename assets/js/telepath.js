$(document).ready(function(){

	//Button for Profile Post
	$('#submit_profile_post').click(function(){

		$.ajax({
			type: "POST",
			url: "include/handler/ajax_submit_profile_post.php",
			data: $('form.profile_post').serialize(),
			success: function(msg){
				$("#post_form").modal('hide');
				location.reload();
			},
			error: function(){
				alert('Failure');
			}
		});

	});

});