<?php
	// modifica operatore pt.2
	echo '<link rel="stylesheet" content-type="text/css" href="modifica.css">';
	session_start();

	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}

	$matricola=$_POST['operatore'];	//PRELEVO ID MUSEO

	include "C:/Apache24/htdocs/Smart Museum/connessione.php";
		if (!$result = $connessione->query("SELECT * FROM personale WHERE Matricola='" . $matricola . "'")) { // query selezione musei
			echo "Errore della query: " . $connessione->error . ".";  //controllo errore
		} else {
			if ($result->num_rows > 0) {  // conteggio dei record
				while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
					echo '<label>Matricola: ',$tmp['Matricola'],'</label><br><br>';
					echo '<form action="confermamodificaoperatore.php" method="post">';
						echo '<label>(*)Scheda Operatore</label><br><br>';
						echo '<label>Nome Operatore: "',$tmp['Nome'],'"</label><br>';
						echo '<label>Cognome Operatore: "',$tmp['Cognome'],'"</label><br>';
						echo '<label>Tel. Operatore:</label><input type="text" name="telo" value="',$tmp['Telefono'],'"><br>';
						echo '<label>email Operatore:</label><input type="email" name="emailo" value="',$tmp['Indmail'],'"><br>';
						echo '<input type="submit" name="invia" value="Conferma">';
					echo '</form>';
					$_SESSION['matricola']=$tmp['Matricola'];
					$_SESSION['telefono']=$tmp['Telefono'];
					$_SESSION['indirizzomail']=$tmp['Indmail'];
				}
			}
		}
?>