<?php
//MODIFICA OPERA pt.1
echo '<link rel="stylesheet" content-type="text/css" href="modifica.css">';
session_start();
if (isset($_SESSION['login'])) {
		if ($_SESSION['login'] != "connesso" || $_SESSION['privilegio'] == "amministratore") {//verifica se è stato fatto login come amministratore
			$_SESSION['login'] = "sconnesso";
			header("Location: formlogin.php");
		}
}
?> 
<html lang="it">
    <head> 
        <meta charset="utf-8"> 
        <title>Modifica Opera</title> 
    </head>
    <body>
        <h1>MODIFICA OPERA</h1>
        <form action="formodificaopera.php" method="post">
		<select name="opera">
            <?php
            include "C:/Apache24/htdocs/Smart Museum/connessione.php";
			$flag=false;	//controllo numero opere>0
			
			$idmus=$_SESSION['idmuseo'];					//id del museo d'appartenenza
			
            if (!$result = $connessione->query("SELECT * FROM scheda WHERE Museo_idMuseo='" . $idmus . "'")) { // query selezione musei
                echo "Errore della query: " . $connessione->error . ".";  //controllo errore
            } else {
                if ($result->num_rows != 0) {  // conteggio dei record
                    while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) { // conteggio dei record restituiti dalla query e inserimento nell'array tmp
                        echo " <option value=",$tmp['CodiceReperto'],">", $tmp['Nome'], "</option>";
                    }
					echo '</select>';
                }else{
					$flag=true;
				}
            }
			
			//pulsante abilitato se almeno un museo è presente altrimenti il pulsante è disabilitatp
			if($flag==true){
				echo '<input type="submit" disabled>';
			}else{
				echo '<input type="submit">';
			}
			
            ?>
		</form>
		
		<a href="http://localhost/Smart Museum/accedi/operazionipersonale.php">Indietro</a>
    </body>
</html>