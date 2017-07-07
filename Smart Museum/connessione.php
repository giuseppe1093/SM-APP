<?php

	$host = "localhost"; // nome di host
	$user = "root"; // username dell'utente in connessione
	$password = "password93"; // password dell'utente
	$db = "progettomusei"; // nome del database

	$connessione = new mysqli($host, $user, $password, $db); // stringa di connessione al DBMS


	if ($connessione->connect_errno) {
		echo "Connessione fallita: " . $connessione->connect_error . ".";
	} // verifica su eventuali errori di connessione
?>