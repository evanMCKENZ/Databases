<?php 
session_start();  
?>
<form action="login.php" method="post"> 
    <?php 
    if (!isset($_SESSION["username"])) { 
    ?> 
        <input type="submit" value='login' name="login">  
    <?php 
    }else {  
        echo "Welcome ". $_SESSION["username"]; 
    ?> 
        <input type="submit" value='logout' name="logout"> 
    <?php 
    } 
?> 
</form>
<br>
Welcome to the Student Homepage<br>
<br>
The hub for all student activity<br>
<br>
What would you like to do? Please click one of the buttons below.<br>
<br>
<form action="classReg.php" method="post">
<input type="submit" value='Class Registration' name="Class Registration">
</form>
<form action="surveyHome.php" method="post">
<input type="submit" value='Course Survey' name="Course Survey">
</form>
<form action="sresetPass.php" method="post">
<input type="submit" value='Change Password' name="Change Password">
</form>