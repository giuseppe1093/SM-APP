<?php

echo '<link rel="stylesheet" content-type="text/css" href="nuovomuseo.css">';
	//CONFERMA NUOVA STRUTTURA MUSEALE
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione

	session_start();
	
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] != "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
	
	$controllo = false;
	$controllodirettore = false;
	
	//PRELEVAMENTO DATI DAL FORM
	//museo
	$nomemuseo = addslashes($_POST['nomemuseo']);
	$città = addslashes($_POST['città']);
	$cap = addslashes($_POST['cap']);
	$indirizzo = addslashes($_POST['indirizzo']);
	$orarioa = $_POST['orarioa'];
	$orarioc = $_POST['orarioc'];
	$orario = $orarioa . " / " . $orarioc;
	$telefono = addslashes($_POST['tel']);
	$fax = addslashes($_POST['fax']);
	$email = addslashes($_POST['email']);
	$descrizione = addslashes($_POST['descrizione']);
	
	//direttore
	$nomed = $_POST['nomed'];
	$cognomed = $_POST['cognomed'];
	$teld = addslashes($_POST['teld']);
	$emaild = addslashes($_POST['emaild']);
	
	$cognomed = addslashes($cognomed);

	//CONTROLLO IMMAGINE
	$nomemuseoim = $_POST['nomemuseo'];
	$nome_file_vero = $_FILES["file_inviato"]["name"];   //da memorizzare
	$nomefile=$nome_file_vero;		//variabile utilizzata per rinominare il file immagine
	$flagimmagine=false;  //variabile utilizzata per controllare se è stata inserita un'immagine
    if ($nome_file_vero == null) {
        $nome_file_vero = "?";
        $tipo_file = "?";
		$flagimmagine=true;
    }else{
	//immagine 
	$nome_file_temporaneo = $_FILES["file_inviato"]["tmp_name"];
	$tipo_file = $_FILES["file_inviato"]["type"];
	
	// Leggo il contenuto del file
	$dati_file = file_get_contents($nome_file_temporaneo);
	// Preparo il contenuto del file per la query sql
	$dati_file = addslashes($dati_file);
	$nome_file_vero=$nomemuseoim.$nome_file_vero;
	$nomefile1=$nome_file_vero;
	$nome_file_vero=addslashes($nome_file_vero);
	//controllo se è stato inserito un file immagine
	$is_img = getimagesize($_FILES['file_inviato']['tmp_name']);
	if (!$is_img) {
		echo "<label>Formato non corretto!</label>";
		$controllo=true;
	}
	}
	//FINE PRELEVAMENTO
if($controllo==false){
	//controlli
if ($nomemuseo == null || $nomed == null || $cognomed == null || $teld == null || $emaild == null) {
	$_SESSION['formerror'] = "Campi obbligatori non compilati!";
	header("Location: nuovomuseo.php");
	$controllodirettore=true;
} else {
    if ($città == null) {
        $città = "?";
    }
    if ($cap == null) {
        $cap = "?";
    }
    if ($indirizzo == null) {
        $indirizzo = "?";
    }
    if ($descrizione == null) {
        $descrizione = "?";
    }
    if ($orario == null) {
        $orario = "?";
    }
    if ($telefono == null) {
        $telefono = "?";
    }
    if ($fax == null) {
        $fax = null;
    }
    if ($email == null) {
        $email = "?";
    }
	}
	
	if($controllodirettore==false){
//CONTROLLO E INSERIMENTO
	if (!$result = $connessione->query("SELECT * FROM museo WHERE Nome='" . $nomemuseo . "'")) { /*ricerca se ci sono altri musei aventi lo stesso nome*/
		echo "Errore della query: " . $connessione->error . ".";  //controllo errore
	} else {
		if ($result->num_rows != 0) { //diverso da zero vuol dire che esiste un museo con lo stesso nome  // conteggio dei record 
			$_SESSION['formerror'] = "Nome del Museo già presente!";
			header("Location: nuovomuseo.php");
		} else {
			if (!$connessione->query("INSERT INTO museo SET
				Nome='$nomemuseo',
				Città='$città',
				CAP='$cap',
				Indirizzo='$indirizzo',
				Descrizione='$descrizione',
				Orario='$orario',
				Immagine= '$nome_file_vero', 
				Telefono='$telefono',
				Fax='$fax',
				Indmail='$email'")) {
					echo "Errore della query: " . $connessione->error . ".";      //	Tre attributi per l'immagine
					$flagquery = true;   //per capire se inserire o meno l'immagine sul server. Se la query non da errori, la variabile viene settata su false
			} else {
				echo '<label>Inserimento museo effettuato correttamente.</label><br><br>';
				$flagquery = false;

				//INSERIMENTO DIRETTORE
				//Creazione matricola
					if (!$result = $connessione->query("SELECT * FROM museo WHERE Nome='" . $nomemuseo . "'")) { /*prelevamento id museo*/
						echo "Errore della query: " . $connessione->error . ".";  //controllo errore
					} else {
						while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
							$idm = $tmp['idMuseo'];
						}
					}
					//
					if (!$result = $connessione->query("SELECT * FROM personale WHERE Cognome ='" . $cognomed . "'")) { /*ricerca se ci sono altre opere aventi lo stesso nome*/
						echo "Errore della query: " . $connessione->error . ".";  //controllo errore
					} else {
						$i=0;
						if ($result->num_rows > 0) {  // conteggio dei record
							while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
								$stringa=$tmp['Cognome'];
								$lunghezza=strlen($stringa);
								$lunghezza=$lunghezza+2;
								$numero = substr($tmp['Matricola'], $lunghezza);
								if($i<=$numero){
									$i=$numero;
								}
							}
							$i++;
						}
						if($i==0){
							$i++;
						}
						$letteranome = substr($nomed, 0, 1);  //preleva la prima lettera del nome
						
						$matricola = $letteranome . '.' . $cognomed . $i;  //creiamo la matricola
						$matricola = strtolower($matricola);
						
						$nomed = addslashes($nomed);
					
						if (!$connessione->query("INSERT INTO personale SET
						Matricola='$matricola',
						Nome='$nomed',
						Cognome='$cognomed',
						Direttore='1',
						Password='$matricola',
						Telefono='$teld',
						Indmail='$emaild',
						Museo_idMuseo='$idm'
						")) {
							echo "Errore della query: " . $connessione->error . ".";  //per capire se inserire o meno l'immagine sul server. Se la query non da errori, la variabile viene settata su false
						} else {
							echo '<label>Inserimento direttore effettuato correttamente.</label><br><br>';
							echo '<label>Credenziali direttore : </label><br>';
							echo '<label>Username : ',stripslashes($matricola),'</label><br>';
							echo '<label>Password : ',stripslashes($matricola),'</label><br>';
						}
					}
			} // Query per inserire il file nel DB
        
		//INSERIMENTO IMMAGINE NELLA CARTELLLA SUL SERVER
		
			if($flagimmagine==false){		//controllo se è stata inserita l'immagine
				if ($flagquery == false) {			/*controllo se la query di inserimento è andata a buon fine. Se flagquery è falso,
													inseriamo l'immagine sul server*/
													
					//percorso della cartella dove mettere i file caricati dagli utenti
					$uploaddir = 'C:/Apache24/htdocs/Smart Museum/immagini/musei/';

					//Recupero il percorso temporaneo del file
					$userfile_tmp = $_FILES['file_inviato']['tmp_name'];

					//recupero il nome originale del file caricato
					$userfile_name = $_FILES['file_inviato']['name'];

					//copio il file dalla sua posizione temporanea alla mia cartella upload
					if (move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name)) {
						//Se l'operazione è andata a buon fine...
						echo '<label>File inviato con successo.</label><br><br>';
						rename($uploaddir.$nomefile, $uploaddir.$nomefile1); // rinomina file
					} else {
						//Se l'operazione è fallta...
						echo '<label>Upload NON valido!</label><br><br>';
					}
				}
			}
		}}//una graffa
    //FINE INSERIMENTO
	}
	}else{
		$_SESSION['formerror'] = "Campi obbligatori non compilati!";
	header("Location: nuovomuseo.php");
	}

	$connessione->close();
	echo '<br><br><a href="http://localhost/Smart Museum/accedi/operazioniadmin.php">Continua</a>';
?>