<?php

	require_once './DBconnection.php';

	session_start();
	if (isset($_SESSION['logged_in']))
	{
		$query = 'SELECT * FROM users WHERE username = "' . $_GET['user'] . '"';
	
		$result = $conn->query($query);
	
		echo ($result->num_rows == 0) ? false : true;
	}

	$conn->close();

?>