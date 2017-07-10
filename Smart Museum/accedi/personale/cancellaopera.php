<?php

echo '<link rel="stylesheet" content-type="text/css" href="rimuovi.css">';
//INSERIMENTO NUOVA STRUTTURA MUSEALE CON FORM

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
        <title>Cancella opera</title> 
    </head>
    <body>
		
		<label>RIMUOVI OPERA</label><br>
		<!-- INFORMAZIONI MUSEO -->
		
		<label>*cancellando il museo verranno cancellati il personale addetto e le opere allegate all'id del museo</label>
		<form action="confermacancellaopera.php" method="post">
		<select name="opera">
		<?php
		$flag=false; //controllo numero opere>0
			include "C:/Apache24/htdocs/Smart Museum/connessione.php";  // inclusione del file di connessione;connessione al database
			$idm=$_SESSION['idmuseo']; //id museo d'appartenenza
			
			if (!$result = $connessione->query("SELECT * FROM scheda WHERE Museo_idMuseo='" . $idm . "'")) { // query selezione musei
				echo "Errore della query: " . $connessione->error . ".";  //controllo errore
			} else {
				if ($result->num_rows > 0) {  // conteggio dei record
					while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
						echo " <option value=",$tmp['CodiceReperto'],">", $tmp['Nome'], "</option>";
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