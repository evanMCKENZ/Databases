<?php 
require "db.php"; 
session_start(); 

if (!isset($_SESSION['username'])) { 
    header("LOCATION:login.php"); 
}
?>
<h2>Student Survey Homepage</h2>
<br>
To take a survey, click the "Take Survey" button. To see a list of your outstanding/completed surveys, click the "Status" button.
<br>
<br>
<form action="surveySelect.php" method="post">
<input type="submit" value='Take Survey' name="Take Survey">
</form>
<form action="surveyHome.php" method="post">
<input type="submit" value='Status' name="Status">
</form>
<?php
if (isset($_POST["Status"])) { 
$sid = getsuserid($_SESSION["username"]);
$status = survey_stat($sid, $_SESSION["username"]);  
?> 
    <br>
    <br>
    <table>
<style>
table {
  border-spacing: 25px;
}
</style>
    <tr> 
    <th>Course ID</th> 
    <th>Survey Status</th>  
    </tr> 
    <center>
    <?php 
    foreach ($status as $row) { 
        echo "<tr>"; 
        echo "<td>" . $row[0] . "</td>";
	if($row[1] == NULL){ 
        	echo "<td>Incomplete </td>";
	} else{
		echo "<td>Completed " . $row[1] . "</td>";
	}
        echo "</tr>"; 
    } 
    echo "<table>";
}
?>
</form>
<form action="studentMain.php" method="post">
<input type="submit" value='Homepage' name="Homepage">
</form>