<?php
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
			<form method = "POST">
				<fieldset>
					<legend>Create Class</legend>
   					<p> <label for="class">class</label><input type="text" name="class" value="class"></input> </p>
   					<p> <label for="code">code</label><input type="text" name="code" value="code"></input> </p>
                    <p> <input type="submit" name="submit_new_class" value="create"/> 
				</fieldset>
			</form>
 			<form method ="POST">
                                <fieldset>
                                        <legend>Current Classes</legend>
                                        <select name = "choices">
                                        	<?php
                                            global $prof_classes;
                                            $arr = $prof_classes; //global variable
                                            if(!empty($arr)){
                                        	foreach($arr as $val){
                                        		echo "<option>" .$val. "</option>";
                                        	}
                                        }
                                        ?>    

                                        </select>
                                        <p> <label for="code">code</label><input type="text" name="code" value = "accesscode"></input> </p>
                                        <p> <input type="submit" name = "submit_current_class" value = "submit_query"/> 
                                </fieldset>
            </form>

		</main>
		<footer>
		</footer>
	</body>
</html>

