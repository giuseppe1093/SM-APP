<?php // modifica operatore pt.1

	echo '<link rel="stylesheet" content-type="text/css" href="modifica.css">';
	session_start();

	if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
	}

?> 
<html>
    <head> 
        <meta charset="utf-8"> 
        <title>Modifica operatore</title> 
    </head>
    <body>
		
		<label>MODIFICA OPERATORE</label><br>
		<!-- INFORMAZIONI MUSEO -->
		
		<form action="formodificaoperatore.php" method="post">
		<select name="operatore">
		<?php
		$flag=false; //controllo numero opere>0
			include "C:/Apache24/htdocs/Smart Museum/connessione.php";  // inclusione del file di connessione;connessione al database
			$idm=$_SESSION['idmuseo']; //id museo d'appartenenza
			
			if (!$result = $connessione->query("SELECT * FROM personale WHERE Museo_idMuseo='" . $idm . "' AND Direttore='0'")) { // query selezione musei
				echo "Errore della query: " . $connessione->error . ".";  //controllo errore
			} else {
				if ($result->num_rows > 0) {  // conteggio dei record
					while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
						echo " <option value=",$tmp['Matricola'],">", $tmp['Cognome']," ", $tmp['Nome'], "</option>";
					}
					echo '</select>';
				}else{
					$flag=true;
				}
			}
		
		$connessione->close();
			
			//pulsante abilitato se almeno un museo è presente altrimenti il pulsante è disabilitatp
			if($flag==true){
				echo '<input type="submit" disabled>';
			}else{
				echo '<input type="submit">';
			}
		echo '</form>';
		?>
	
		<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Indietro</a>
    </body>
</html>