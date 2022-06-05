<?php 
require "db.php"; 
session_start(); 

if (!isset($_SESSION['username'])) { 
    header("LOCATION:login.php"); 
}
?>
<h2>Welcome to the class registration page!</h2>
<br>
In order to register for a class, please enter your name and the Course ID number<br>
EX. Drew and 2100<br>
<br>
<form action="classReg.php" method="post">
<label for="sname">Name: </label>
<input type="text" id="sname" name="sname"><br>
<br>
<label for="cid">CID: </label>
<input type="text" id="cid" name="cid"><br>
<br>
<input type="submit" value='Submit' name="Submit">
<?php
if (isset($_POST["Submit"], $_POST["sname"], $_POST["cid"])) {
    $sid = getsuserid($_POST["sname"]);
    $user = $_SESSION["username"];
    register($sid, $_POST["cid"], $user);
header("LOCATION:studentMain.php");
echo "Registration Successful!"; 
}
?>
</form>
<form action="studentMain.php" method="post">
<input type="submit" value='Homepage' name="Homepage">
</form>