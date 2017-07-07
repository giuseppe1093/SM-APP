<?php //pt.1

echo '<link rel="stylesheet" content-type="text/css" href="cancellamuseo.css">';
//RIMOZIONE STRUTTURA MUSEALE

session_start();
if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] != "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
}
?> 
<html>
    <head> 
        <meta charset="utf-8"> 
        <title>Nuovo Museo</title> 
    </head>
    <body>
		<?php
			if (isset($_SESSION['formerror'])) {
				echo $_SESSION['formerror'];    //per visualizzare se non sono stati compilati i campi obbligatori
				$_SESSION['formerror'] = null;
			} else {
				$_SESSION['formerror'] = null;
			}
		?>
		
		<label>RIMUOVI STRUTTURA</label><br>
		<!-- INFORMAZIONI MUSEO -->
		
		<label>*cancellando il museo verranno cancellati il personale addetto e le opere allegate all'id del museo</label>
		<form action="confermacancellamuseo.php" method="post">
		<select name="musei">
		<?php
			$flag=false; //controllo numero museo>0
            include "C:/Apache24/htdocs/Smart Museum/connessione.php";
            if (!$result = $connessione->query("SELECT * FROM museo")) { // query selezione musei
                echo "Errore della query: " . $connessione->error . ".";  //controllo errore
            } else {
                if ($result->num_rows > 0) {  // conteggio dei record
                    while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
                        echo " <option value=",$tmp['idMuseo'],">", $tmp['Nome'], "</option>";
                    }
					echo '</select>';
                } else {
					$flag=true;
				}
            }
			//pulsante abilitato se almeno un museo è presente altrimenti il pulsante è disabilitato
			if($flag==true){
				echo '<input type="submit" disabled><br><br>';
				echo '<label>Nessuna Struttura Museale Presente</label><br><br>';
			}else{
				echo '<input type="submit"><br><br>';
			}
            ?>
	
		<a href="http://localhost/Smart Museum/accedi/operazioniadmin.php">Indietro</a>
    </body>
</html>