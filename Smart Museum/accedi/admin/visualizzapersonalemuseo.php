<?php
//VISUALIZZA PERSONALE PARTE 1
echo '<link rel="stylesheet" content-type="text/css" href="visualizza.css">';
session_start();
if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] != "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
}
?> 
<html lang="it">
    <head> 
        <meta charset="utf-8"> 
        <title>VISUALIZZA PERSONALE</title> 
    </head>
    <body>
        <h1>VISUALIZZA PERSONALE</h1>
        <form action="visualizzapersonale.php" method="post">
		<select name="musei">
            <?php
            include "C:/Apache24/htdocs/Smart Museum/connessione.php";
			$flag=false;	//controllo numero museo>0
			
            if (!$result = $connessione->query("SELECT * FROM museo")) { // query selezione musei
                echo "Errore della query: " . $connessione->error . ".";  //controllo errore
            } else {
                if ($result->num_rows != 0) {  // conteggio dei record
                    while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
                        echo " <option value=",$tmp['idMuseo'],">", $tmp['Nome'], "</option>";
                    }
					echo '</select>';
                }else{
					$flag=true;
				}
            }
			
			//pulsante abilitato se almeno un museo è presente altrimenti il pulsante è disabilitato
			if($flag==true){
				echo '<input type="submit" disabled><br><br>';
				echo '<label>Nessuna Struttura Museale Presente</label>';
			}else{
				echo '<input type="submit"><br>';
			}
            ?>
		</form>
		
		<a href="http://localhost/Smart Museum/accedi/operazioniadmin.php">Indietro</a>
    </body>
</html>