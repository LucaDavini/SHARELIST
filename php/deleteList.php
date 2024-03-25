<?php

	require_once './DBconnection.php';

	session_start();
	if (isset($_SESSION['logged_in']))
	{
		$check_query = 'SELECT creator, blocked FROM lists WHERE list_id = ' . $_GET['list_id'];
		$result = $conn->query($check_query);
		$result = $result->fetch_assoc();

		if (!$result['blocked'])	// controllo che la lista non sia stata bloccata
		{
			if ($result['creator'] == $_SESSION['username'])	// controllo che l'utente che vuole eliminare la lista ne sia il creatore
			{
				// elimino prima tutti i riferimenti nelle tabelle 'participants' e 'elements'
				$delete_query = 'DELETE FROM participants WHERE list = ' . $_GET['list_id'];
				$result = $conn->query($delete_query);

				$delete_query = 'DELETE FROM elements WHERE list = ' . $_GET['list_id'];
				$result = $conn->query($delete_query);

				// ora posso eliminare definitivamente la lista
				$delete_query = 'DELETE FROM lists WHERE list_id = ' . $_GET['list_id'];
				$result = $conn->query($delete_query);

				echo $result;
			}
		}
		else	// se lista bloccata
		{
			echo false;
		}
	}

	$conn->close();

?>