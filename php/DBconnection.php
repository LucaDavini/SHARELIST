<?php

	$host = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'ShareListDB';

	$conn = new mysqli($host, $username, $password, $dbname);

	if ($conn->connect_error)
	{
		die('Connect Error(' . $conn->connect_errno . ')' . $conn->connect_error);
	}

?>