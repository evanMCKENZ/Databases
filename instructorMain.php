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
Welcome to the Instructor Homepage<br>
<br>
The hub for all instructor operations<br>
<br>
What would you like to do? Please click one of the buttons below.<br>
<br>
<form action="classList.php" method="post">
<input type="submit" value='Class Lists' name="Class Lists">
</form>
<form action="answers.php" method="post">
<input type="submit" value='Survey Results' name="Survey Results">
</form>
<form action="iresetPass.php" method="post">
<input type="submit" value='Change Password' name="Change Password">
</form>