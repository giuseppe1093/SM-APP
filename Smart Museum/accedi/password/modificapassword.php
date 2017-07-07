<?php

	echo '<link rel="stylesheet" content-type="text/css" href="modificapassword.css">';
	
	//MODIFICA PASSWORD

	session_start();
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso") {//verifica se è stato fatto login
			$_SESSION['login'] = "sconnesso";
			header("Location: C:/Apache24/htdocs/Smart Museum/accedi/formlogin.php");
		}
	}
	
?>

<!doctype html>
<html lang="it">
    <head> 
        <meta charset="utf-8"> 
        <title>Modifica PASSWORD</title> 
    </head>
    <body>
        <h1>MODIFICA PASSWORD</h1>
        <form action="confermamodificapassword.php" method="post">
            <?php
			
				$us=$_SESSION['nome']; //memorizzo username in una variabile
			
				include "C:/Apache24/htdocs/Smart Museum/connessione.php";
			
				echo "<label>Username: ".$us."</label><br><br>";
				echo "<label>Vecchia PASSWORD: </label><input type='password' name='passwordv'><br>";
				echo "<label>Nuova PASSWORD: </label><input type='password' name='passwordn'><br>";
				echo "<label>Conferma PASSWORD: </label><input type='password' name='passwordc'><br>";
            
            ?>
			<br>
			<input type="submit">
			<?php 
			if($_SESSION['privilegio'] == "amministratore"){
				echo '<a class=pulsante href="http://localhost/Smart Museum/accedi/operazioniadmin.php">Indietro</a>';
			}else{
				echo '<a class=pulsante href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Indietro</a>';
			}
			
			?>
		</form>
    </body>
</html>