<?php
//VISUALIZZA PERSONALE PARTE 2
include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione
echo '<link rel="stylesheet" content-type="text/css" href="visualizza.css">';

session_start();
if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] != "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
}

	$idmuseo=$_POST['musei'];	//PRELEVO ID MUSEO
	
	if (!$result = $connessione->query("SELECT * FROM personale WHERE Museo_idMuseo='" . $idmuseo . "' AND Direttore ='1'")) { // query selezione personale
		echo "Errore della query: " . $connessione->error . ".";  //controllo errore
	} else {
		if ($result->num_rows > 0) {  // conteggio dei record
			while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
				echo '<p>Direttore del Museo</p>';
				echo '<div>
						<label>Matricola: </label><label id="value"> ',$tmp['Matricola'],'</label><br><br>
						<label>Nome: </label><label id="value"> ',$tmp['Nome'],'</label><br><br>
						<label>Cognome: </label><label id="value"> ',$tmp['Cognome'],'</label><br><br>
						<label>Password: </label><label id="value"> ',$tmp['Password'],'</label><br><br>
						<label>Telefono: </label><label id="value"> ',$tmp['Telefono'],'</label><br><br>
						<label>email: </label><label id="value"> ',$tmp['Indmail'],'</label><br><br>
					</div>';
			}
		}
	}
	
	if (!$result = $connessione->query("SELECT * FROM personale WHERE Museo_idMuseo='" . $idmuseo . "' AND Direttore ='0'")) { // query selezione personale
		echo "Errore della query: " . $connessione->error . ".";  //controllo errore
	} else {
		if ($result->num_rows > 0) {  // conteggio dei record
			while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
				echo '<p>Dipendenti del Museo</p>';
				echo '<div>
						<label>Matricola: </label><label id="value"> ',$tmp['Matricola'],'</label><br><br>
						<label>Nome: </label><label id="value"> ',$tmp['Nome'],'</label><br><br>
						<label>Cognome: </label><label id="value"> ',$tmp['Cognome'],'</label><br><br>
						<label>Password: </label><label id="value"> ',$tmp['Password'],'</label><br><br>
						<label>Telefono: </label><label id="value"> ',$tmp['Telefono'],'</label><br><br>
						<label>email: </label><label id="value"> ',$tmp['Indmail'],'</label><br><br>
					</div>';
			}
		}
	}
	
    $result->close(); // liberazione delle risorse occupate dal risultato
	$connessione->close();  //chiusura connessione database
	echo '<a href="http://localhost/Smart Museum/accedi/operazioniadmin.php">Indietro</a>';
?>