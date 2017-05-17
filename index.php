<?php

    
	require_once "model/model.php";
	require_once "model/consts.php";

	session_save_path("sess");
	session_start();

	ini_set('display_errors', 'On');

	$errors=array();
	$view="";
    
    //create a connection instance for each session
	$model_database = new model();
	

	/*controller code*/
	//If the state has not been set yet, set it to LOGIN
	if(!isset($_SESSION['state'])){
		$_SESSION['state']="login";

		$_SESSION['coursecode_and_Prof_name'] = array();

		$_SESSION['student_currentclass'] = "";
		$_SESSION['prof_currentclass'] ="";
		
        //profile info
		$_SESSION['username'] = "";
        $_SESSION['password'] = "";
        $_SESSION['first_name'] = "";
        $_SESSION['last_name'] = "";
        $_SESSION['email'] = "";
        $_SESSION['designation'] = "";
        $_SESSION['profile_created'] = False;

	}
	
	switch($_SESSION['state']){
		case "login":		
			//this view displays the login page

			$view = "login.php";
			//Check if submit or not
			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!= "login"){
				break;
			}
			if(empty($_REQUEST['user'])){
				$errors[] = "Please enter a username dear friend. We'd like to be on a nickname basis with you ;).";
				}
            if(empty($_REQUEST['password'])){
				$errors[] = "Please enter a password dear friend. We can't just let you into anyone's account ;)";
				}
            
            if(!empty($errors))break;          
            
			//Check if username exists in the database
			$if_user = $model_database->user_exists($_REQUEST['user']);
           

			if (pg_num_rows($if_user) == 1){ //User exists
				$_SESSION['profile_created'] = True;
				$model_database-> setAccountExists($_SESSION['profile_created']);
				//TO DO: provide feedback message for when username exists
				//Now verify that the user provides the correct password
				$if_verified = $model_database->authenticate($_REQUEST["user"],$_REQUEST["password"]);      
				if (pg_num_rows($if_verified) == 1){ //username & password is correct. Now take to them to instructor/student page
												                          
					$user_details = pg_fetch_row($if_verified);
					$designation = $user_details[5]; //Accessing their designation
					$_SESSION['username'] = $_REQUEST["user"];
					$_SESSION['password'] = $_REQUEST['password'];
					$profile_details = $model_database->getProfileDetails($_SESSION['username']);
					$_SESSION['first_name'] = $profile_details[0];
                    $_SESSION['last_name'] = $profile_details[1];
                    $_SESSION['email'] = $profile_details[2];
                    $_SESSION['designation'] = $designation;

					if ($designation == 'instructor'){						
						    $model_database->setProfName($_SESSION['username']); //set global
							$model_database->prof_classes($_SESSION['username']); //set global
							$_SESSION['state'] = 'instructor_create_class';
							$view = 'instructor_createclass.php';
                         }					
					else if($designation == "student"){
						$_SESSION['state'] = 'student_join_class'; 
						$view = "student_joinclass.php";
					   }
				    }

				else {
					   echo ("password incorrect");
					 }

		    	}
			
			else if (pg_num_rows($if_user) == 0){ //user does not exist. We need to put them in the database
					 		  				     // After they make a new profile on profile view page
				     echo "Username does not exist in our records, kindly enter details to make an account so you can be part of the best new thing at UTM!\n";
					 $_SESSION['state']='profile';
					 $view = 'profile.php';
					 }
			break;
		
		case "profile":

			if(isset($_REQUEST['change_to_logout']) AND $_REQUEST['change_to_logout'] == "Logout"){
				session_unset();
        	    session_destroy();
				$_SESSION['state'] = "logout";
				$view = "login.php";
				break;
			}

			if(isset($_REQUEST['change_to_profile']) AND $_REQUEST['change_to_profile'] == "Profile"){
				$model_database->setAccountExists(True);
				global $Account_exists;
				$model_database->setUserfields($_SESSION["username"],$_SESSION["password"],$_SESSION["first_name"],$_SESSION["last_name"],$_SESSION["email"],$_SESSION["designation"]);
				$_SESSION['state'] = "profile";
				$view = "profile.php";
				break;
				}
			
			$view = 'profile.php';
		
			if(pg_num_rows($model_database->user_exists($_SESSION['username'])) == 1){ // User wants to update
		    	$model_database-> setAccountExists($_SESSION['profile_created']);

		    	$view = 'profile.php';

		    	$_SESSION['password'] = $_REQUEST['password'];
			    $_SESSION['first_name'] = $_REQUEST['firstName'];
			    $_SESSION['last_name'] = $_REQUEST['lastName'];
			    $_SESSION['email'] = $_REQUEST['email'];
			    
			    $if_updated = $model_database->update_profile($_SESSION['username'],$_SESSION['password'], $_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['email']);
			    if($if_updated != 0){//profile sucessfully updated
			    	echo ("Your profile was successfully updated!");
			    	}
			    //send to a function in model.php that updates a row(profile) in the table appuser
			    // set a global variable to $_SESSION["profilecreated"]
			    // send all the required gloabl varibles to the view

			    if($_REQUEST['type'] == "instructor"){
            		$_SESSION['username'] = $username;
            		$_SESSION['state'] = "instructor_create_class";
            		$view = "instructor_createclass.php";
            		}

            	else if($_REQUEST['type'] == "student"){
            		$_SESSION['username'] = $username;
            		$_SESSION['state'] = "student_join_class";
            		$view = "student_joinclass.php";
            		}
		  	    }

		    //First time logging in, needs a new profile to be created
		   // $view = "register.php";
		    if(empty($_REQUEST['submit']) || $_REQUEST['submit']!= "makeprofile"){
				break;
			  }

			if(empty($_REQUEST['user'])){
				$errors[] = "Please enter a username, dear friend. We'd like to be on a nickname basis with you ;)";
				}
            if(empty($_REQUEST['password'])){
				$errors[] = "Password is required, dear friend.";
				}
		
			if(empty($_REQUEST['firstName'])){
				$errors[] = "First name is required, dear friend.";
				}
            if(empty($_REQUEST['lastName'])){ 
                $errors[] = "Last name is required, dear friend.";
                }
            if(empty($_REQUEST['email'])){ 
                $errors[] = "Email is required, dear friend.";
                }
            if(empty($_REQUEST['type'])){ 
                $errors[] = "Choose either instructor or student as your designation.";
                }

			if(!empty($errors))break;

			$username = $_REQUEST['user'];
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $_REQUEST['password'];
			$_SESSION['first_name'] = $_REQUEST['firstName'];
			$_SESSION['last_name'] = $_REQUEST['lastName'];
			$_SESSION['email'] = $_REQUEST['email'];
			$_SESSION['designation'] = $_REQUEST['type'];
			
            //adding one user to database
            //counting if one row is affected
            //If username already exists, tell user to try again.
			$constraints = new consts($_SESSION['username'], $_SESSION['password'], $_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['email']);
			$if_passed = $constraints->test_constraints($_SESSION['username'], $_SESSION['password'], $_SESSION['first_name'], $_SESSION['last_name'],$_SESSION['email']);
			//Check if all answers in if_passed are True
			$flag = True;
			for ($i=0; $i<4; $i++){
			    if ($if_passed[$i] == False ){
			    	$flag = False;
			    	break;
			    	}
			    }
			if ($flag == True){ //passed all constraints*/
           		$affected = $model_database->create_account($_SESSION['username'], $_SESSION['password'], $_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['email'],	$_SESSION['designation']); 
				if($affected == 1){ //If we successfully added a new profile, move on
					echo("You have succefully made a profile! \n");   
					//Initialising a variable to keep track on whether or not user has already created
					//their profile
					$_SESSION['profile_created'] = True;
					$model_database-> setAccountExists($_SESSION['profile_created']);


            		//Change view based on whether they are an instructor or a student
            		if($_REQUEST['type'] == "instructor"){
            			$_SESSION['username'] = $username;
            			$_SESSION['state'] = "instructor_create_class";
            			$view = "instructor_createclass.php";
            			}
            		else if($_REQUEST['type'] == "student"){
            			$_SESSION['username'] = $username;
            			$_SESSION['state'] = "student_join_class";
            			$view = "student_joinclass.php";
            			}
					}

				else{
					echo ("\nDidnt successfully add a row\n ");
					break;
				    }
					break;
        		 }//closing if flag=true 
			break;
		
		case "instructor_current_class":

			if(isset($_REQUEST['change_to_logout']) AND $_REQUEST['change_to_logout'] == "Logout"){
				session_unset();
        	    session_destroy();
				$_SESSION['state'] = "logout";
				$view = "login.php";
				break;
			   }
			if(isset($_REQUEST['change_to_profile']) AND $_REQUEST['change_to_profile'] == "Profile"){
				$model_database->setAccountExists(True);
				global $Account_exists;
				echo ("account exists = $Account_exists");
				$model_database->setUserfields($_SESSION["username"],$_SESSION["password"],$_SESSION["first_name"],$_SESSION["last_name"],$_SESSION["email"],$_SESSION["designation"]);
				$_SESSION['state'] = "profile";
				$view = "profile.php";
				break;
			    }

		    // this view displays the instructor current class page
		    $view = 'instructor_currentclass.php';   
		    $ok = $_SESSION['prof_currentclass'];
		    $class = $model_database->split_classname($_SESSION['prof_currentclass']);
            $model_database->setProfCurrentclass($class);	
            $model_database->setProfName($_SESSION['username'])	;
			$model_database->count_votes();
			$view = 'instructor_currentclass.php';

			break;

		case "instructor_create_class":
			if(isset($_REQUEST['change_to_logout']) AND $_REQUEST['change_to_logout'] == "Logout"){
				session_unset();
        	    session_destroy();
				$_SESSION['state'] = "logout";
				$view = "login.php";
				break;
			}
			if(isset($_REQUEST['change_to_profile']) AND $_REQUEST['change_to_profile'] == "Profile"){
				$model_database->setAccountExists(True);
				global $Account_exists;
				$model_database->setUserfields($_SESSION["username"],$_SESSION["password"],$_SESSION["first_name"],$_SESSION["last_name"],$_SESSION["email"],$_SESSION["designation"]);
				$_SESSION['state'] = "profile";
				$view = "profile.php";
				break;
			}

			//this view displays the instructorcreate class page
			$view = "instructor_createclass.php";
		    //Setting global variables name, class, list of classes
            $model_database->setProfName($_SESSION['username']); //set global
			$model_database->prof_classes($_SESSION['username']); //set global

			if (isset($_POST['submit_new_class'])){
				$model_database->intialize_igi_idgi();
				echo ("entered submit1");
			 	
			if(empty($_REQUEST['class'])){
					$errors[] = "class is required BRUH";
					}

			if(empty($_REQUEST['code'])){
					$errors[] = "code is required";
					}

            if (empty($_SESSION['username'])){
            		$errors[] = "username required";
            	}

			if(!empty($errors))break;											
				$classname= $_REQUEST['class'];

				$_SESSION['prof_currentclass'] = $classname;					
				$classcode= $_REQUEST['code'];
				$profname= $_SESSION['username'];	
          	    $affected = $model_database->create_class($classname, $classcode,$profname);
        		if($affected == 0){
        			echo("This class already exists. Try making another class.");
        		   }
        		else{
          	        $_SESSION['state'] = 'instructor_current_class';
           	        $view = 'instructor_currentclass.php';
           	        }
			}

			else if (isset($_POST['submit_current_class'])){

				//get the latest info for igetit and idontgetit using a query
				// then set to the global variable in model.php
				//verify that the access code and course selected are correct

				$_SESSION['prof_currentclass'] = $_POST['choices'];
				$class = $model_database->split_classname($_SESSION['prof_currentclass']);
				$model_database->prof_currentclass = $class;
				$model_database->count_votes();

                $_SESSION['state'] = 'instructor_current_class';
           	    $view = 'instructor_currentclass.php';
			}

			break;

		case "student_current_class":

		    //this view displays the student current class page
			$view = 'student_currentclass.php';
			if(isset($_REQUEST['change_to_logout']) AND $_REQUEST['change_to_logout'] == "Logout"){
				session_unset();
        	    session_destroy();
				$_SESSION['state'] = "logout";
				$view = "login.php";
				break;
			}
			if(isset($_REQUEST['change_to_profile']) AND $_REQUEST['change_to_profile'] == "Profile"){
				//populate the view file for profile.php	
				$model_database->setAccountExists(True);
				global $Account_exists;
				$model_database->setUserfields($_SESSION["username"],$_SESSION["password"],$_SESSION["first_name"],$_SESSION["last_name"],$_SESSION["email"],$_SESSION["designation"]);
				$_SESSION['state'] = "profile";
				$view = "profile.php";
				break;
			}
			$student_currentclass = $_SESSION['student_currentclass'];
			$vote = $_REQUEST['vote'];
			$model_database->update_votes($student_currentclass,$vote);
			break;

		case "student_join_class": 

		    //this view displays the student join class page
			//If student is already in the class, don't let them join again, just change view
			$view = "student_joinclass.php";

			if(isset($_REQUEST['change_to_logout']) AND $_REQUEST['change_to_logout'] == "Logout"){
				session_unset();
        	    session_destroy();
				$_SESSION['state'] = "logout";
				$view = "login.php";
				break;
			}
			if(isset($_REQUEST['change_to_profile']) AND $_REQUEST['change_to_profile'] == "Profile"){
				//populate the view file for profile.php
				$model_database->setAccountExists(True);
				global $Account_exists;
				$model_database->setUserfields($_SESSION["username"],$_SESSION["password"],$_SESSION["first_name"],$_SESSION["last_name"],$_SESSION["email"],$_SESSION["designation"]);	
				$_SESSION['state'] = "profile";
				$view = "profile.php";
				break;
			}
			
		    if(empty($_REQUEST['submit']) || $_REQUEST['submit']!= "create"){
				break;
			   }			
			if(empty($_REQUEST['code'])){
				$errors[] = "code required";
				}
			if(!isset($_POST['choices'])){
				$errors[] = "choice reqd";
				}
			if(!empty($errors))break;

			$student_username = $_SESSION['username'];
			$class_chosen = $_POST['choices']; //****ERRROR
			$class_chosen = $class_chosen;
            $accessCode = $_REQUEST['code']; 
            $class = $model_database->split_classname($class_chosen);
            //If student is already in the class, don't let them join again, just change view
            //Check if access code matches regardless
            $accesscode_exists = $model_database->check_accesscode($class,$accessCode);
            echo ($accesscode_exists);
            if($accesscode_exists){
            	if ($model_database->student_in_class($student_username, $class) == 0){ //student not already in class
                     echo("student not in class");
           			 $enrolled = $model_database->enroll_student($class, $accessCode, $student_username);
            		}
            	$_SESSION['student_currentclass'] = $class;
                $_SESSION['state'] = 'student_current_class';
            	$view = 'student_currentclass.php';
            	}
            
            else{
            	echo ("Access code is incorrect. Sorry, try again.");
                }
 	    break;
    
        }
	require_once "view/view_lib.php"; //view for errors
	require_once "view/$view"; //everything else

?>
