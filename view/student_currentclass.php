<?php
require_once "model/model.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style>
			td a {
				background-color:green; 
				display:block; 
				width:200px; 
				text-decoration:none; 
				padding:20px; 
				color:white; 
				text-align:center;
			}
		</style>
		<title>iGetIt</title>
	</head>
	<body>
		<header><h1>iGetIt (student)</h1></header>
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
			<form method = "POST">
				<fieldset>
					<!-- Making a new connection -->
					<legend> Please vote :) </legend>
					<table style="width:100%;">
						<tr>
							<td><input type ="submit" name="vote" value = "iGetIt" a style="background-color:green;" href="">i Get It</a></td>
							<td><input type ="submit" name = "vote" value = "iDontGetIt" a style="background-color:red;" href="">i Don't Get It</a></td>
						</tr>
					</table>
				</fieldset>
			</form>
		</main>
		<footer>
		</footer>
	</body>
</html>

