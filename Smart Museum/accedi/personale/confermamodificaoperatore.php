<?php
	// modifica operatore pt.3
	echo '<link rel="stylesheet" content-type="text/css" href="modifica.css">';
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione

	session_start();
	
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
	
	//prelevamento dati dal form
	$telo = addslashes($_POST['telo']);
	$emailo = addslashes($_POST['emailo']);
	$matricola=$_SESSION['matricola'];
	
	$mat=$_SESSION["matricola"];//prelevo matricola del direttore da modificare
	$ind=$_SESSION['indirizzomail'];
	$tel=$_SESSION['telefono'];
					
	if(($ind==$emailo) && ($tel==$telo)){
		echo '<label>Nessuna Modifica effettuata.</label><br><br>';	
	}else {
		//modifico direttore
		$matricola=addslashes($matricola);
		$mat=addslashes($mat);
		if (!$connessione->query("UPDATE personale SET
					Telefono='$telo',
					Indmail='$emailo'
					WHERE Matricola='$matricola'")) {
					echo "Errore della query: " . $connessione->error . ".";     
					}
		echo '<label>Modifica effettuata correttamente.</label><br><br>';	
	}
	unset($_SESSION['matricola']);
	unset($_SESSION['indirizzomail']);
	unset($_SESSION['telefono']);
	echo '<a class="pulsante" href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Continua</a>';
?>