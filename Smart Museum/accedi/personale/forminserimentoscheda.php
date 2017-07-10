<?php

	echo '<link rel="stylesheet" type="text/css" href="inserimento.css">';
session_start();
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
?> 
 
<html> 
<head> 
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
	<h1>NUOVA OPERA</h1><br><br>
	<form method="post" action="confermaopera.php" enctype="multipart/form-data">
		<label id="dir">Scheda Opera</label><br><br>
        <label>Nome : </label><input type="text" name="nomeopera"><br><br>
		<label>Artista : </label><input type="text" name="artistaopera"><br><br>
		<label>Descrizione : </label><textarea maxlength="3000" name="descrizioneopera"></textarea><br><br>
		<label>Immagine Opera :  </label><input type="file" name="file_inviato"><br><br>
		<label>Audio Opera :  </label><input type="file" name="audio_inviato"><br><br>
		
		<input type="submit" name="submit" value="Invia">
		<input type="reset" value="Reset">
		<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Indietro</a>
    </form>
</body> 
</html>