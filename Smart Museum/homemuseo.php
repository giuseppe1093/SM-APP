<?php

	$id = $_POST['idbutton'];	//preleva la scelta effettuata tramite pulsante dalla pagina home.php

	echo '<link rel="stylesheet" content-type="text/css" href="css/homemuseo.css">';
	echo "<body>";
	include "connessione.php";      // inclusione del file di connessione;connessione al database
	
	if (!$result = $connessione->query("SELECT * FROM museo WHERE idMuseo='" . $id . "'")) {	//query selezione del museo scelto attravero la clausola where e paragone del id
		echo "Errore della query: " . $connessione->error . ".";  //controllo errore
	} else {
		if ($result->num_rows > 0) {  // conteggio dei record
			while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { //associazioni della tabella risultante all'array tmp
				echo '
					<div>
						<h1>',$tmp['Nome'],'</h1>
						
						<img src="immagini/musei/',$tmp['Immagine'],' "><br>
						
						<p>Descrizione</p><div id="des">',$tmp['Descrizione'],'</div><br>
						
						<p>Come raggiungerci</p>
							<div>
								<label>Indirizzo: </label><label id="value">',$tmp['Indirizzo'],'</label><br><br>
								<label>Città: </label><label id="value">',$tmp['Città'],'</label><br><br>
								<label>Cap: </label><label id="value">',$tmp['CAP'],'</label><br><br>
							</div>
							
						<p>Orari e Conttatti</p>
							<div>
								<label>Telefono: </label><label id="value">',$tmp['Telefono'],'</label><br><br>
								<label>Orario: </label><label id="value">',$tmp['Orario'],'</label><br><br>
							</div>';
							
						if ($tmp['Fax'] != null) {
							echo '<p>Fax: </p><label id="value">',$tmp['Fax'],'</label><br><br>';
						}
						echo '<p>e-mail: </p><label id="value">',$tmp['Indmail'],'</label>
					</div>';
			}
		}
	$result->close(); // liberazione delle risorse occupate dal risultato
	}	

	echo "</body>";

	$connessione->close();  //chiusura connessione database
?>