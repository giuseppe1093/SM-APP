<?php
	//imposta sessione a 0, ovvero nessun utente connesso e reindirizza nella pagina di login
	session_start();
	$_SESSION['login'] = "sconnesso";
	$_SESSION['errore'] = "Logout effettuato <br>Arrivederci " . $_SESSION['nome'] . "!";
	header("Location: formlogin.php");
?> 