<?php 
require "db.php"; 
session_start(); 
?>
<form action="survey.php" method="post"><br>
<?php
if (!isset($_SESSION['username'])) { 
    header("LOCATION:login.php"); 
}
	$qlist = questions(); 
	$cidperm = $_SESSION["cid"];
	$id = 1; 
	foreach ($qlist as $row) {
        	echo "Q: " . $row[1]; 
        	if($row[2] == "mc") {
			echo " *<br>";
			$olist = options($row[0]);
			$count = 0;
			foreach($olist as $orow) {
				echo "<input type=\"radio\" id=$count name=$id value=\"$orow[0]\"> <label for=$count>$orow[0]</label><br>";
				$count = $count + 1;
			} 
		} else {
?>
			<input type="text" id="ans" name="ans"><br>
<?php
		}
	echo "<br>";
	$id = $id + 1;
	}
?>
<input type="submit" value='Submit' name="Submit"><br><br>
* = Required Question
<?php
if (isset($_POST["Submit"])) {
	$idmark = 1;
	$iid = get_inst($cidperm);
	$qlist2 = questions();
	foreach ($qlist2 as $qrow) {
		if($qrow[2] == "mc") {
			 sendanswer($qrow[0], "$_POST[$idmark]", $iid, $cidperm);
		} else {
			if($_POST["ans"] != NULL) {
				sendanswer($qrow[0], $_POST["ans"], $iid, $cidperm);
			}
		}
		$idmark = $idmark + 1;
	}
	$sid = getsuserid($_SESSION['username']);
	update_survstat($sid, $cidperm);
}
?>
</form>
<form action="studentMain.php" method="post">
<input type="submit" value='Main Homepage' name="Main Homepage">
</form>
<form action="surveyHome.php" method="post">
<input type="submit" value='Survey Homepage' name="Survey Homepage">
</form>
	