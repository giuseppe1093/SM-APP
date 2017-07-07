<?php

echo '<link rel="stylesheet" content-type="text/css" href="modifica.css">';
	//CONFERMA NUOVA STRUTTURA MUSEALE
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione

	session_start();
	
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
	
	$cittàv=$_SESSION['città'];
	$capv=$_SESSION['cap']; 
	$indirizzov=$_SESSION['indirizzo']; 
	$orariov=$_SESSION['orario']; 
	$telefonov=$_SESSION['telefono']; 
	$faxv=$_SESSION['fax']; 
	$indmailv=$_SESSION['indmail'];
	$descrizionev=$_SESSION['descrizione']; 

	
	$controllo=false;
	$idmus=$_SESSION['idmuseo'];					//id del museo d'appartenenza
	$nomemuseo=$_SESSION['museonome'];				//nome del museo d'appartenenza
	$immagine=$_SESSION['immagine'];				//nome dell'immagine del museo d'appartenenza
	$immagine1=$immagine;							//nome immagine salvato nel database
	//PRELEVAMENTO DATI DAL FORM
	$città = addslashes($_POST['città']);
	$cap = addslashes($_POST['cap']);
	$indirizzo = addslashes($_POST['indirizzo']);
	$orarioa = $_POST['orarioa'];
	$orarioc = $_POST['orarioc'];
	$orario = $orarioa . " / " . $orarioc;
	if ($orarioa == $orarioc) {
        $orario = $orariov;
    }
	
	$telefono = addslashes($_POST['tel']);
	$fax = addslashes($_POST['fax']);
	$email = addslashes($_POST['email']);
	$descrizione = addslashes($_POST['descrizione']);

	$modificaeff=false;
	//CONTROLLI
    if ($città != $cittàv) {
        $modificaeff=true;
    }
    if ($cap != $capv) {
        $modificaeff=true;
    }
    if ($indirizzo != $indirizzov) {
        $modificaeff=true;
    }
    if ($descrizione != $descrizione) {
        $modificaeff=true;
    }
    
    if ($telefono != $telefonov) {
        $modificaeff=true;
    }
    if ($fax != $faxv) {
        $modificaeff=true;
    }
    if ($email != $indmailv) {
        $modificaeff=true;
    }
	
	//CONTROLLO IMMAGINE
	$nome_file_vero = $_FILES["file_inviato"]["name"];   //da memorizzare
	$nomefile=$nome_file_vero;		//variabile utilizzata per rinominare il file immagine
	$flagimmagine=false;  //variabile utilizzata per controllare se è stata inserita un'immagine
    if ($nome_file_vero != null) {
		
		//immagine 
		$nomemuseoim=$_SESSION['museonome'];				//nome del museo d'appartenenza
		unset($_SESSION['museonome']);
		unset($_SESSION['immagine']);
		$nome_file_temporaneo = $_FILES["file_inviato"]["tmp_name"];
		$tipo_file = $_FILES["file_inviato"]["type"];
	
		// Leggo il contenuto del file
		$dati_file = file_get_contents($nome_file_temporaneo);
		// Preparo il contenuto del file per la query sql
		$dati_file = addslashes($dati_file);
		$nome_file_vero=$nomemuseoim.$nome_file_vero;
		$immagine1=$nome_file_vero;
		$nomefile1=$nome_file_vero;
		$nome_file_vero=addslashes($nome_file_vero);
		$immagine1=addslashes($immagine1);
		//controllo se è stato inserito un file immagine
		$is_img = getimagesize($_FILES['file_inviato']['tmp_name']);
		if (!$is_img) {
		echo "<label>Formato non corretto!</label>";
		$controllo=true;
		}
	}else{
		$flagimmagine=true;
		$immagine1=addslashes($immagine1);
    }
	//FINE PRELEVAMENTO
	if($controllo==false){
		
		if($flagimmagine!=true){	//rimuovo immagine precedente
			$immaginevec = "C:/Apache24/htdocs/Smart Museum/immagini/musei/".$immagine;  //rimozione immagine museo dal server
			if($immagine!="?"){	//elimino immagine precedente
				if (unlink($immagine)) {
					echo '<label>Il file è stato cancellato!</label><br><br>';
				} else {
					echo '<label>Il file NON è stato cancellato!</label><br><br>';
				}
			}
		}
	//QUERY MODIFICA MUSEO
	if (!$connessione->query("UPDATE museo SET		
		Città='$città',
		CAP='$cap',
		Indirizzo='$indirizzo',
		Descrizione='$descrizione',
		Orario='$orario',
		Immagine= '$immagine1', 
		Telefono='$telefono',
		Fax='$fax',
		Indmail='$email'
	    WHERE idMuseo='$idmus'")) {
		echo "Errore della query: " . $connessione->error . ".";      //	Tre attributi per l'immagine
		$flagquery = true;   //per capire se inserire o meno l'immagine sul server. Se la query non da errori, la variabile viene settata su false
	} else {
		if($modificaeff==true){
		echo '<label>Inserimento effettuato correttamente!</label><br><br>';
		$flagquery=false;
		}else{
			echo '<label>Nessuna modifica effettuata!</label><br><br>';
		}
	}
    
	//INSERIMENTO IMMAGINE NELLA CARTELLLA SUL SERVER

	if($flagimmagine!=true){		//controllo se è stata inserita l'immagine
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
	}//FINE INSERIMENTO
	}
	$connessione->close();
	echo '<a class="pulsante" href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Continua</a>';
?>