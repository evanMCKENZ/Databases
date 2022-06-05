<?php 
require "db.php"; 
session_start(); 

if (!isset($_SESSION['username'])) { 
    header("LOCATION:login.php"); 
}
?>
<form action="surveySelect.php" method="post">
Please enter the course ID of the course survey you would like to take.<br>
<br>
<label for="cid">Class ID: </label>
<input type="text" id="cid" name="cid"><br>
<br>
<input type="submit" value='Submit' name="Submit">
<br>
<br>
<?php
if (isset($_POST["cid"])) {
	$_SESSION["cid"]=$_POST["cid"];
	header("LOCATION:survey.php");
}
?>
</form>
<form action="studentMain.php" method="post">
<input type="submit" value='Homepage' name="Homepage">
</form>
	