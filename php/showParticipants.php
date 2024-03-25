<?php

	require_once './DBconnection.php';

	session_start();
	if (isset($_SESSION['logged_in']))
	{
		$participants_query = 'SELECT user FROM participants WHERE list = ' . $_GET['list_id'];
		$result = $conn->query($participants_query);

		while ($row = $result->fetch_assoc())
		{
			$array[] = $row['user'];
		}

		echo json_encode($array);
	}

	$conn->close();

?>