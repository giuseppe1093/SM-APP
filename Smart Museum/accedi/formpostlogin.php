<?php
	//effettua prima il controllo di username e password e se sono corretti l'utente viene connesso
	session_start();

	//LETTURA CREDENZIALI ADMIN
	$user = "admin";
	
	$fp = fopen("password admin.txt", "r");		//apertura file

	$pass = fread($fp, 50);
	fclose($fp);
	// fine admin

	$us = addslashes($_POST['username']);	//assegnamento ad una variabile il valore prelevato dai campi dedicati alle credenziali 
	$pa = addslashes($_POST['password']);	//			"							"

	if ($us == null || $pa == null) {   //controllo se i campi sono vuoti
		$_SESSION['errore'] = "Errore!!! Inserire credenziali!"; //errore credenziali
		header("Location: formlogin.php");
	}

    if (strcmp($us, $user) == 0 && strcmp($pa, $pass) == 0) {  //controllo per vedere se è entrato con credenziali amministratore
        $_SESSION['privilegio'] = "amministratore";   //assegnazione privilegio amministratore. Per riconoscere l'utente che è entrato
        $_SESSION['login'] = "connesso";    //la sessione viene impostata a connesso
        $_SESSION['nome'] = $us;      //memorizza l'username da visualizzare nel corso delle operazioni
        header("Location: operazioniadmin.php");  //indirizza al menù del personale
    } else {										
        include "C:/Apache24/htdocs/Smart Museum/connessione.php";      // inclusione del file di connessione;connessione al database
        if (!$result = $connessione->query("SELECT * FROM personale WHERE Matricola='" . $us . "'")) { /* prelevamento,ricerca e confronto per username. 
																										  Se non trova la corrispondenza, il risultato 
																										  sarà un tabella con 0 righe */
            //query selezione del museo scelto attravero la clausola where e paragone del'username
            echo "Errore della query: " . $connessione->error . ".";  //controllo errore
        } else {
            if ($result->num_rows != 0) {  // conteggio dei record 
				$us=stripslashes($us);
                while ($tmp = $result->fetch_array(MYSQLI_ASSOC)) {  //associazioni della tabella risultante all'array tmp
                    if (strcmp(stripslashes($pa), $tmp['Password']) == 0) {    //controllo password
                        if ($tmp['Direttore'] == 1) {        //controllo se è direttore
                            $_SESSION['privilegio'] = "direttore"; //assegnazione privilegio direttore. Per riconoscere l'utente che è entrato
                            $_SESSION['nome'] = $us; //memorizza l'username da visualizzare nel corso delle operazioni
                        } else {
                            $_SESSION['privilegio'] = "operatore"; //assegnazione privilegio operatore. Per riconoscere l'utente che è entrato
                            $_SESSION['nome'] = $us; //memorizza l'username da visualizzare nel corso delle operazioni
                        }
                        $_SESSION['login'] = "connesso";  //la sessione viene impostata a connesso
                        $_SESSION['idmuseo'] = $tmp['Museo_idMuseo'];  //per memorizzare il museo di appartenenza
                        header("Location: operazionipersonale.php");  //avvenuta verifica quindi eseguito l'accesso
                    } else {
                        $_SESSION['errore'] = "Errore credenziali!"; //errore credenziali
                        header("Location: formlogin.php");   //ritorna all'inserimento delle credenziali
                    }
                }
                $result->close();  // liberazione delle risorse occupate dal risultato
            } else {    //se non è stata trovata la corrispondenza dell'username nel database
                $_SESSION['errore'] = "Errore credenziali!"; //errore credenziali. Utente non trovato quindi inesistente
                header("Location: formlogin.php");   //ritorna all'inserimento delle credenziali
            }
        }
        $connessione->close(); //chiusura connessione database
    }
?>