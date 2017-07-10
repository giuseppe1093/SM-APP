<?php
	echo '<link rel="stylesheet" content-type="text/css" href="home.css">';
	echo '<body>';
	include 'connessione.php';	// inclusione del file di connessione al database

	if (!$result = $connessione->query("SELECT * FROM museo")) { // query selezione musei
		echo "Errore della query: " . $connessione->error . ".";  //controllo errore
	} else {
		echo '<form action="homemuseo.php" method="post"> <!-- spiegazione sotto; permette di collegarsi ad un museo mostrando la rispettiva home -->';
					if ($result->num_rows > 0) {  // conteggio dei record
						echo '<div id="presentazione">
									Smart Museum, la rivoluzione nella visita dei musei. Da oggi potrai sapere tutto sulle opere presenti nel museo visitato tramite 
									il tuo smartphone. Prova la nostra nuova app!!!!
							</div><hr>';
						while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
							echo '<h1><button name="idbutton" class="pulsante" value="',$tmp['idMuseo'],'">',$tmp['Nome'],'</h1>
									<img src="immagini/musei/',$tmp['Immagine'],'"><br><br>
									<div class="des">',$tmp['Descrizione'],'</div>
									<hr>
								</div><br>';
						} /* visualizza nome e immagine di tutti i musei presenti nel database; i nomi dei musei vengono inseriti in pulsanti e 
							attraverso l'azionamento del pulsante avviene la visualizzazione dei contenuti del museo selezionato collegandosi 
							alla pagina homemuseo.php (citato sopra) */
					} else {
						echo '<label>Al momento non è presente alcuna struttura museale, torna a trovarci presto.</label><br><br>';
						echo '<label>Ti aspettiamo!!</label>';
					}
		echo '</form>';
	}
	
    $result->close(); // liberazione delle risorse occupate dal risultato
	
	echo '</body>';
	
	$connessione->close();  //chiusura connessione database
	
?>