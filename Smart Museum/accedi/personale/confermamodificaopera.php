<?php
// MODIFICA OPERA pt.3
	echo '<link rel="stylesheet" content-type="text/css" href="modifica.css">';
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione

	session_start();
	
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}

	//controllo se sono stati modificati dei campi
	$controllomodifica = false;
	
	//prelevo informazioni opera precedenti
	$codopera=$_SESSION['codopera']; //codice opera
	$nomeoperavecchia=$_SESSION['nomeopera']; //nome opera
	$immagineopera=$_SESSION['immagineopera']; //immagine opera
	$audiopera=$_SESSION['audiopera']; //audio opera
	$artistavecchio=$_SESSION['artistaopera']; //artista opera
	$descrizionevecchio=$_SESSION['descrizioneopera']; //descrizione opera
	//LIBERO VARIABILI SESSIONE
	unset($_SESSION['codopera']);
	unset($_SESSION['immagineopera']);
	unset($_SESSION['audiopera']);
	unset($_SESSION['nomeopera']);
	unset($_SESSION['artistaopera']);
	unset($_SESSION['descrizioneopera']);
	
	//CONTROLLO SE ERANO PRESENTI FILE
	$immagineprecedente=false;
	$audioprecedente=false;
	if($immagineopera=="?"){
		$immagineprecedente=true;
	}
	if($audiopera=="?"){
		$audioprecedente=true;
	}
	
	//prelevo dati form
	$nomeoperanuova = $_POST['nomeo'];
	$artista = $_POST['artista'];
	$descrizione = $_POST['descrizione'];
	
	//CONTROLLO SE IL NOME HA SUBITO CAMBIAMENTI
	$cambiamentonome=false;
	if($nomeoperanuova==$nomeoperavecchia && $artista==$artistavecchio && $descrizione==$descrizionevecchio){
		$cambiamentonome=true;	//nome non cambiato
		$controllomodifica = true;
	}
	
	$controlloformato=false;   //variabile utilizzata per verificare il corretto formato del file audio/immagine
	//CONTROLLO IMMAGINE
	$nome_file_vero = $_FILES["file_inviato"]["name"];   //da memorizzare
	$inputimmagine=$nome_file_vero;			//utilizzata nel rename per ricordare file precendete
	$nomefile=$nome_file_vero;		//variabile utilizzata per rinominare il file immagine
	$flagimmagine=false;  //variabile utilizzata per controllare se è stata inserita un'immagine
    
	if ($nome_file_vero == null) {
		$flagimmagine=true;			//immagine non inserita
		if($immagineprecedente==false || $nome_file_vero == null){
			$nome_file_vero=$immagineopera;
		}
    }else{
		//IMMAGINE
		$nome_file_temporaneo = $_FILES["file_inviato"]["tmp_name"];
		$tipo_file = $_FILES["file_inviato"]["type"];
	
		// Leggo il contenuto del file
		$dati_file = file_get_contents($nome_file_temporaneo);
		// Preparo il contenuto del file per la query sql
		$dati_file = addslashes($dati_file);
		
	
		//controllo se è stato inserito un file immagine
		$is_img = getimagesize($_FILES['file_inviato']['tmp_name']);
		if (!$is_img) {
			echo 'Formato file non corretto!<br>';
			$controlloformato=true;
		}
		$controllomodifica = true;
		//modifico nome file immagine
	}
	
	//CONTROLLO AUDIO
	$nome_audio_vero = $_FILES["audio_inviato"]["name"];   //da memorizzare
	$inputaudio=$nome_audio_vero;		//utilizzata nel rename per ricordare file precendete
	$nomeaudio=$nome_audio_vero;		//variabile utilizzata per rinominare il file audio
	$flagaudio=false;  //variabile utilizzata per controllare se è stata inserita l'audio
	
	if ($nome_audio_vero == null) {
		$flagaudio=true;		//audio non inserito
		if($audioprecedente==false || $nome_audio_vero == null){
			$nome_audio_vero=$audiopera;
		}
	}else{
		//AUDIO
		$nome_audio_temporaneo = $_FILES["audio_inviato"]["tmp_name"];
		$tipo_audio = $_FILES["audio_inviato"]["type"];
	
		// Leggo il contenuto del file
		$dati_audio = file_get_contents($nome_audio_temporaneo);
		// Preparo il contenuto del file per la query sql
		$dati_audio = addslashes($dati_audio);
	
		//controllo formato audio
		$ext_ok = array('midi','mid','mp1','mp2','mp3','m4a','pca','ra','rm','wav','wma','xm','flac');
		$temp = explode('.', $_FILES["audio_inviato"]["name"]);
		$ext = end($temp);
		if (!in_array($ext, $ext_ok)) {
			echo 'Il file ha un estensione non ammessa!<br><br>';
			$controlloformato=true;
		}
		$controllomodifica = true;
	}
	
	if($controlloformato==true){
		echo '<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Continua</a>';
	}else{
		if($nomeoperanuova == null || $artista == null || $descrizione == null){
			echo "Campi non compilati!<br>";
			echo '<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Continua</a>';
		}else{
			//CONTROLLO E RIMOZIONE FILE PRECEDENTI
			if($flagimmagine!=true){	//rimuovo immagine precedente
				$immagineopera1 = "C:/Apache24/htdocs/Smart Museum/immagini/opere/immagine/".$immagineopera;  //rimozione immagine museo dal server
				if($immagineopera!="?"){	//elimino immagine precedente
					if (unlink($immagineopera1)) {
						echo '<label>Il file è stato cancellato!</label><br><br>';
					} else {
						echo '<label>Il file NON è stato cancellato!</label><br><br>';
					}
				}
			}
			if($flagaudio!=true){	//rimuovo immagine precedente
				$audiopera1 = "C:/Apache24/htdocs/Smart Museum/immagini/opere/audio/".$audiopera;  //rimozione immagine museo dal server
				if($audiopera!="?"){	//elimino immagine precedente
					if (unlink($audiopera1)) {
						echo '<label>Il file è stato cancellato!</label><br><br>';
					} else {
						echo '<label>Il file NON è stato cancellato!</label><br><br>';
					}
				}
			}
			//FINE RIMOZIONE FILE PRECEDENTI
			
			if($nomeoperanuova==$nomeoperavecchia){
				//SE I NOMI SONO UGUALI NON SERVE MODIFICARE I NOMI DEI FILE. NON SERVE RICERCARE NEL DATABASE
				$nomeoperanuova=$nomeoperavecchia;
			}else{
				$nomeoperanuova=addslashes($nomeoperanuova);
				if (!$result = $connessione->query("SELECT * FROM scheda WHERE Nome LIKE '" . $nomeoperanuova . "%'")) { /*ricerca se ci sono altre opere aventi lo stesso nome*/
					echo "Errore della query: " . $connessione->error . ".";  //controllo errore
				} else {
					$nomeoperanuova=stripslashes($nomeoperanuova);
					$i=0;
					if ($result->num_rows > 0) {  // conteggio dei record
						while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
							//VERIFICO QUANTE OPERE HANNO LO STESSO NOME E CREO L'ID DA AGGIUNGERE IN CODA
							$stringa=$tmp['Nome'];
							$temp = explode(' ', $stringa);
							$ext = end($temp);
							if(is_numeric($ext)){
								if($i<=$ext){
									$i=$ext+1;
								}
							}else{
								$i++;
							}
						}
						if($i>0){
							$nomeoperanuova = $nomeoperanuova . ' ' . $i;  //AGGIUNGO ID IN CONDA
						}
					}
				}
			}
			//RINOMINO FILE
			//
			if($flagimmagine==false){
				$nomefile=$nomeoperanuova.$nomefile;
				if($cambiamentonome!=false){
					$nomefile=$codopera.$nomefile;
					$nome_file_vero=$nomefile;
				}
			}
			if($flagaudio==false){
				$nomeaudio=$nomeoperanuova.$nomeaudio;
				if($cambiamentonome!=false){
					$nomeaudio=$codopera.$nomeaudio;
					$nome_audio_vero=$nomeaudio;
				}
			}
			
			//VARIABILI UTILIZZATE NELLA QUERY DI MODIFICA
			$nome_file_vero=addslashes($nome_file_vero);
			$nome_audio_vero=addslashes($nome_audio_vero);
			$nomeoperanuova = addslashes($nomeoperanuova);
			$artista = addslashes($artista);
			$descrizione=addslashes($descrizione);
			
			//QUERY MODIFICA OPERA
			if (!$connessione->query("UPDATE scheda SET		
				Nome='$nomeoperanuova',
				Artista='$artista',
				Descrizione='$descrizione',
				Immagine= '$nome_file_vero', 
				Audio='$nome_audio_vero'
				WHERE CodiceReperto='$codopera'")) {
				echo "Errore della query: " . $connessione->error . ".";      //	Tre attributi per l'immagine
			} else {
				$nome_file_vero=stripslashes($nome_file_vero);
				$nome_audio_vero=stripslashes($nome_audio_vero);
				
				//INSERIMENTO FILE
				if($flagimmagine==false){		//controllo se è stata inserita l'immagine
					//percorso della cartella dove mettere i file caricati dagli utenti
					$uploaddir = 'C:/Apache24/htdocs/Smart Museum/immagini/opere/immagine/';

					//Recupero il percorso temporaneo del file
					$userfile_tmp = $_FILES['file_inviato']['tmp_name'];

					//recupero il nome originale del file caricato
					$userfile_name = $_FILES['file_inviato']['name'];

					//copio il file dalla sua posizione temporanea alla mia cartella upload
					if (move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name)) {
						//Se l'operazione è andata a buon fine...
						echo '<label>File inviato con successo</label><br><br>';
						rename($uploaddir.$inputimmagine, $uploaddir.$nome_file_vero); // rinomina file
					} else {
						//Se l'operazione è fallta...
						echo '<label>Upload NON valido!</label><br><br>';
					}
				}
				
				//INSERIMENTO AUDIO
				if($flagaudio==false){		//controllo se è stata inserita l'immagine
					//percorso della cartella dove mettere i file caricati dagli utenti
					$uploaddir = 'C:/Apache24/htdocs/Smart Museum/immagini/opere/audio/';

					//Recupero il percorso temporaneo del file
					$userfile_tmp = $_FILES['audio_inviato']['tmp_name'];

					//recupero il nome originale del file caricato
					$userfile_name = $_FILES['audio_inviato']['name'];

					//copio il file dalla sua posizione temporanea alla mia cartella upload
					if (move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name)) {
						//Se l'operazione è andata a buon fine...
						echo '<label>File inviato con successo</label><br><br>';
						rename($uploaddir.$inputaudio, $uploaddir.$nome_audio_vero); // rinomina file
					} else {
						//Se l'operazione è fallta...
						echo '<label>Upload NON valido!</label><br><br>';
					}
				}
				
				$connessione->close();
			}
		}
	}
	if($controllomodifica==true){
		echo '<label>Operazione non effettuata, campi non modificati</label><br><br>';
	}
	else {
		echo '<label>Operazione effettuata!</label><br><br>';
	}
	echo '<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Continua</a>';
?>