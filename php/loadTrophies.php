<?php

	require_once './DBconnection.php';

	session_start();
	if (isset($_SESSION['logged_in']))
	{
		$trophies_query = 'SELECT trophy_id, date FROM achievements WHERE user="' . $_SESSION['username'] . '";';

		$result = $conn->query($trophies_query);

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
				$array[] = $row;

			echo json_encode($array);
		}
		else
			echo 'No Trophies';		// result set vuoto
	}

	$conn->close();

?>