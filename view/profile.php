<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
$_REQUEST['firstName']=!empty($_REQUEST['firstName']) ? $_REQUEST['firstName'] : '';
$_REQUEST['lastName']=!empty($_REQUEST['lastName']) ? $_REQUEST['lastName'] : '';
$_REQUEST['email']=!empty($_REQUEST['email']) ? $_REQUEST['email'] : '';
require_once "model/model.php";

?>



<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>iGetIt</title>
	</head>
	<body>
		<header><h1>iGetIt</h1></header>
		<nav>
			<form>
			<ul>
                        <li><a href="">Class</a>
                        <li> <input type="submit" name = "change_to_profile" value = "profile">
                        <li> <input type="submit" name = "change_to_logout" value = "Logout">
                        </ul>
            </form>

		</nav>
		<main>
			<h1>Profile</h1>
			<form>
				<fieldset>
					<legend>Edit Profile</legend>
					<?php
						global $Account_exists;
						global $username;
                   	    global $pass_word;
                   	    global $firstname;
                    	global $lastname;
                    	global $email_addr;
                    	global $designation;
						if (isset($Account_exists) AND $Account_exists == True){

							echo "<p> <label for=\"user\">User</label><input type=\"text\" name=\"user\" value =".$username." readonly></input> </p>";
							echo "<p> <label for=\"password\">Password</label><input type=\"password\" name=\"password\" value=".$pass_word."></input> </p>";
							echo "<p> <label for=\"firstName\">First Name</label><input type=\"text\" name=\"firstName\" value=".$firstname."></input> </p>";
							echo "<p> <label for=\"lastName\">Last Name</label><input type=\"text\" name=\"lastName\" value=".$lastname."></input> </p>";
							echo "<p> <label for=\"email\">email</label><input type=\"email\" name=\"email\" value=".$email_addr."></input> </p>";
							echo "<p> <label for=\"type\">type</label>";
					
                   		 	if ($designation == "student"){
								echo "<input type=\"radio\" name=\"type\" value=\"instructor\" disabled>instructor</input>";
								echo "<input type=\"radio\" name=\"type\" value=\"student\" checked>student</input> </p>";
							}
					    else{
                          	    echo "<input type=\"radio\" name=\"type\" value=\"instructor\" checked>instructor</input>";
						        echo "<input type=\"radio\" name=\"type\" value=\"student\" disabled>student</input> </p> ";
				       		}
					
						}
					
						else{
							echo "<p> <label for=\"user\">User</label><input type=\"text\" name=\"user\"></input> </p>";
							echo "<p> <label for=\"password\">Password</label><input type=\"password\" name=\"password\"></input> </p>";
							echo "<p> <label for=\"firstName\">First Name</label><input type=\"text\" name=\"firstName\"></input> </p>";
							echo "<p> <label for=\"lastName\">Last Name</label><input type=\"text\" name=\"lastName\"></input> </p>";
							echo "<p> <label for=\"email\">email</label><input type=\"email\" name=\"email\"></input> </p>";
							echo "<p> <label for=\"type\">type</label>";
							echo "<input type=\"radio\" name=\"type\" value=\"instructor\">instructor</input>";
							echo "<input type=\"radio\" name=\"type\" value=\"student\">student</input></p>";
			     		}
				    
				    ?>
				    
					<p> <input type="submit" name = "submit" value="makeprofile" />
				</fieldset>
			</form>
		</main>
		<footer>
		</footer>
	</body>
</html>

