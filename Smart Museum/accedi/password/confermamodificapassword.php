<?php //pt.2
	
	echo '<link rel="stylesheet" content-type="text/css" href="modificapassword.css">';

	//CONFERMA MODIFICA PASSWORD

	session_start();
		if (isset($_SESSION['login'])) {
			if ($_SESSION['login'] != "connesso") {//verifica se è stato fatto login
				$_SESSION['login'] = "sconnesso";
				header("Location: C:/Apache24/htdocs/Smart Museum/accedi/formlogin.php");
			}
	}
	
	//inizializzo link errori o continua
	$indietro='<a class=pulsante href="http://localhost/Smart Museum/accedi/password/modificapassword.php">Indietro</a>';
	$avanti='<a class=pulsante href="http://localhost/Smart Museum/accedi/operazioniadmin.php">Continua</a>';

	//prelevamento dati dal form
	$passv = $_POST['passwordv'];
	$passn = $_POST['passwordn'];
	$passc = $_POST['passwordc'];

	$us=$_SESSION['nome']; //memorizzo username in una variabile

	if($us!='admin'){
		include "C:/Apache24/htdocs/Smart Museum/connessione.php"; // inclusione del file di connessione

		if (!$result = $connessione->query("SELECT * FROM personale WHERE Password='" . $passv . "' AND Matricola='$us'")) { /*ricerca se la password vecchia è corretta*/
			echo "Errore della query: " . $connessione->error . ".";  //controllo errore
		} else {
			$righe = $result->num_rows;	//controllo numero di righe restituite
			if($righe!=0){
				if($passn!=$passc || $passn==null){
					echo "<label>Errore inserimento dati! Inserire correttamente la nuova password!</label><br><br>";
					echo $indietro;
				}else{
					//modifico password
					if (!$connessione->query("UPDATE personale SET
						Password='$passn'
						WHERE Matricola='$us'")) {
						echo "Errore della query: " . $connessione->error . ".";      //	Tre attributi per l'immagine
					} else {
						echo "<label>Password modificata correttamente!</label><br><br>";
						echo $avanti;
					}
				}
			}else{
				echo "<label>Vecchia Password non corretta!</label><br><br>";
				echo $indietro;
			}
		}
	}else{
		//LETTURA CREDENZIALI ADMIN
		$fp = fopen("C:/Apache24/htdocs/Smart Museum/accedi/password admin.txt", "r");		//apertura file

		$passadmin = fread($fp, 50);
		fclose($fp);
	
		//controllo corrisondenza password
		if($passn!=$passc || $passn==null || $passadmin!=$passv){
			echo "<label>Errore password admin!</label><br><br>";
			echo $indietro;
		}else{
			//SCRITTURA CREDENZIALI ADMIN
			$fp = fopen("C:/Apache24/htdocs/Smart Museum/accedi/password admin.txt", "w+");		//apertura file		
			fwrite($fp, $passn);
			fclose($fp);
			echo "<label>Password modificata correttamente!</label><br><br>";
			echo $avanti;
		}
	}
	
?>