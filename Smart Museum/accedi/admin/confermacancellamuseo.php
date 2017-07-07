<?php //pt.2

echo '<link rel="stylesheet" content-type="text/css" href="cancellamuseo.css">';
//CONFERMA CANCELLAZIONE STRUTTURA MUSEALE

include "C:/Apache24/htdocs/Smart Museum/connessione.php";           // inclusione del file di connessione;connessione al database


session_start();
if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] != "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
}

$idmuseo=$_POST['musei'];	//PRELEVO ID MUSEO

$idmuseo = explode(' ', $idmuseo);		//Questa funzione serve per suddividere una stringa ($museo) in sottostringhe. Viene creata una sottostringha qualora venga trovato, nel nostro caso, il carattere spazio e ognuna di esse memorizzate in un array ($idmuseo). 
$idmuseo = $idmuseo[0];					//A noi serve solamente la prima sottostringha che contiene l'id del museo.

//rimozione tuple personale
if (!$result = $connessione->query("DELETE FROM personale WHERE Museo_idMuseo ='" . $idmuseo . "'")) { // query rimozione personale
    echo "Errore della query: " . $connessione->error . ".";  //controllo errore
    exit();
}

//rimozione tuple opere
if (!$result = $connessione->query("SELECT * FROM scheda WHERE Museo_idMuseo ='" . $idmuseo . "'")) { // query selezione opere
    echo "Errore della query: " . $connessione->error . ".";  //controllo errore
    exit();
} else {
    if ($result->num_rows != 0) {  // conteggio dei record 
        while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) {  //associazioni della tabella risultante all'array tmp
            //rimozione file dal server
             $immagine = "C:/Apache24/htdocs/Smart Museum/immagini/opere/immagine/" . $tmp['Immagine'];	//assegnazione ad una variabile del percorso dell'immagine
			 $audio = "C:/Apache24/htdocs/Smart Museum/immagini/opere/audio/" . $tmp['Audio'];	//assegnazione ad una variabile del percorso dell'immagine
             $qr = "C:/Apache24/htdocs/Smart Museum/immagini/opere/qrcode/" . $tmp['Qrcode'];	//assegnazione ad una variabile del percorso del qr
			 
			if($tmp['Immagine']!="?") {
				unlink($immagine);
			}
			if($tmp['Audio']!="?"){
				unlink($audio);
			}
			if($tmp['Qrcode']!="?"){
				unlink($qr);
			}
	//rimozione tuple opere da database
		if (!$result = $connessione->query("DELETE FROM scheda WHERE Museo_idMuseo ='" . $idmuseo . "'")) {
            echo "Errore della query: " . $connessione->error . ".";  //controllo errore
            exit();
        }
    }
}
}

//RIMOZIONE TUPLA MUSEO DA DATABASE E IMMAGINE DA SERVER
if (!$result = $connessione->query("SELECT * FROM museo WHERE idMuseo ='" . $idmuseo . "'")) { // query selezione museo per memorizzare il nome del immagine da eliminare dal server
    echo "Errore della query: " . $connessione->error . ".";  //controllo errore
    exit();
} else {
    if ($result->num_rows > 0) {  // conteggio dei record 
        while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) {  //associazioni della tabella risultante all'array tmp
			$immagine = "C:/Apache24/htdocs/Smart Museum/immagini/musei/" . $tmp['Immagine'];  //rimozione immagine museo dal server
			if($tmp['Immagine']!="?"){
				unlink($immagine);
			}	
		}
	}
}

//prelevo nome del museo da cancellare
if (!$result = $connessione->query("SELECT * FROM museo WHERE idMuseo ='" . $idmuseo . "'")) { // query selezione museo per memorizzare il nome del immagine da eliminare dal server
    echo "Errore della query: " . $connessione->error . ".";  //controllo errore
    exit();
} else {
	while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) {  //associazioni della tabella risultante all'array tmp
		$nomemuseo=$tmp['Nome'];
	}
}

//rimozione tupla museo
if (!$result = $connessione->query("DELETE FROM museo WHERE idMuseo ='" . $idmuseo . "'")) { // query rimozione museo
    echo "Errore della query: " . $connessione->error . ".";  //controllo errore
    exit();
}else{
echo "<label>Museo ".$nomemuseo." Cancellato!</label><br><br>";
	echo '<a class="pulsante" href="http://localhost/Smart Museum/accedi/operazioniadmin.php">Continua</a>';
}

$connessione->close();

?>