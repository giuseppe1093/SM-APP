<?php
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione

	
	echo '<link rel="stylesheet" content-type="text/css" href="visualizza.css">';
	session_start();
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
	
	$idopera=$_POST['idbutton']; //id museo d'appartenenza
	
	if (!$result = $connessione->query("SELECT * FROM scheda WHERE CodiceReperto='" . $idopera . "'")) { // query selezione musei
		echo "Errore della query: " . $connessione->error . ".";  //controllo errore
	} else {
		if ($result->num_rows > 0) {  // conteggio dei record
			while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
				echo '<div>
						<label>Nome: </label><label id="value"> ',$tmp['Nome'],'</label><br><br>
						<label>Artista: </label><label id="value"> ',$tmp['Artista'],'</label><br><br>
					</div>';
				echo '<img src="http://localhost/Smart Museum/immagini/opere/qrcode/',$tmp['Qrcode'],'"><br><br>';	
			}
		}
	}
	
    $result->close(); // liberazione delle risorse occupate dal risultato
	$connessione->close();  //chiusura connessione database
	echo '<a class="pulsante" href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Indietro</a>';
?>