<?php

	require_once './DBconnection.php';

	session_start();
	if (isset($_SESSION['logged_in']))
	{
		$user_query = 'SELECT * FROM users WHERE username = "' . $_GET['user'] . '";';
		$result = $conn->query($user_query);

		if ($result->num_rows == 1)		// esiste l'utente
		{
			$add_user_query = 'INSERT INTO participants VALUES ("' . $_GET['user'] . '", ' . $_GET['list'] . ')';
			$result = $conn->query($add_user_query);

			echo $result;
		}
		else
			echo false;
	}

	$conn->close();

?>