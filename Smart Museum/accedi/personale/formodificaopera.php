<?php
	//MODIFICA OPERA 2
	echo '<link rel="stylesheet" type="text/css" href="modifica.css">';
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
        <title>Modifica Opera</title> 
    </head>
    <body>
        <div>
		</div>
		<h1>MODIFICA OPERA</h1><br>
		<form action="confermamodificaopera.php" method="post" enctype="multipart/form-data">
		
			<?php
			
			include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione
			$idmus=$_POST['opera'];					//id del museo d'appartenenza
			
			if (!$result = $connessione->query("SELECT * FROM scheda WHERE CodiceReperto='" . $idmus . "'")) { // query selezione musei
				echo "Errore della query: " . $connessione->error . ".";  //controllo errore
			} else {
				if ($result->num_rows > 0) {  // conteggio dei record
					while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
						echo '<label>Nome: </label><input type="text" name="nomeo" value="',$tmp['Nome'],'"><br>';
						echo '<label>Artista: </label><input type="text" name="artista" value="',$tmp['Artista'],'"><br>';
						echo '<label>Immagine: </label><input type="file" name="file_inviato"> File immagine corrente : ',$tmp['Immagine'],'<br>';
						echo '<label>Audio: </label><input type="file" name="audio_inviato"> File audio corrente : ',$tmp['Audio'],'<br>';
						echo '<label>Descrizione: </label><textarea name="descrizione">',$tmp['Descrizione'],'</textarea><br><br>';
						
						$_SESSION['codopera']=$tmp['CodiceReperto']; //codice opera
						$_SESSION['nomeopera']=$tmp['Nome']; //nome opera
						$_SESSION['artistaopera']=$tmp['Artista']; //artista opera
						$_SESSION['descrizioneopera']=$tmp['Descrizione']; //descrizione opera
						$_SESSION['immagineopera']=$tmp['Immagine']; //immagine opera
						$_SESSION['audiopera']=$tmp['Audio']; //audio opera
					}
				}
			}
			
			?>
			
			<div>
				<input type="submit" name="submit" value="Invia">
				<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Indietro</a>
			</div>
		</form>
    </body>
</html>