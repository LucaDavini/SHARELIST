<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Neucha">
	<link rel="icon" href="../images/ShareListIcon.png">
	<link rel="stylesheet" href="../css/homepage.css">
	<link rel="stylesheet" href="../css/forms.css">
	<script defer src="../js/forms.js"></script>
	<title> ShareList </title>
</head>
<body>
	<?php

		require_once '../html/signIn_form.html';
		//require_once '../html/homepage_header.html';
		//require_once '../html/homepage_body.html';
		//require_once '../html/homepage_footer.html';

	?>
</body>
</html>

<!-- PHP sotto il codice html in modo che sia eseguito successivamente al caricamento della pagina-->
<?php

	require_once './DBconnection.php';

	if (isset($_POST['username']))
	{
		// controllo unicità dello username
		$query = 'SELECT * FROM users WHERE username = "' . $_POST['username'] . '"';

		$result = $conn->query($query);
		if ($result->num_rows != 0)		// risultato non vuoto = username già esistente
		{
			echo '<script>
					var username_field = document.getElementById(\'signIn_form\').getElementsByTagName(\'input\')[2];
					var err_message = document.getElementsByClassName(\'err_message\')[2];
					
					username_field.style.borderColor = \'red\';
					err_message.textContent = \'Username già esistente\';
				</script>';
		}
		else
		{
			$first_name = $conn->real_escape_string($_POST['first_name']);	// protezione prima dell'inserimento nel db
			$last_name = $conn->real_escape_string($_POST['last_name']);
			$username = $conn->real_escape_string($_POST['username']);
			$password = $conn->real_escape_string($_POST['password']);
			$profile_pic = '../images/account' . rand(1, 8) . '.jpg';

			$passwordHash = password_hash($password, PASSWORD_BCRYPT);

			$insert_query = 'INSERT INTO users(first_name, last_name, username, password, profile_pic)
					VALUES ("' . $first_name . '", "' . $last_name . '", "' . $username . '", "' . $passwordHash . '", "' . $profile_pic . '")';

			$result = $conn->query($insert_query);

			session_start();
			$_SESSION['logged_in'] = true;
			$_SESSION['username'] = htmlspecialchars($_POST['username']);
			$_SESSION['profile_pic'] = $profile_pic;

			echo '<script>
					window.location.href = \'./personal_page.php\';
				</script>';
		}
	}

	$conn->close();
?>