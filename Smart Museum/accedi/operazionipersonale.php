<?php
	//PAGINA OPERAZIONI ADMIN
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione

	// link al foglio di stile	
	echo "<body>";

	session_start();

	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso") {
			header("Location: formlogin.php");
			$_SESSION['login'] = "sconnesso";
		}
	}
	
	echo "</body>";
?> 

<!doctype html>
<html>
    <head> 
        <meta charset="UTF-8"> 
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
			<?php
				if ($_SESSION['privilegio'] == "direttore") {   //operazioni fatte solo dal direttore
					echo '
						<a href="personale/modificamuseo.php">Modifica Informazioni Museo</a><br><br>
						<a href="personale/inseriscioperatore.php">Inserisci Operatore</a><br><br>
						<a href="personale/visualizzaoperatori.php">Visualizza Dipendenti Museo</a><br><br>
						<a href="personale/modificaoperatore.php">Modifica Informazioni Operatore</a><br><br>
						<a href="personale/cancellaoperatore.php">Rimuovi Operatore</a><br><br>';
				}
			?>
			
            <a href="personale/forminserimentoscheda.php">Inserisci Opera</a><br><br>           
			<a href="personale/visualizzaopere.php">Visualizza Opere Museo</a><br><br>
			<a href="personale/visualizzaopereqr.php">Stampa QR CODE Opere</a><br><br>
			<a href="personale/modificaopera.php">Modifica Opera</a><br><br>
			<a href="personale/cancellaopera.php">Rimuovi Opera</a><br><br>
			<a href="password/modificapassword.php">Modifica Password</a><br><br>
			<form action="logout.php">
				<input type="submit" name="logout" value="LOGOUT">
			</form>
		</div>
    </body>
</html>