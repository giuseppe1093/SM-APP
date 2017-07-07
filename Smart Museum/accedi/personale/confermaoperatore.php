<?php //pt.2
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione
	echo '<link rel="stylesheet" type="text/css" href="inserimento.css">';
	session_start();
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
	
	//operatore
	$nomeo = $_POST['nomeo'];
	$cognomeo = $_POST['cognomeo'];
	$telo = addslashes($_POST['telo']);
	$emailo = addslashes($_POST['emailo']);
	$cognomeo = addslashes($cognomeo);
	
	$idm=$_SESSION['idmuseo']; //id museo d'appartenenza
	
	if($nomeo == null || $cognomeo == null || $telo == null || $emailo == null){
		echo '<label>Campi non compilati!</label><br><br>';
		echo '<a class="pulsante" href="http://localhost/Smart Museum/accedi/personale/inseriscioperatore.php">Indietro</a>&nbsp;';
	}else{
		
		if (!$result = $connessione->query("SELECT * FROM personale WHERE Cognome ='" . $cognomeo . "'")) { /*ricerca se ci sono altre opere aventi lo stesso nome*/
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
						$letteranome = substr($nomeo, 0, 1);  //preleva la prima lettera del nome
						$matricola = $letteranome . '.' . $cognomeo . $i;  //creiamo la matricola
						$matricola = strtolower($matricola);
						
						$nomeo = addslashes($nomeo);
						
			if (!$connessione->query("INSERT INTO personale SET
			Matricola='$matricola',
			Nome='$nomeo',
			Cognome='$cognomeo',
			Direttore='0',
			Password='$matricola',
			Telefono='$telo',
			Indmail='$emailo',
			Museo_idMuseo='$idm'
			")) {
				echo "Errore della query: " . $connessione->error . ".";  //per capire se inserire o meno l'immagine sul server. Se la query non da errori, la variabile viene settata su false
			} else {
				echo '<label>Inserimento effettuato correttamente.</label><br><br>';
				echo '<label>Credenziali operatore : </label><br>';
				echo '<label>Username : ',stripslashes($matricola),'</label><br>';
				echo '<label>Password : ',stripslashes($matricola),'</label><br><br>';
			}
		}
	}
	$connessione->close();
	echo '<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Continua</a>';
?>