<?php
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione
	
	echo '<link rel="stylesheet" type="text/css" href="inserimento.css">';
	session_start();
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
	
	$controllo=false;
	$flagquery=false;
	
	//prelevo dati dal form
	$nomeopera = $_POST['nomeopera'];
	$artista = addslashes($_POST['artistaopera']);
	$descrizione = addslashes($_POST['descrizioneopera']);
	
	$idm=$_SESSION['idmuseo']; //id museo d'appartenenza
	
	//CONTROLLO IMMAGINE
	$nome_file_vero = $_FILES["file_inviato"]["name"];   //da memorizzare
	$nomefile=$nome_file_vero;		//variabile utilizzata per rinominare il file immagine
	$flagimmagine=false;  //variabile utilizzata per controllare se è stata inserita un'immagine
    
	if ($nome_file_vero == null) {
        $nome_file_vero = "?";
        $tipo_file = "?";
		$flagimmagine=true;
    }else{
		//IMMAGINE
		
		$nomeoperais = $_POST['nomeopera'];
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
			$controllo=true;
		}
	}
	
	//CONTROLLO AUDIO
	$nome_audio_vero = $_FILES["audio_inviato"]["name"];   //da memorizzare
	$nomeaudio=$nome_audio_vero;		//variabile utilizzata per rinominare il file audio
	$flagaudio=false;  //variabile utilizzata per controllare se è stata inserita l'audio
	
	if ($nome_audio_vero == null) {
        $nome_audio_vero = "?";
        $tipo_audio = "?";
		$flagaudio=true;
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
			$controllo=true;
		}
	}
	//FINE PRELEVAMENTO
	
	if($nomeopera == null || $artista == null || $descrizione == null)
	{
		$_SESSION['formerror'] = "Campi opera non compilati!";
		header("Location: forminserimentoscheda.php");
	}else{
		if($controllo==true){
			$_SESSION['formerror'] = "Formato file non corretto!";
			header("Location: forminserimentoscheda.php");
		}else{
			$nomeopera=addslashes($nomeopera);
			if (!$result = $connessione->query("SELECT * FROM scheda WHERE Nome LIKE '" . $nomeopera . "%'")) { /*ricerca se ci sono altre opere aventi lo stesso nome*/
				echo "Errore della query: " . $connessione->error . ".";  //controllo errore
			} else {
				$nomeopera=stripslashes($nomeopera);
					$i=0;
					if ($result->num_rows > 0) {  // conteggio dei record
						while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
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
							$nomeopera = $nomeopera . ' ' . $i;  //creiamo la matricola
						}
					}
					//nome file audio e immagine
					if($flagimmagine!=true){
					$nome_file_vero=$nomeopera.$nome_file_vero;
					$nomefile1=$nome_file_vero;
					$nome_file_vero=addslashes($nome_file_vero);
					}
					if($flagaudio!=true){
					$nome_audio_vero=$nomeopera.$nome_audio_vero;
					$nomeaudio1=$nome_audio_vero;
					$nome_audio_vero=addslashes($nome_audio_vero);
					}
					
					$nomeopera = addslashes($nomeopera);
					
					if (!$connessione->query("INSERT INTO scheda SET
					Nome='$nomeopera',
					Artista='$artista',
					Descrizione='$descrizione',
					Immagine= '$nome_file_vero',
					Audio= '$nome_audio_vero',
					Museo_idMuseo='$idm'")) {
						echo "Errore della query: " . $connessione->error . ".";      //	Tre attributi per l'immagine
						//$flagquery = true;   //per capire se inserire o meno l'immagine sul server. Se la query non da errori, la variabile viene settata su false
					} else {
						echo '<label>Inserimento effettuato correttamente</label><br><br>';
						$flagquery = false;
						
						//INSERIMENTO FILE
						
						if($flagimmagine==false){		//controllo se è stata inserita l'immagine
							if ($flagquery == false) {			/*controllo se la query di inserimento è andata a buon fine. Se flagquery è falso,
													inseriamo l'immagine sul server*/
													
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
									rename($uploaddir.$nomefile, $uploaddir.$nomefile1); // rinomina file
								} else {
								//Se l'operazione è fallta...
									echo '<label>Upload NON valido!</label><br><br>';
								}
							}
						}
						
						if($flagaudio==false){		//controllo se è stata inserita l'immagine
							if ($flagquery == false) {			/*controllo se la query di inserimento è andata a buon fine. Se flagquery è falso,
													inseriamo l'immagine sul server*/
													
								//percorso della cartella dove mettere i file caricati dagli utenti
								$uploaddir = 'C:/Apache24/htdocs/Smart Museum/immagini/opere/audio/';

								//Recupero il percorso temporaneo del file
								$userfile_tmp = $_FILES['audio_inviato']['tmp_name'];

								//recupero il nome originale del file caricato
								$userfile_name = $_FILES['audio_inviato']['name'];

								//copio il file dalla sua posizione temporanea alla mia cartella upload
								if (move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name)) {
								//Se l'operazione è andata a buon fine...
									echo '<label>File inviato con successo</label><br>';
									rename($uploaddir.$nomeaudio, $uploaddir.$nomeaudio1); // rinomina file
								} else {
								//Se l'operazione è fallta...
									echo '<label>Upload NON valido!</label><br><br>';
								}
							}
						}
						
						//FINE INSERIMENTO FILE
					}
				
			}
		}
	}
	
	// Creazione e Salvataggio QR_Code associato al codice reperto
	
	include  "C:/Apache24/htdocs/Smart Museum/accedi/personale/QR_BarCode.php"; // inclusione classe QR_Code
	
	if (!$result = $connessione->query("SELECT * FROM scheda WHERE Nome='" . $nomeopera . "'")) { // query selezione opera appena inserita
		echo "Errore della query: " . $connessione->error . ".";  //controllo errore
	} else {
		if ($result->num_rows > 0) {  // conteggio dei record
			while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
				$idopera=$tmp['CodiceReperto'];
			}
		}
	}
	$qr = new QR_BarCode(); 
	$qr->text($idopera); 
	$qr->qrCode(200, 'C:/Apache24/htdocs/Smart Museum/immagini/opere/qrcode/'. $idopera.$nomeopera .'.png'); // Bisogna rinominare il file
	$qrnome=$idopera.$nomeopera.'.png';
	//QUERY MODIFICA OPERA
			if (!$connessione->query("UPDATE scheda SET		
				Qrcode='$qrnome'
				WHERE CodiceReperto='$idopera'")) {
				echo "Errore della query: " . $connessione->error . ".";      //	Tre attributi per l'immagine
			} else {
				echo '<label>QR CODE generato correttamente!</label><br><br>';;
			}
	$connessione->close();
	echo '<a class="pulsante" href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Continua</a>';
?>