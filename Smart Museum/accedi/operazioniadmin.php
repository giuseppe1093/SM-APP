<?php
	
	echo '<body>';
	//PAGINA OPERAZIONI ADMIN
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione
	
	session_start();

	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso") {
			header("Location: formlogin.php");
			$_SESSION['login'] = "sconnesso";
		}
	}
	echo '</body>';
?> 

<!doctype html>
<html>
    <head> 
        <meta charset="utf-8"> 
        <title>Menù Personale</title> 
    </head>
    <body>
        <div>
			<label>BENVENUTO</label>
			<br>
			<?php 
				echo '<link rel="stylesheet" content-type="text/css" href="css-accedi/operazioni.css">';
				echo '<label>',$_SESSION['nome'],'</label>'; 
			?> 
			<br><br>
			<a href="admin/nuovomuseo.php">Inserisci Nuovo Museo</a><br><br>
			<a href="admin/modificadirettoremuseo.php">Modifica Direttore Museo</a><br><br>
			<a href="admin/visualizzapersonalemuseo.php">Visualizza Personale Museo</a><br><br>
			<a href="admin/cancellamuseo.php">Rimuovi Museo</a><br><br>
			<a href="password/modificapassword.php">Modifica Password</a><br><br>
				
			<form action="logout.php">
				<input class=pulsante type="submit" name="logout" value="LOGOUT">
			</form>
		</div>
    </body>
</html>