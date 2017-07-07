<?php
// pt.2
	echo '<link rel="stylesheet" content-type="text/css" href="modificadirettore.css">';
session_start();
if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] != "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
}

$idmuseo=$_POST['musei'];	//PRELEVO ID MUSEO

include "C:/Apache24/htdocs/Smart Museum/connessione.php";
    if (!$result = $connessione->query("SELECT * FROM personale WHERE Museo_idMuseo='" . $idmuseo . "' AND Direttore='1'")) { // query selezione musei
        echo "Errore della query: " . $connessione->error . ".";  //controllo errore
    } else {
        if ($result->num_rows > 0) {  // conteggio dei record
            while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
                echo '<label>Matricola: ',$tmp['Matricola'],'</label><br>';
				echo '<label>idMuseo: ',$idmuseo,'</label><br><br>';
				echo '<form action="confermamodificadirettore.php" method="post">';
					echo '<label>(*)Scheda Direttore</label><br><br>';
					echo '<label>Nome Direttore:</label><input type="text" name="nomed" value="',$tmp['Nome'],'"><br>';
					echo '<label>Cognome Direttore:</label><input type="text" name="cognomed" value="',$tmp['Cognome'],'"><br>';
					echo '<label>Tel. Direttore:</label><input type="text" name="teld" value="',$tmp['Telefono'],'"><br>';
					echo '<label>email Direttore:</label><input type="email" name="emaild" value="',$tmp['Indmail'],'"><br>';
					echo '<input type="submit" name="invia" value="Conferma">';
				echo '</form>';
				$_SESSION['matricola']=$tmp['Matricola'];
				$_SESSION['direttorenome']=$tmp['Nome'];
				$_SESSION['direttorecognome']=$tmp['Cognome'];	
				$_SESSION['direttoretelefono']=$tmp['Telefono'];
				$_SESSION['direttoreemail']=$tmp['Indmail'];		
            }
        }
    }
?>