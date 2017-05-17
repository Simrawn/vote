<?php
require_once "model/model.php";
$_POST['submit']=!empty($_POST['submit']) ? $_REQUEST['submit'] : '';
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>iGetIt</title>
	</head>
	<body>
		<header><h1>iGetIt (student)</h1></header>
		<nav>
		<form>
			<ul>
                        <li> <input type="submit" name = "change_to_class" value = "Class"/>
                        <li> <input type="submit" name = "change_to_profile" value = "Profile"/>
                        <li> <input type="submit" name = "change_to_logout" value = "Logout"/>
                        </ul>
                </form>
		</nav>
		<main>
			<h1>Class</h1>
			<form method ="post">
				<fieldset>
					<legend>Current Classes</legend>
					<select name="choices">
						<?php
						$model_database = new model();
                        $arr = $model_database->GetProf_andCourse();          
                        foreach($arr as $val)
                        {
                        	echo "<option>".$val."</option>"; //OG                                        	 
                        }
                        ?>
						
					</select>
   					<p> <label for="code">code</label><input type="text" name="code"></input> </p>
                                        <p> <input type="submit" name="submit" value="create" />
				</fieldset>
			</form>
		</main>
		<footer>
		</footer>
	</body>
</html>



