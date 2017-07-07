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

$operatore = $_POST['operatore'];

$idoperatore = explode(' ', $operatore);		//Questa funzione serve per suddividere una stringa ($museo) in sottostringhe. Viene creata una sottostringha qualora venga trovato, nel nostro caso, il carattere spazio e ognuna di esse memorizzate in un array ($idmuseo). 
$idoperatore = $idoperatore[0];					//A noi serve solamente la prima sottostringha che contiene l'id del museo.

//rimozione tupla operatore

//prelevo nome del museo da cancellare
$idoperatore=addslashes($idoperatore);
		if (!$result = $connessione->query("SELECT * FROM personale WHERE Matricola ='" . $idoperatore . "'")) { // query selezione museo per memorizzare il nome del immagine da eliminare dal server
			echo "Errore della query: " . $connessione->error . ".";  //controllo errore
		} else {
			while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) {  //associazioni della tabella risultante all'array tmp
				$nomeoperatore=$tmp['Nome'];
				$cognomeoperatore=$tmp['Cognome'];
			}
		}
		
		if (!$result = $connessione->query("DELETE FROM personale WHERE Matricola ='" . $idoperatore . "'")) {
                echo "Errore della query: " . $connessione->error . ".";  //controllo errore
        }else{
			echo "<label>L'operatore ".$nomeoperatore." ".$cognomeoperatore." è stato rimosso!</label><br><br>";
			echo '<a class="pulsante" href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Continua</a>';
		}

$connessione->close();

?>