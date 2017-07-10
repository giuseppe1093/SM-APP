<?php
	include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione
	
	session_start();
	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}
	
	$idopera=$_POST['qrcode']; //id museo d'appartenenza
	
	if (!$result = $connessione->query("SELECT * FROM scheda WHERE CodiceReperto='" . $idopera . "'")) { // query selezione musei
		echo "Errore della query: " . $connessione->error . ".";  //controllo errore
	} else {
		if ($result->num_rows > 0) {  // conteggio dei record
			while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
				echo '<button onclick="myFunction()"><img width="1000" heigh="1000" src="http://localhost/Smart Museum/immagini/opere/qrcode/',$tmp['Qrcode'],'"></button>';
				echo '<script>
						function myFunction() {
							window.print();
						}
					</script>';	
			}
		}
	}
	
    $result->close(); // liberazione delle risorse occupate dal risultato
	$connessione->close();  //chiusura connessione database
?>