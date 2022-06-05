<?php 
require "db.php"; 
session_start(); 

if (!isset($_SESSION['username'])) { 
    header("LOCATION:login.php"); 
}
?>
<form action="answers.php" method="post">
Please enter the course ID of the survey answers you would like to view.<br>
<br>
<label for="cid">Class ID: </label>
<input type="text" id="cid" name="cid"><br>
<br>
<input type="submit" value='Submit' name="Submit">
<br>
<br>
<?php
if (isset($_POST["Submit"])) { 
	$iid = getiuserid($_SESSION["username"]);
	$qlist = questions();
	$total = totalstudents($_POST["cid"]);
	foreach($qlist as $row) {
		$qid = $row[0];
	}
	$ans = totalanswers($iid, $_POST["cid"], ($qid - 1));
	$freq = ($ans / $total) * 100;
	echo "Response Rate: " . $ans . "/" . $total . "  (" . $freq . "%)";
	foreach ($qlist as $row) {  
        	echo "<br><br>Q: " . $row[1]; 
        	if($row[2] == "mc") {
			$olist = options($row[0]);
?> 
<br>
<table>
<style>
table {
  border-spacing: 15px;
}
</style>
<tr> 
<th>Response Option</th> 
<th>Frequency</th>
<th>Percent</th> 
    </tr> 
    <center>	
<?php 
    			foreach ($olist as $orow) { 
        			echo "<tr>"; 
        			echo "<td>" . $orow[0] . "</td>";
				$anscount = msanswer_count($iid, $_POST["cid"], "$orow[0]"); 
        			echo "<td>" . $anscount . "</td>";
				$ansfreq = ( $anscount / $ans ) * 100; 
        			echo "<td>" . $ansfreq . "%</td>"; 
        			echo "</tr>"; 
    			}
			echo "</table>";
		} else {
?>
<br>
<table>
<style>
table {
  border-spacing: 15px;
}
</style>
<tr> 
<th>Frequency</th>
<th>Percent</th> 
    </tr> 
    <center> 
<?php  
        			echo "<tr>"; 
				$anscount = esanswer_count($iid, $_POST["cid"], $row[0]); 
        			echo "<td>" . $anscount . "</td>"; 
				$ansfreq = ( $anscount / $ans ) * 100; 
        			echo "<td>" . $ansfreq . "%</td>";
				echo "</tr>";
				echo "</table>";
			
			$esanswers = getessays($iid, $_POST["cid"], $row[0]);
?>
<br>
<table>
<style>
table {
  border-spacing: 15px;
}
</style>
<tr> 
<th>Answers</th> 
    </tr> 
    <center>
<?php
			foreach($esanswers as $esans) {
				echo "<tr>";
				echo "<td>" . $esans[0] . "</td>";
				echo "</tr>";
				echo "</table>";
			}
		}
	}
}
?>
</form>
<form action="instructorMain.php" method="post">
<input type="submit" value='Homepage' name="Homepage">
</form>
	
 