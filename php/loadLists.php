<?php

	require_once './DBconnection.php';

	session_start();
	if (isset($_SESSION['logged_in']))
	{
		$query = 'SELECT list_id, list_name, creator, purpose FROM lists INNER JOIN participants ON list_id = list WHERE user = "' . $_SESSION['username'] .'" ORDER BY created_at DESC';

		$result = $conn->query($query);

		if ($result->num_rows > 0)
		{
			// se sono attive 10 liste contemporaneamente creo la tupla in 'achievements'
			if ($result->num_rows == 10) 
			{
				$trophy_query = 'INSERT INTO achievements VALUES (5, "' . $_SESSION['username'] . '", "' . date('Y-m-d') . '");';

				$trophy_result = $conn->query($trophy_query);
			}

			while ($row = $result->fetch_assoc())
			{
				$array[] = $row;
			}

			echo json_encode($array);	// passo la risposta ad ajax in formato json
		}
		else
			echo 'Empty Result Set';		// gestione del result set vuoto
	}
	
	$conn->close();

?>