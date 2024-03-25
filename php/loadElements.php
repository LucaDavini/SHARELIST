<?php

	require_once './DBconnection.php';

	session_start();
	if (isset($_SESSION['logged_in']))
	{
		$query = 'SELECT type, elem_name, quantity FROM elements WHERE list=' . $_GET['list_id'] . ' ORDER BY type';

		$result = $conn->query($query);

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$array[] = $row;
			}

			echo json_encode($array);
		}
		else
			echo 'Empty List';		// result set vuoto
	}

	$conn->close();

?>