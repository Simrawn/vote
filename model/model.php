<?php

$iGetit;
$idontGetit;
$Currentprof;
$Currentclass;
$prof_classes;
$Account_exists;
$class_code;

$username;
$pass_word;
$firstname;
$lastname;
$email_addr;
$designation;


class model{

    public $con;
    public $current_prof;
    public $prof_currentclass;


    
	//verifies if login details are sensible
	//add new users to database
	//add new classes to database

	public function __construct() {
		$this->con = pg_connect("host=mcsdb.utm.utoronto.ca port=5432 dbname=jaggisi1_309 user=jaggisi1 password=74162");
		if(!$this->con){
				echo("Can't connect to the database");
				exit;
			}		
		}

    public function setAccountExists($t){
        //Sets a global variable for later reference to know if the user already exists in the database.
        global $Account_exists;
        $Account_exists = $t;
    }

	public function setProfCurrentclass($currclass){
        //Sets a global variable for later reference to know the current class that the prof is looking at.
		$this->prof_currentclass = $currclass;
        global $Currentclass ;
        $Currentclass = $this->prof_currentclass;
	}
    

    public function setUserfields($user_n,$passwrd,$fname,$lname,$e_mail,$desgn){
        global $username;
        global $pass_word;
        global $firstname;
        global $lastname;
        global $email_addr;
        global $designation;
      

        $username = $user_n;
        $pass_word = $passwrd;
        $firstname = $fname;
        $lastname = $lname;
        $email_addr = $e_mail;
        $designation = $desgn;
    }

    public function student_in_class($username, $classname){
        //Check whether the student is in a specific class
        $query = "SELECT * FROM course_enrollment WHERE student_username = $1 AND classname = $2;";
        $query_result = pg_prepare($this->con, "myquery115", $query);
        $query_result = pg_execute($this->con, "myquery115", array($username, $classname));
        return pg_num_rows($query_result);
    }

	public function setProfName($profName){
		$this->current_prof = $profName;
		global $Currentprof; 
		$Currentprof = $profName;
	}

	public function user_exists($username){
        //Checks if the user exists in the system (table appuser)
		$if_user_exists_query = "SELECT username FROM appuser WHERE username = $1;"; //make query
		$if_user = pg_prepare($this->con, "my_query", $if_user_exists_query); //prepare query
		$if_user = pg_execute($this->con, "my_query", array($username)); //execute query
		return $if_user;
		}

	public function authenticate($username, $password){
        //Authenticates the user's password
		$verify_user_password_query = "SELECT * from appuser WHERE username = $1 AND password = $2;";

		$if_verified = pg_prepare($this->con, "my_query1", $verify_user_password_query);
       	$if_verified = pg_execute($this->con, "my_query1", array($username,$password));
       	return $if_verified;
	}

	public function create_account($username, $password, $firstname,$lastname,$email,$designation){
        //Creates a new account for a user if the don't already have one
		$add_query = "INSERT INTO appuser (username, password, firstname,lastname,email,designation) VALUES ($1,$2,$3,$4,$5,$6);";
        $add_result = pg_prepare($this->con, "my_query3", $add_query);
        $add_result = pg_execute($this->con, "my_query3", array($username,$password,$firstname,$lastname,$email,$designation));
        $num_rows = pg_affected_rows($add_result);
        return pg_affected_rows($add_result); //Returns 1 if successfully added a new user (1 new row)
	}

    public function update_profile($username, $password, $firstname,$lastname,$email){
        //Updates the user profile
        $query = "UPDATE appuser SET password = $1, firstname = $2, lastname = $3, email=$4 WHERE username = $5;";
        $query_prep = pg_prepare($this->con, "my_query44", $query);
        $query_prep = pg_execute($this->con, "my_query44", array($password, $firstname, $lastname, $email, $username));
        $num_rows = pg_affected_rows($query_prep);
        return $num_rows; //Returns 1 if successfully updates

    }

	public function create_class($classname, $classcode, $prof){
        //Allows the instructor to create a new class which is then added to the classes table
        global $class_code;
        $class_code = $classcode;
		$add_query = "INSERT INTO classes (classname, classcode, professor) VALUES ($1,$2, $3);";
       	$add_result = pg_prepare($this->con, "my_query4", $add_query);
        $add_result = pg_execute($this->con, "my_query4", array($classname, $classcode, $prof));
        return pg_affected_rows($add_result);
	}

	public function GetProf_andCourse(){
        //Returns all the instructors and their respective courses
		$Prof_andCourse = array();
		$query = "SELECT classname, professor from classes;";
		$result = pg_query($this->con,$query);
		if (!$result) {
  			echo "An error occurred.\n";
  			exit;
		}
		while($row = pg_fetch_row($result)){
            $input = "$row[0] $row[1]";
            array_push($Prof_andCourse, $input);
		}
		return $Prof_andCourse;
   }
   public function authenticate_code($classname, $code){
    //Authenticates the access code for a specific class
    $query = "SELECT classcode FROM classes where classname = $1;";
    $query_result = pg_prepare($this->con, "myquery15", $query);
    $query_result = pg_execute($this->con, "myquery15", array($classname));
    $row = pg_fetch_row($query_result);
    if($row[0] == $code){ //Accesscode matched
        echo("Access Code matched");
        return True;
    }
    else{return False;}


   }

    public function enroll_student($classname, $code, $username){
        //Enrolls a student into a class if they have the correct access code
        $query2 = "INSERT INTO course_enrollment VALUES ($1, $2);";
    	$query2_result = pg_prepare($this->con, "myquery6", $query2);
    	$query2_result = pg_execute($this->con, "myquery6", array($classname, $username));
    	$query3 = "UPDATE classes SET total_enrolled = total_enrolled +1, iDontGetIt = iDontGetIt + 1 WHERE classname like $1";
    	$query3_result = pg_prepare($this->con, "myquery7", $query3);
    	$query3_result = pg_execute($this->con, "myquery7", array($classname));
 
    }
    public function check_accesscode($classname, $access_code){
        //Authenticates the access code for a specific class
        $query = "SELECT * FROM classes WHERE classname = $1;";
        $query_result = pg_prepare($this->con, "myquery05", $query);
        $query_result = pg_execute($this->con, "myquery05", array($classname));
        //$num_rows = pg_affected_rows($query_result);
        $row = pg_fetch_row($query_result);
        if(!$row){
            echo "entered stupid error statment";
            echo "An error occured.\n";
            exit;
        }
        if($row[1] == $access_code){
            return True;}
        else { return False;}

    }

    public function split_classname($classname){
        //Class name comes in the form "CSC309 Arnold". This function returns the class name without the instructor name
        //i.e in the form "CSC309"
    	$strlen = strlen($classname);
    	$new_class = "";
    	for($i=0; $i <= $strlen; $i++){
    		$char = substr($classname, $i, 1);
    		if ($char != " "){
    			$new_class .= $char;
    		}
    		else{
    			break;
    		}
    	}
    	return $new_class;

    }

    public function update_votes($student_currentclass, $vote){
        //Updates the votes that students make.

    	if ($vote == 'iGetIt'){ //Update classes table to increate iGetIt by 1, & decrease iDontGetIt by 1
    		echo "Entered case iGetIt";
    		$query = "SELECT igetit, total_enrolled FROM classes WHERE igetit = total_enrolled AND classname = $1;";
    		$query_result = pg_prepare($this->con, "myquery11", $query);
    		$query_result = pg_execute($this->con, "myquery11", array($student_currentclass));
    		$row = pg_fetch_row($query_result);

    		if(!$row){ //If iGetIt == total, & user chooses iGetIt, don't do anything, else do this:
    			echo "Entered in!";
    			$query = "UPDATE classes SET igetit = igetit + 1, idontgetit = idontgetit - 1 WHERE classname = $1;";
    			$query_result = pg_prepare($this->con, "myquery8", $query);
    			$query_result = pg_execute($this->con, "myquery8", array($student_currentclass));
            }
    	}
    	else if ($vote == 'iDontGetIt'){
    		//If iDontGetIt is full, then don't do anything. Else ++iDontGetIt and --iGetIt by 1
    		$query = "SELECT idontgetit, total_enrolled FROM classes WHERE idontgetit = total_enrolled AND classname = $1;";
    		$query_result = pg_prepare($this->con, "myquery9", $query);
    		$query_result = pg_execute($this->con, "myquery9", array($student_currentclass));
    		$row = pg_fetch_row($query_result);

    		if(!$row){ //They arent equal. Dont do anything
       			$query = "UPDATE classes SET idontgetit = idontgetit + 1, igetit = igetitt - 1 WHERE classname = $1;";
       			$query_result = pg_prepare($this->con, "myquery10", $query);
    		    $query_result = pg_execute($this->con, "myquery10", array($student_currentclass));
    		    }
    		}

    	}

    public function count_votes(){
        //Count the number of votes in a specifc class
    	$query = "SELECT igetit, idontgetit FROM classes WHERE classname = $1;";
    	$query_result = pg_prepare($this->con, "myquery12", $query);
    	$query_result = pg_execute($this->con, "myquery12", array($this->prof_currentclass));
    	$row = pg_fetch_row($query_result);
    	if (!$row) {
  			echo "An error hasssss occurred.\n";
  			exit;
		}
        global $iGetit ;
        $iGetit = "$row[0]";
        global $idontGetit ;
        $idontGetit = "$row[1]";
        global $Currentclass ;
        $Currentclass = $this->prof_currentclass;

        
    }
    
    public function prof_classes($current_prof){
        //Returns all the courses that a specific instructor is teaching
    	global $prof_classes;
    	$prof_classes = array(); //global variable of array of classes
    	$query = "SELECT classname, professor FROM classes WHERE professor = $1;";
    	$query_result = pg_prepare($this->con, "myquery13", $query);
    	
	   	$query_result = pg_execute($this->con, "myquery13", array($current_prof));
	    if (!$query_result) {
  			echo "An error occurred.\n";
  			exit;
		}
		while($row = pg_fetch_row($query_result)){
            $input = "$row[0] $row[1]";
            global $prof_classes;
            array_push($prof_classes, $input);
		}
        if(empty($prof_classes)){echo("prof classes is empty");}
		
   }

   public function getProfileDetails($user_name){
       //Returns a few of the instructor profile details
       $user_details = array();
       $query = "SELECT * FROM appuser WHERE username = $1";
       $query_result = pg_prepare($this->con, "myquery14", $query);
       $query_result = pg_execute($this->con, "myquery14", array($user_name));
       if (!$query_result) {
            echo "An error occurred.\n";
            exit;
        }
        $row = pg_fetch_row($query_result);
        array_push($user_details, $row[2]);
        array_push($user_details, $row[3]);
        array_push($user_details, $row[4]);
        return $user_details;

   }






    public function intialize_igi_idgi(){
      global $iGetit ;
      $iGetit = 0;
      global $idontGetit ;
      $idontGetit = 0;
    }
   
    
   






	public function close_con(){
		pg_close($this->con);
	}


}

?>
