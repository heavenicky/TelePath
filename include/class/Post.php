<?php 
class Post {
	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	public function SubmitPost($body, $penerima){
		$body = strip_tags($body);
		$body = mysqli_real_escape_string($this->con, $body);
		$body = str_replace('\r\n', '\n', $body);
		$body = nl2br($body);
		$check_empty = preg_replace('/\s+/', '', $body);

		if($check_empty != ""){
			$tanggalpost = date("Y-m-d H:i:s");
			$pengirim = $this->user_obj->GetUsername();

			//IF User DiTimeline Sendiri, Penerima = None
			if($penerima == $pengirim){
				$penerima = "None";
			}

			//Insert Post ke Database
			$query = mysqli_query($this->con, "INSERT INTO posts VALUES('', '$body', '$pengirim', '$penerima', '$tanggalpost', 'No', 'No', '0')");
			$returned_id = mysqli_insert_id($this->con);

			//Update Jumlah Post
			$jumlahpost = $this->user_obj->GetNumPost();
			$jumlahpost++;
			$update_query = mysqli_query($this->con, "UPDATE user SET jumlahpost = '$jumlahpost' WHERE username = '$pengirim'");
		}
	}

	public function LoadPostFriend($data, $limit){
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->GetUsername();

		if($page == 1){ 
			$start = 0;
		}
		else{ 
			$start = ($page - 1) * $limit;}

		$str = "";
		$data_query = mysqli_query($this->con, "SELECT *FROM posts WHERE hapus = 'No' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {

			$num_iteration = 0; 
			$count = 1;

			while($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$body = $row['body'];
				$pengirim = $row['pengirim'];
				$tanggal_waktu = $row['tanggalpost'];

				if($row['penerima'] == "None"){
					$penerima = "";
				}
				else{
					$penerima_obj = new User($this->con, $row['penerima']);
					$namapenerima = $penerima_obj->GetFirstAndLastName();
					$penerima = " to <a href='" . $row['penerima'] . "'>" . $namapenerima . "</a>";
				}

				$pengirim_obj = new User($this->con, $pengirim);
				if($pengirim_obj->IsClosed()){
					continue;
				}

				
				if($num_iteration++ < $start)
						continue; 

					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $pengirim){
						$delete_button = "<button class = 'delete_button btn-danger' id = 'post$id'>X</button>";
					}
					else{
						$delete_button = "";
					}
				
				$user_details_query = mysqli_query($this->con, "SELECT namadepan, namabelakang, fotoprofile FROM user WHERE username = '$pengirim'");
				$user_row = mysqli_fetch_array($user_details_query);
				$namadepan = $user_row['namadepan'];
				$namabelakang = $user_row['namabelakang'];
				$fotoprofile = $user_row['fotoprofile'];
				?>

				<script>
					function toggle<?php echo $id; ?>() {
						var target = $(event.target);
						if(!target.is("a")){
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							if(element.style.display == "block")
							element.style.display = "none";
						else
							element.style.display = "block";
						}
					}
				</script>

				<?php

				$comments_check = mysqli_query($this->con, "SELECT *FROM comments WHERE post_id = '$id'");
				$comments_check_num = mysqli_num_rows($comments_check);

				//Waktu Post
				$waktusekarang = date("Y-m-d H:i:s");
				$start_date = new DateTime($tanggal_waktu);
				$end_date = new DateTime($waktusekarang);
				$interval = $start_date->diff($end_date);
				if($interval->y>=1){
					if($interval == 1){
						$time_message = $interval->y . " Year Ago" ;
					}
					else{
						$time_message = $interval->y . " Years Ago" ;
					}
				}
				else if($interval->m >= 1){
					if($interval->d == 0){
						$days = " Ago";
					}
					else if($interval->d == 1){
						$days = $interval->d . " Day Ago";
					}
					else{
						$days = $interval->d . " Days Ago";
					}
					if($interval->m == 1){
						$time_message = $interval->m . " Month". $days;
					}
					else{
						$time_message = $interval->m . " Months". $days;
					}
				}
				else if($interval->d >= 1){
					if($interval->d == 1){
						$time_message = "Yesterday";
					}
					else{
						$time_message = $interval->d . " Days Ago";
					}
				}
				else if($interval->h >= 1){
					if($interval->h == 1){
						$time_message = $interval->h . " Hour Ago";
					}
					else{
						$time_message = $interval->h . " Hours Ago";
					}
				}
				else if($interval->i >= 1){
					if($interval->i == 1){
						$time_message = $interval->i . " Minute Ago";
					}
					else{
						$time_message = $interval->i . " Minutes Ago";
					}
				}
				else{
					if($interval->s < 30){
						$time_message = "Just Now";
					}
					else{
						$time_message = $interval->s . " Seconds Ago";
					}
				}

				$str .= "<div class='status_post' onClick='javascript:toggle$id()'>
							<div class = 'post_profile_pic'>
								<img src ='$fotoprofile' width ='50'>
							</div>
							<div class = 'posted_by' style = 'color: #ACACAC;'>
							<a href = '$pengirim'> $namadepan $namabelakang </a> $penerima &nbsp;&nbsp;$time_message
							$delete_button
							</div>
							<div id = 'post_body'> $body<br><br><br></div>

							<div class = 'NewsFeedPostOptions'>
								Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
								<iframe src = 'like.php?post_id=$id' scrolling = 'no'></iframe>
							</div>

						</div>
						<div class='post_comment' id='toggleComment$id' style='display:none;'>
							<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
						</div>
						<hr>";
				

				?>
					<script>
						
						$(document).ready(function(){
							$('#post<?php echo $id; ?>').on('click', function(){
								bootbox.confirm("Are You Sure You Want to Delete This Post?", function(result){
									$.post("include/form_handler/delete_post.php?post_id=<?php echo $id; ?>", {result:result});
									if(result){
										location.reload();
									}
								});
							});
						});

					</script>
				<?php
			}

			if($count > $limit) {
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='NoMorePost' value='false'>";
			}
			else {
				$str .= "<input type='hidden' class='NoMorePost' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
			}
	}
		echo $str;
	}

	public function loadProfilePosts($data, $limit){
		$page = $data['page']; 
		$profileUser = $data['profileUsername'];
		$userLoggedIn = $this->user_obj->GetUsername();

		if($page == 1){ 
			$start = 0;
		}
		else{ 
			$start = ($page - 1) * $limit;}

		$str = "";
		$data_query = mysqli_query($this->con, "SELECT *FROM posts WHERE hapus = 'No' AND ((pengirim = '$profileUser' AND penerima = 'None') OR penerima = '$profileUser') ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {

			$num_iteration = 0; 
			$count = 1;

			while($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$body = $row['body'];
				$pengirim = $row['pengirim'];
				$tanggal_waktu = $row['tanggalpost'];



				if($num_iteration++ < $start)
						continue; 

					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $pengirim){
						$delete_button = "<button class = 'delete_button btn-danger' id = 'post$id'>X</button>";
					}
					else{
						$delete_button = "";
					}
				
				$user_details_query = mysqli_query($this->con, "SELECT namadepan, namabelakang, fotoprofile FROM user WHERE username = '$pengirim'");
				$user_row = mysqli_fetch_array($user_details_query);
				$namadepan = $user_row['namadepan'];
				$namabelakang = $user_row['namabelakang'];
				$fotoprofile = $user_row['fotoprofile'];
				?>

				<script>
					function toggle<?php echo $id; ?>() {
						var target = $(event.target);
						if(!target.is("a")){
							var element = document.getElementById("toggleComment<?php echo $id; ?>");
							if(element.style.display == "block")
							element.style.display = "none";
						else
							element.style.display = "block";
						}
					}
				</script>

				<?php

				$comments_check = mysqli_query($this->con, "SELECT *FROM comments WHERE post_id = '$id'");
				$comments_check_num = mysqli_num_rows($comments_check);

				//Waktu Post
				$waktusekarang = date("Y-m-d H:i:s");
				$start_date = new DateTime($tanggal_waktu);
				$end_date = new DateTime($waktusekarang);
				$interval = $start_date->diff($end_date);
				if($interval->y>=1){
					if($interval == 1){
						$time_message = $interval->y . " Year Ago" ;
					}
					else{
						$time_message = $interval->y . " Years Ago" ;
					}
				}
				else if($interval->m >= 1){
					if($interval->d == 0){
						$days = " Ago";
					}
					else if($interval->d == 1){
						$days = $interval->d . " Day Ago";
					}
					else{
						$days = $interval->d . " Days Ago";
					}
					if($interval->m == 1){
						$time_message = $interval->m . " Month". $days;
					}
					else{
						$time_message = $interval->m . " Months". $days;
					}
				}
				else if($interval->d >= 1){
					if($interval->d == 1){
						$time_message = "Yesterday";
					}
					else{
						$time_message = $interval->d . " Days Ago";
					}
				}
				else if($interval->h >= 1){
					if($interval->h == 1){
						$time_message = $interval->h . " Hour Ago";
					}
					else{
						$time_message = $interval->h . " Hours Ago";
					}
				}
				else if($interval->i >= 1){
					if($interval->i == 1){
						$time_message = $interval->i . " Minute Ago";
					}
					else{
						$time_message = $interval->i . " Minutes Ago";
					}
				}
				else{
					if($interval->s < 30){
						$time_message = "Just Now";
					}
					else{
						$time_message = $interval->s . " Seconds Ago";
					}
				}

				$str .= "<div class='status_post' onClick='javascript:toggle$id()'>
							<div class = 'post_profile_pic'>
								<img src ='$fotoprofile' width ='50'>
							</div>
							<div class = 'posted_by' style = 'color: #ACACAC;'>
							<a href = '$pengirim'> $namadepan $namabelakang </a>  &nbsp;&nbsp;$time_message
							$delete_button
							</div>
							<div id = 'post_body'> $body<br><br><br></div>

							<div class = 'NewsFeedPostOptions'>
								Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
								<iframe src = 'like.php?post_id=$id' scrolling = 'no'></iframe>
							</div>

						</div>
						<div class='post_comment' id='toggleComment$id' style='display:none;'>
							<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
						</div>
						<hr>";
				

				?>
					<script>
						
						$(document).ready(function(){
							$('#post<?php echo $id; ?>').on('click', function(){
								bootbox.confirm("Are You Sure You Want to Delete This Post?", function(result){
									$.post("include/form_handler/delete_post.php?post_id=<?php echo $id; ?>", {result:result});
									if(result){
										location.reload();
									}
								});
							});
						});

					</script>
				<?php
			}

			if($count > $limit) {
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='NoMorePost' value='false'>";
			}
			else {
				$str .= "<input type='hidden' class='NoMorePost' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
			}
	}
		echo $str;
	}
}

?>