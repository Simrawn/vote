<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
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
		<!--
		<nav>
			<ul>
			<li> <a href="">Class</a>
			<li> <a href="">Profile</a>
			<li> <a href="">Logout</a>
			</ul>
		</nav>
		-->
		<main>
			<h1>Login</h1>

			<form>
				<fieldset>
					<legend>Login</legend>
					<p> <label for="user">User</label>    <input type="text" name="user"></input> </p>
					<p> <label for="password">Password</label><input type="password" name="password"></input> </p>
					<p> <input type="submit" name="submit" value="login" />
					<p> <?php echo(view_errors($errors));?>

				</fieldset>
			</form>
		</main>
		<footer>
		</footer>
	</body>
</html>

