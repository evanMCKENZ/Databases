<?php 
function connectDB() 
{
    $config = parse_ini_file("db.ini"); 
    $dbh = new PDO($config['dsn'], $config['username'], $config['password']); 
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    return $dbh; 
} 

function getsuserid($user) 
{
    try { 
        $dbh = connectDB(); 
        $statement = $dbh->prepare("SELECT s_id from student where name= :name "); 
        $statement->bindParam(":name", $user);  
        $result = $statement->execute();   
 	$row=$statement->fetch();

        return $row[0];
	}catch (PDOException $e) { 
        print "Error!" . $e->getMessage() . "<br/>"; 
        die(); 
    }
}

function getiuserid($user) 
{
    try { 
        $dbh = connectDB(); 
        $statement = $dbh->prepare("SELECT i_id from instructor where name= :user"); 
        $statement->bindParam(":user", $user);  
        $result = $statement->execute();   
	$row=$statement->fetch();

        return $row[0];
	}catch (PDOException $e) { 
        print "Error!" . $e->getMessage() . "<br/>"; 
        die(); 
    }
}

//return number of rows matching the given user and passwd.  
function authenticate_stud($user, $passwd) { 
    try { 
        $dbh = connectDB(); 
        $statement = $dbh->prepare("SELECT count(*) FROM student ". 
                                   "where name = :name and password = sha2(:passwd,256) "); 
        $statement->bindParam(":name", $user); 
        $statement->bindParam(":passwd", $passwd); 
        $result = $statement->execute(); 
        $row=$statement->fetch(); 
        $dbh=null; 
 
        return $row[0]; 
    }catch (PDOException $e) { 
        print "Error!" . $e->getMessage() . "<br/>"; 
        die(); 
    } 
}
  
function authenticate_inst($user, $passwd) { 
    try { 
        $dbh = connectDB(); 
        $statement = $dbh->prepare("SELECT count(*) FROM instructor ". 
                                   "where name = :name and password = sha2(:passwd,256) "); 
        $statement->bindParam(":name", $user); 
        $statement->bindParam(":passwd", $passwd); 
        $result = $statement->execute(); 
        $row=$statement->fetch(); 
        $dbh=null; 
 
        return $row[0]; 
    }catch (PDOException $e) { 
        print "Error!" . $e->getMessage() . "<br/>"; 
        die(); 
    } 
}

function sfirsttime($user, $psswd)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select resetpassword from student where name=:user");
	$statement->bindParam(":user", $user);
        $statement->execute();

        $row = $statement->fetch();
        $dbh = null;

	if($row[0] == 0)
	{
		return 1;
	}else{ return 0; }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function ifirsttime($user, $psswd)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select resetpassword from instructor where name=:user");
	$statement->bindParam(":user", $user);
        $statement->execute();

        $row = $statement->fetch();
        $dbh = null;

	if($row[0] == 0)
	{
		return 1;
	}else{ return 0; }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function ireset($old, $new, $user)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("update instructor set password=sha2(:new,256), resetpassword=1 where name=:name");
        $statement->bindParam(":new", $new);
	$statement->bindParam(":name", $user);
        $statement->execute();

        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function sreset($old, $new, $user)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("update student set password=sha2(:new,256), resetpassword=1 where name=:name");
        $statement->bindParam(":new", $new);
	$statement->bindParam(":name", $user);
        $statement->execute();

        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function class_list($user, $class)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select s.name, t.s_id from student s left join takes t on s.s_id=t.s_id where t.c_id= :c_id ");
        $statement->bindParam(":c_id", $class);
        $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function survey_stat($sid, $user)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select c_id, survey_datetime from takes where s_id=:s_id ");
        $statement->bindParam(":s_id", $sid);
        $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function questions()
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select distinct q_id, question, type from questions ");
        $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function options($qid)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select choice from options where q_id = :q_id");
	$statement->bindParam(":q_id", $qid);
        $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function msanswer_count($iid, $cid, $opt)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select count(*) from answers where i_id = :i_id and c_id = :c_id and answer = :opt ");
	$statement->bindParam(":i_id", $iid);
	$statement->bindParam(":c_id", $cid);
	$statement->bindParam(":opt", $opt);
        $statement->execute();
	$row = $statement->fetch();

        return $row[0];
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function esanswer_count($iid, $cid, $qid)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select count(*) from answers where i_id = :i_id and c_id = :c_id and q_id = :q_id ");
	$statement->bindParam(":i_id", $iid);
	$statement->bindParam(":c_id", $cid);
	$statement->bindParam(":q_id", $qid);
        $statement->execute();
	$row = $statement->fetch();

        return $row[0];
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function answers($surid)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select q_id, answer from answers where survey_id= :survey_id ");
	$statement->bindParam(":survey_id", $surid);
        $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getessays($iid, $cid, $qid)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select answer from answers where i_id= :i_id and c_id= :c_id and q_id= :q_id");
	$statement->bindParam(":i_id", $iid);
	$statement->bindParam(":c_id", $cid);
	$statement->bindParam(":q_id", $qid);
        $statement->execute();

        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function totalstudents($cid)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select count(*) from takes where c_id= :c_id ");
	$statement->bindParam(":c_id", $cid);
        $statement->execute();
	$row = $statement->fetch();

	return $row[0];
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function totalanswers($iid, $cid, $qid)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select count(*) from answers where i_id= :i_id and c_id= :c_id and q_id= :q_id");
	$statement->bindParam(":i_id", $iid);
	$statement->bindParam(":c_id", $cid);
	$statement->bindParam(":q_id", $qid);
        $statement->execute();
	$row = $statement->fetch();

	return $row[0];
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function get_inst($cid)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("select i_id from teaches where c_id= :c_id");
	$statement->bindParam(":c_id", $cid);
	$result = $statement->execute();
        $row = $statement->fetch();

	return $row[0];
	$dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function update_survstat($sid, $cid)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("update takes set survey_datetime=CURRENT_TIMESTAMP where s_id= :s_id and c_id= :c_id ");
	$statement->bindParam(":s_id", $sid);
	$statement->bindParam(":c_id", $cid);
	$statement->execute();

	$dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function sendanswer($qid, $ans, $iid, $cid)
{
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("insert into answers values(:q_id, :ans, :i_id, :c_id) ");
	$statement->bindParam(":q_id", $qid);
	$statement->bindParam(":ans", $ans);
	$statement->bindParam(":i_id", $iid);
	$statement->bindParam(":c_id", $cid);
        $statement->execute();

        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function register($sid, $cid, $user)
{
    try {
        $dbh = connectDB();
	$dbh->beginTransaction();

        $statement = $dbh->prepare("select c_id from course where c_id=:c_id ");
        $statement->bindParam(":c_id", $cid);
        $result = $statement->execute();
        $row = $statement->fetch();
        if ($row[0] == NULL) {
                echo "This class does not exist!";
		$dbh->rollBack();
                $dbh=null;
                return;
            }

        $statement = $dbh->prepare("insert into takes values (:s_id, :c_id, NULL) on duplicate key update c_id=:c_id");
        $statement->bindParam(":s_id", $sid);
        $statement->bindParam(":c_id", $cid);
        $statement->execute();
	$result = $statement->execute();
        $dbh->commit();

    } catch (Exception $e) {
        echo "Failedd: " . $e->getMessage();
	$dbh->rollBack();
    }

    $dbh=null;
}
?>
