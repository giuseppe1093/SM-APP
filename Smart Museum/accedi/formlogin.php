<?php

	//imposta sessione a 0, ovvero nessun utente connesso e reindirizza nella pagina di login 
	session_start();	//inizio sessione
	
	if (isset($_SESSION['login'])) {    //serve per verificare che la variabile esiste. Se esiste restituisce valore true
		if ($_SESSION['login'] == "connesso") {  //se esiste, verifica la condizione. Se è 
			if ($_SESSION['privilegio'] == "amministratore") {
				header("Location: operazioniadmin.php");   //reindirizzamento a causa di login già effettuato
			} else {
				header("Location: operazionipersonale.php");
			}
		} else {
			$_SESSION['login'] = "sconnesso";
		}	//imposta sessione come sconnessa
	}

?>

<!doctype html>
<html>
    <head> 
        <meta charset="UTF-8"> 
        <title>Menù Personale</title> 
    </head>
    <body>
        <div><br>
			<form action="formpostlogin.php" method="POST">
				<?php
					echo '<link rel="stylesheet" content-type="text/css" href="css-accedi/formlogin.css">';
					if (isset($_SESSION['errore'])) {
						echo '<label>',$_SESSION['errore'],'</label>';
						$_SESSION['errore'] = "ACCEDI";
					} else {
						$_SESSION['errore'] = "ACCEDI";
						echo '<label>',$_SESSION['errore'],'</label>';
					}
				?>
				<br><br>
				<label>Username</label> <input type="text" name="username" value=""><br><br>
				<label>Password</label> <input type="password" name="password" value=""><br><br>
				<input type="submit" name="login" value="ENTRA">
			</form>
		</div>
    </body>
</html>
