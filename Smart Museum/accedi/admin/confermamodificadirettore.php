<?php
// pt.3
	echo '<link rel="stylesheet" content-type="text/css" href="modificadirettore.css">';
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione

	session_start();
	
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] != "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
	//prelevamento dati dal form
	$nomed = $_POST['nomed'];
	$cognomed = $_POST['cognomed'];
	$teld = addslashes($_POST['teld']);
	$emaild = addslashes($_POST['emaild']);
	
	$cognomed = addslashes($cognomed);
	
	$mat=$_SESSION["matricola"];//prelevo matricola del direttore da modificare
	$nom=$_SESSION['direttorenome'];
	$cog=$_SESSION['direttorecognome'];	
	$tel=$_SESSION['direttoretelefono'];
	$mail=$_SESSION['direttoreemail'];
	
	if($cog!=$cognomed ||$nom!=$nomed){			//GIUSEPPE
		//Creazione matricola del nuovo direttore
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
			if($i==0){$i++;}
			$letteranome = substr($nomed, 0, 1);  //preleva la prima lettera del nome
			$matricola = $letteranome . '.' . $cognomed . $i;  //creiamo la matricola
			$matricola = strtolower($matricola);
			$nomed = addslashes($nomed);
			$mat=addslashes($mat);
			//modifico direttore
			if (!$connessione->query("UPDATE personale SET
				Matricola='$matricola',
				Nome='$nomed',
				Cognome='$cognomed',
				Telefono='$teld',
				Password='$matricola',
				Indmail='$emaild'
				WHERE Matricola='$mat'")) {
				echo "Errore della query!!: " . $connessione->error . ".";      //	Tre attributi per l'immagine
			} else {
				$nomed = stripslashes($nomed);
				$cognomed = stripslashes($cognomed);
				$matricola = stripslashes($matricola);
				echo '<label>Inserimento effettuato correttamente.</label><br><br>';
				echo '<label>Nuove Informazioni Direttore : </label><br>';
				echo '<label>Username : ',stripslashes($matricola),'</label><br>';
				echo '<label>Password : ',stripslashes($matricola),'</label><br>';
				echo '<label>Nome : ',stripslashes($nomed),'</label><br>';
				echo '<label>Cognome : ',stripslashes($cognomed),'</label><br><br>';
			}
		}
	}else{
		echo "<label>Credenziali non modificate! I campi sono uguali!</label><br><br>";
	}
	if($tel!=$teld || $mail!=$emaild){
			echo '<label>Telefono : ',stripslashes($teld),'</label><br>';
			echo '<label>email : ',stripslashes($emaild),'</label><br>';
	}else {
		echo "<label>Contatti non modificati!</label><br><br>";
	}
	
	unset($_SESSION['matricola']);
	unset($_SESSION['direttorenome']);
	unset($_SESSION['direttorecognome']);
	unset($_SESSION['direttoretelefono']);
	unset($_SESSION['direttoreemail']);
	echo '<a class="pulsante" href="http://localhost/Smart Museum/accedi/operazioniadmin.php">Continua</a>';
?>