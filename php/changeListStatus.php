<?php

	require_once './DBconnection.php';

	session_start();
	if (isset($_SESSION['logged_in']))
	{
		$check_query = 'SELECT blocked FROM lists WHERE list_id=' . $_GET['list_id'];

		$result = $conn->query($check_query);
		$result = $result->fetch_assoc()['blocked'];

		if (!$result)	// blocco se non è già stata bloccata da altri
		{
			$block_query = 'UPDATE lists SET blocked="' . $_SESSION['username'] . '" WHERE list_id=' . $_GET['list_id'];

			$result = $conn->query($block_query);
		}
		else if ($result == $_SESSION['username'])	// se è l'utente bloccante, la sblocco
		{
			$free_query = 'UPDATE lists SET blocked=null WHERE list_id=' . $_GET['list_id'];

			$result = $conn->query($free_query);

			// aggiorno i contatori dell'utente
			$update_query = 'UPDATE users SET shopping_done = shopping_done + 1 WHERE username="' . $_SESSION['username'] . '";';

			$result = $conn->query($update_query);

			// controllo il contatore per eventuali trofei sbloccati
			$counter_query = 'SELECT shopping_done FROM users WHERE username="' . $_SESSION['username'] . '";';

			$result = $conn->query($counter_query);
			$shopping_done = $result->fetch_assoc()['shopping_done'];

			if ($shopping_done == 1)
			{
				$trophy_query = 'INSERT INTO achievements VALUES (2, "' . $_SESSION['username'] . '", "' . date('Y-m-d') . '");';

				$trophy_result = $conn->query($trophy_query);
			}
			else if ($shopping_done == 10)
			{
				$trophy_query = 'INSERT INTO achievements VALUES (7, "' . $_SESSION['username'] . '", "' . date('Y-m-d') . '");';

				$trophy_result = $conn->query($trophy_query);
			}
		}
		else
		{
			echo json_encode($result);	// se qualcuno non ha ancora aggiornato la pagina
		}
	}

	$conn->close();

?>