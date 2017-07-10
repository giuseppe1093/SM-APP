<?php

echo '<link rel="stylesheet" content-type="text/css" href="rimuovi.css">';
//CONFERMA CANCELLAZIONE STRUTTURA MUSEALE

include "C:/Apache24/htdocs/Smart Museum/connessione.php";           // inclusione del file di connessione;connessione al database


session_start();
if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
}

$opera = $_POST['opera'];

$idopera = explode(' ', $opera);		//Questa funzione serve per suddividere una stringa ($museo) in sottostringhe. Viene creata una sottostringha qualora venga trovato, nel nostro caso, il carattere spazio e ognuna di esse memorizzate in un array ($idmuseo). 
$idopera = $idopera[0];					//A noi serve solamente la prima sottostringha che contiene l'id del museo.

//rimozione tuple opere
if (!$result = $connessione->query("SELECT * FROM scheda WHERE CodiceReperto ='" . $idopera . "'")) { // query selezione opere
    echo "Errore della query: " . $connessione->error . ".";  //controllo errore
} else {
    if ($result->num_rows != 0) {  // conteggio dei record 
        while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
            //rimozione file dal server
             $immagine = "C:/Apache24/htdocs/Smart Museum/immagini/opere/immagine/" . $tmp['Immagine'];	//assegnazione ad una variabile del percorso dell'immagine
			 $audio = "C:/Apache24/htdocs/Smart Museum/immagini/opere/audio/" . $tmp['Audio'];	//assegnazione ad una variabile del percorso dell'audio
			 $qr = "C:/Apache24/htdocs/Smart Museum/immagini/opere/qrcode/" . $tmp['Qrcode'];	//assegnazione ad una variabile del percorso del qr
            
			if($tmp['Immagine']!="?") {     
				if (unlink($immagine)) {
					echo '<label>il file immagine è stato cancellato</label><br><br>';
				} else {
					echo '<label>il file immagine NON è stato cancellato</label><br><br>';
				}
			}
			
			if($tmp['Audio']!="?"){
				if (unlink($audio)) {
					echo '<label>il file audio è stato cancellato</label><br><br>';
				} else {
					echo '<label>il file audio NON è stato cancellato</label><br><br>';
				}
			}
			
			if($tmp['Qrcode']!="?"){
				if (unlink($qr)) {
					echo '<label>il file qr è stato cancellato</label><br><br>';
				} else {
					echo '<label>il file qr NON è stato cancellato</label><br><br>';
				}
			}
        }
		
		//prelevo nome del museo da cancellare
		if (!$result = $connessione->query("SELECT * FROM scheda WHERE CodiceReperto ='" . $idopera . "'")) { // query selezione museo per memorizzare il nome del immagine da eliminare dal server
			echo "Errore della query: " . $connessione->error . ".";  //controllo errore
		} else {
			while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) {  //associazioni della tabella risultante all'array tmp
				$nomeopera=$tmp['Nome'];
			}
		}
		
		if (!$result = $connessione->query("DELETE FROM scheda WHERE CodiceReperto ='" . $idopera . "'")) {
                echo "Errore della query: " . $connessione->error . ".";  //controllo errore
        }else{
			echo "<label>Opera ".$nomeopera." Cancellato!</label><br><br>";
			echo '<a class="pulsante" href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Continua</a>';
		}
    }
}

$connessione->close();

?>