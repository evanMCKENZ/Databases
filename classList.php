<?php 
require "db.php"; 
session_start(); 
 
if (!isset($_SESSION['username'])) { 
    header("LOCATION:login.php"); 
}
?>
<form action="classList.php" method="post">
Please enter the course ID of the list you would like to view.<br>
<br>
<label for="cid">Class ID: </label>
<input type="text" id="cid" name="cid"><br>
<br>
<input type="submit" value='Submit' name="Submit">
<br>
<?php
if (isset($_POST["Submit"])) { 
$iid = getiuserid($_SESSION["username"]);
$lists = class_list($iid, $_POST["cid"]);  
?> 
    <br>
    <br>
    <table>
<style>
table {
  border-spacing: 15px;
}
</style>
    <tr> 
    <th>Student Name</th> 
    <th>Student ID</th>  
    </tr> 
    <center>
    <?php 
    foreach ($lists as $row) { 
        echo "<tr>"; 
        echo "<td>" . $row[0] . "</td>"; 
        echo "<td>" . $row[1] . "</td>"; 
        echo "</tr>"; 
    } 
    echo "<table>";
}
?>
</form>
<form action="instructorMain.php" method="post">
<input type="submit" value='Homepage' name="Homepage">
</form>
