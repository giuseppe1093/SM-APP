<?php // pt.1

	echo '<link rel="stylesheet" type="text/css" href="inserimento.css">';
	session_start();
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}	

?>

<!doctype html> 
<html lang="it">
    <head>
        <meta charset="utf-8"> 
        <title>Inserisci operatore</title> 
    </head>
    <body>
		<h1>INSERISCI OPERATORE</h1><br>
		<form action="confermaoperatore.php" method="post" enctype="multipart/form-data">
		
			<!-- INFORMAZIONI DIRETTORE -->
		   <br>
           <label id="dir">(*)Scheda OPERATORE</label>
		   <br><br>
		   <label>Nome Operatore: </label> <input type="text" name="nomeo"><br><br>
		   <label>Cognome Operatore: </label> <input type="text" name="cognomeo"><br><br>
		   <label>Tel. Operatore: </label> <input type="text" name="telo"><br><br>
		   <label>email Operatore: </label> <input type="email" name="emailo"><br><br>
                                                           
			<!-- FINE DIRETTORE-->
			
			<div>
				<input type="submit" name="submit" value="Invia">
				<input type="reset" value="Reset">
				<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Indietro</a>
			</div>
		</form>
    </body>
</html>