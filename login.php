<?php
require "db.php";
session_start();
?>
<form method=post action=login.php>
Are you a...<br>
<input type="radio" id="STUD" name="1" value="Student">
<label for="STUD">Student</label><br>
<input type="radio" id="INST" name="1" value="Instructor">
<label for="INST">Instructor</label><br>
<br>
<label for="username">Username: </label>
<input type="text" id="username" name="username"><br>
<br>
<label for="password">Password: </label>
<input type="password" id="password" name="password"><br>
<br>
<input type="submit" name="login" value="login">
<?php
if ( isset($_POST["login"]) && ($_POST["1"]=="Student")) {
   $_SESSION["username"]=$_POST["username"];   
   if (authenticate_stud($_POST["username"], $_POST["password"]) == 1) {
      if( sfirsttime( $_POST["username"], $_POST["password"]) == 1 ) {
	 header("LOCATION:sresetPass.php"); 
      	 return;
      } 
      header("LOCATION:studentMain.php"); 
      return;
      }else {
      	echo '<p style="color:red">incorrect username and password</p>';
      }
}
if ( isset($_POST["login"]) && ($_POST["1"]="Instructor")) {
   $_SESSION["username"]=$_POST["username"];   
   if (authenticate_inst($_POST["username"], $_POST["password"]) == 1) { 
      if( ifirsttime( $_POST["username"], $_POST["password"]) == 1 ) {
	 header("LOCATION:iresetPass.php"); 
      	 return;
      } 
      header("LOCATION:instructorMain.php"); 
      return;
      }else {
      	echo '<p style="color:red">incorrect username and password</p>';
      }
}
if ( isset($_POST["logout"]) ) {
   session_destroy();
}
?>
</form>
