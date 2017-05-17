<?php
require_once "model/model.php";
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="refresh" content="300">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style>
			span {
				background-color:green; 
				display:block; 
				text-decoration:none; 
				padding:20px; 
				font-color:black; 
				text-align:center;
			}
		</style>
		<title>iGetIt</title>
	</head>
	<body>
		<header><h1>iGetIt (instructor)</h1></header>
		<nav>
  			<form>
			<ul>
                        <li> <a href="">Class</a>
                        <li> <input type="submit" name = "change_to_profile" value = "Profile"/>
                        <li> <input type="submit" name = "change_to_logout" value = "Logout"/>
                        </ul>
		  </form>
		</nav>
		<main>
			<h1>Class</h1>
			<form>
				<fieldset>
					<?php
					//global $Currentprof;
					global $class_code;
					echo "<legend>".$Currentprof."</legend>";
					echo "<legend>".$Currentclass."</legend>";
					global $igi; //iGetIt
					$igi = intval($iGetit);
					global $idgi; //iDontGetIt
					$idgi = intval($idontGetit);
					echo("igi = $igi and idgi = $idgi");
					if ($igi == 0 && $idgi == 0){ //no one is in the class
						echo "There is no one enrolled in this class. Hence, no data is available yet.";
					}
					else{
						$igetit_width = ($igi/($igi +$idgi))*(100);
						$idontgetit_width = ($idgi/($igi +$idgi))*(100);
						echo "<span style=\"background-color:green; width:$igetit_width;\">IGetIt</span>";
						echo "<span style=\"background-color:red; width:$idontgetit_width;\">IDontGetIt</span>";
				}
                    
     				?>							
				</fieldset>
			</form>
		</main>
		<footer>
		</footer>
	</body>
</html>

