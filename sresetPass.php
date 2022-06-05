<?php 
require "db.php"; 
session_start();
?>

<h1>Password Reset</h1>
To reset your password, please enter your current password and then your new password twice.<br>
<br>
<form action="sresetPass.php" method="post">
<label for="oldp">Old Password: </label>
<input type="password" id="oldp" name="oldp"><br>
<br>
<label for="newp">New Password: </label>
<input type="password" id="newp" name="newp"><br>
<label for="cnewp">Confirm New Password: </label>
<input type="password" id="cnewp" name="cnewp"><br>
<br>
<input type="submit" value='Submit' name="Submit">
<?php
if (isset($_POST["Submit"], $_POST["oldp"], $_POST["newp"])) {
	if($_POST["newp"] == $_POST["cnewp"] ){
    		$user = $_SESSION["username"];
    		sreset($_POST["oldp"], $_POST["newp"], $user); 
		header("LOCATION:login.php");
		echo "Password Reset Successful!";
	} else {
		echo "New passwords do not match, please try again!";
	}
}
?>
</form>

