<?php 
class User {
	private $user;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$user_details_query = mysqli_query($con, "SELECT *FROM user WHERE username = '$user'");
		$this->user = mysqli_fetch_array($user_details_query); 
	}

	public function GetUsername(){
		return $this->user['username'];
	}

	public function GetNumPost(){
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT jumlahpost FROM user WHERE username = '$username'");
		$row = mysqli_fetch_array($query);
		return $row['jumlahpost'];
	}

	public function GetFirstAndLastName(){
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT namadepan, namabelakang FROM user WHERE username = '$username'");
		$row = mysqli_fetch_array($query);
		return $row['namadepan'] . " " . $row['namabelakang'];
	}

	public function IsClosed(){
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT private FROM user WHERE username = '$username'");
		$row = mysqli_fetch_array($query);
		if($row['private'] == "Yes"){
			return true;
		}
		else{
			return false;
		}
	}

	public function IsFriend($username_to_check){
		$usernamecomma = ",".$username_to_check.",";

		if((strstr($this->user['teman'], $usernamecomma) || $username_to_check == $this->user['username'])){
			return true;
		}
		else{
			return false;
		}
	}

	public function GetProfilePic(){
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT fotoprofile FROM user WHERE username = '$username'");
		$row = mysqli_fetch_array($query);
		return $row['fotoprofile'];
	}

	public function getFriendArray(){
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT teman FROM user WHERE username = '$username'");
		$row = mysqli_fetch_array($query);
		return $row['teman'];
	}


	public function didReceiveRequest($user_from){
		$user_to = $this->user['username'];
		$check_request_query = mysqli_query($this->con, "SELECT *FROM friend_requests WHERE user_to = '$user_to'");
		if(mysqli_num_rows($check_request_query) > 0){
			return true;
		}
		else{
			return false;
		}
	}

	public function didSendRequest($user_to){
		$user_from = $this->user['username'];
		$check_request_query = mysqli_query($this->con, "SELECT *FROM friend_requests WHERE user_to = '$user_to'");
		if(mysqli_num_rows($check_request_query) > 0){
			return true;
		}
		else{
			return false;
		}
	}

	public function removeFriend($user_to_remove){
		$logged_in_user = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT teman FROM user WHERE username = '$user_to_remove'");
		$row = mysqli_fetch_array($query);
		$friend_array_username = $row['teman'];

		$new_friend_array = str_replace($user_to_remove . ",", "", $this->user['teman']);
		$remove_friend = mysqli_query($this->con, "UPDATE user SET teman = '$new_friend_array' WHERE username = '$logged_in_user'");

		$new_friend_array = str_replace($this->user['username'] . ",", "", $friend_array_username);
		$remove_friend = mysqli_query($this->con, "UPDATE user SET teman = '$new_friend_array' WHERE username = '$user_to_remove'");
	}

	public function sendRequest($user_to){
		$user_from = $this->user['username'];
		$query = mysqli_query($this->con, "INSERT INTO friend_requests VALUES('', '$user_to', '$user_from')");
	}

	public function getMutualFriends($user_to_check) {
		$mutualFriends = 0;
		$user_array = $this->user['teman'];
		$user_array_explode = explode(",", $user_array);

		$query = mysqli_query($this->con, "SELECT teman FROM user WHERE username='$user_to_check'");
		$row = mysqli_fetch_array($query);
		$user_to_check_array = $row['teman'];
		$user_to_check_array_explode = explode(",", $user_to_check_array);

		foreach($user_array_explode as $i) {

			foreach($user_to_check_array_explode as $j) {

				if($i == $j && $i != "") {
					$mutualFriends++;
				}
			}
		}
		return $mutualFriends;

	}
}
?>