<?php
	//INSERIMENTO NUOVA STRUTTURA MUSEALE CON FORM
	echo '<link rel="stylesheet" type="text/css" href="nuovomuseo.css">';
	session_start();
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] != "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
?>

<!doctype html> 
<html lang="it">
    <head>
        <meta charset="utf-8"> 
        <title>Nuovo Museo</title> 
    </head>
    <body>
        <div>
			<?php
				if (isset($_SESSION['formerror'])) {
					echo '<label>',$_SESSION['formerror'],'</label>';	// messaggio di errore se non sono stati compilati i campi obbligatori
					$_SESSION['formerror'] = null;
				} else {
					$_SESSION['formerror'] = null;
				}
			?>
		</div>
		<h1>NUOVA STRUTTURA MUSEALE</h1><br>
		<form action="confermanuovomuseo.php" method="post" enctype="multipart/form-data">
		
			<!-- INFORMAZIONI MUSEO -->
			<label>*inserire obbligatoriamente i campi con l'asterisco mentre gli altri campi possono essere compilati dal direttore al primo accesso</label>
			<br><br>
			<label>(*)Nome Museo:</label> <input type="text" name="nomemuseo"><br><br>
			<label>Città:</label> <input type="text" name="città"><br><br>
			<label>CAP:</label> <input type="text" name="cap"><br><br>
			<label>Indirizzo:</label> <input type="text" name="indirizzo"><br><br>
			<label>Immagine Museo:</label> <input type="file" name="file_inviato"><br><br>
			<label>Orario Apertura Museo:</label> <input type="time" name="orarioa"><br><br>
			<label>Orario Chiusura Museo:</label> <input type="time" name="orarioc"><br><br>
			<label>Tel.:</label> <input type="tel" name="tel"><br><br>
			<label>FAX:</label> <input type="tel" name="fax"><br><br>
			<label>email:</label> <input type="email" name="email"><br><br>
			<label>Descrizione</label> <textarea name="descrizione"></textarea><br><br>
            
			<!-- INFORMAZIONI DIRETTORE -->
			<br><br>
           <label id="dir">(*)Scheda Direttore</label>
		   <br><br>
		   <label>Nome Direttore:</label> <input type="text" name="nomed"><br><br>
		   <label>Cognome Direttore:</label> <input type="text" name="cognomed"><br><br>
		   <label>Tel. Direttore:</label> <input type="tel" name="teld"><br><br>
		   <label>email Direttore:</label> <input type="email" name="emaild"><br><br>
                                                           
			<!-- FINE DIRETTORE-->
			
			<div>
				<input type="submit" name="submit" value="Invia">
				<input type="reset" value="Reset">
				<a href="http://localhost/Smart Museum/accedi/operazioniadmin.php">Indietro</a>
			</div>
		</form>
    </body>
</html>