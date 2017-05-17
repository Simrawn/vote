<?php

class consts{
	public $username;
	public $password;
	public $firstname;
	public $lastname;
	public $email;
	
	public function __construct($user_name, $pass_word, $first_name, $last_name, $email1) {
		$this->username= $user_name;
		$this->password = $pass_word;
		$this->firstname = $first_name;
		$this->lastname = $last_name;
		$this->email = $email1;
		
		}
	public function check_username(){
		$strlen = strlen($this->username);
		if ($strlen < 4){
			echo ("Kindly choose a username between the length 4 and 6.");
			return False;
		}
		return True;

	}
	public function check_password(){
		//Minimum length 6
		$strlen = strlen($this->password);
		if ($strlen < 6){
			echo("Kindly choose a password that is atleast 6 characters long.");
			return False;
		}
		return True;
	}
	public function check_firstname(){
		//Minimum length of first and last name must be at least 2.
		$strlen = strlen($this->firstname);
		if($strlen < 2){
			echo("Kindly enter a name that is atleast 2 characters long.");
			return False;
		}
		return True;
	}
	public function check_lastname(){
		//Minimum length of first and last name must be at least 2.
		$strlen = strlen($this->lastname);
		if($strlen < 2){
			echo("Kindly enter a last name that is atleast 2 characters long.");
			return False;
		}
		return True;
	}

	public function test_constraints(){
		echo "Entered test_constraints";
		$username_clear = $this->check_username();
		$password_clear = $this->check_password();
		$firstname_clear = $this->check_firstname();
		$lastname_clear = $this->check_lastname();
		$answers = array();
		array_push($answers, $username_clear);
		array_push($answers, $password_clear);
		array_push($answers, $firstname_clear);
		array_push($answers, $lastname_clear);
		return $answers;



	}
}

?>
