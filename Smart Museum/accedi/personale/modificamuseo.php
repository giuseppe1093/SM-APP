<?php
	//MODIFICA STRUTTURA MUSEALE CON FORM
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
        <title>Modifica Museo</title> 
    </head>
    <body>
		<h1>MODIFICA STRUTTURA MUSEALE</h1><br>
		<form action="confermamodificamuseo.php" method="post" enctype="multipart/form-data">
		
			<?php
			
			include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione
			$idmus=$_SESSION['idmuseo'];					//id del museo d'appartenenza
			
			if (!$result = $connessione->query("SELECT * FROM museo WHERE idMuseo='" . $idmus . "'")) { // query selezione musei
				echo "Errore della query: " . $connessione->error . ".";  //controllo errore
			} else {
				if ($result->num_rows > 0) {  // conteggio dei record
					while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
						echo '<label>Nome Museo: ',$tmp['Nome'],'</label><br><br>';
						echo '<label>Città: </label><input type="text" name="città" value="',$tmp['Città'],'"><br><br>';
						echo '<label>CAP: </label><input type="text" name="cap" value="',$tmp['CAP'],'"><br><br>';
						echo '<label>Indirizzo: </label><input type="text" name="indirizzo" value="',$tmp['Indirizzo'],'"><br><br>';
						echo '<label>Immagine Museo: </label><input type="file" name="file_inviato">File immagine corrente : ',$tmp['Immagine'],'<br><br>';
						echo '<label>Orario Museo: </label>',$tmp['Orario'],'<br><br>';
						echo '<label>Orario Apertura Museo: </label><input type="time" name="orarioa""><br><br>';
						echo '<label>Orario Chiusura Museo: </label><input type="time" name="orarioc""><br><br>';
						echo '<label>Telefono: </label><input type="tel" name="tel" value="',$tmp['Telefono'],'"><br><br>';
						echo '<label>Fax: </label><input type="tel" name="fax" value="',$tmp['Fax'],'"><br><br>';
						echo '<label>E-mail: </label><input type="text" name="email" value="',$tmp['Indmail'],'"><br><br>';
						echo '<label>Descrizione: </label><textarea name="descrizione">',$tmp['Descrizione'],'</textarea><br><br>';
						
						$_SESSION['città']=$tmp['Città']; 
						$_SESSION['cap']=$tmp['CAP']; 
						$_SESSION['indirizzo']=$tmp['Indirizzo']; 
						$_SESSION['immagine']=$tmp['Immagine'];
						$_SESSION['orario']=$tmp['Orario']; 
						$_SESSION['telefono']=$tmp['Telefono']; 
						$_SESSION['fax']=$tmp['Fax']; 
						$_SESSION['indmail']=$tmp['Indmail'];
						$_SESSION['descrizione']=$tmp['Descrizione']; 
						$_SESSION['museonome']=$tmp['Nome']; 
					}
				}
			}
			
			?>
			
			<div>
				<input type="submit" name="submit" value="Invia">
				<input type="reset" value="Reset">
				<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Indietro</a>
			</div>
		</form>
    </body>
</html>