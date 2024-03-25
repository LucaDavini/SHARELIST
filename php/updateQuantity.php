<?php

	require_once './DBconnection.php';

	session_start();
	if (isset($_SESSION['logged_in']))
	{
		// controllo che la lista non sia bloccata
		$status_query = 'SELECT blocked FROM lists WHERE list_id=' . $_GET['list_id'];

		$result = $conn->query($status_query);
		$blocked = $result->fetch_assoc()['blocked'];

		if (!$blocked || ($blocked == $_SESSION['username']))	// lista modificabile solo se non bloccata o se l'utente è il bloccante
		{
			if ($_GET['operation'] == 'remove')
			{
				$update_query = 'DELETE FROM elements WHERE elem_name="' . $_GET['elem'] . '" AND list=' . $_GET['list_id'];
			}
			else if ($_GET['operation'] == 'decrement')
			{
				$update_query = 'UPDATE elements SET quantity = quantity - 1 WHERE elem_name="' . $_GET['elem'] . '" AND list=' . $_GET['list_id'];
			}
			else if ($_GET['operation'] == 'increment')
			{
				$update_query = 'UPDATE elements SET quantity = quantity + 1 WHERE elem_name="' . $_GET['elem'] . '" AND list=' . $_GET['list_id'];
			}

			$result = $conn->query($update_query);

			echo $result;
		}
		else
			echo false;
	}

	$conn->close();

?>