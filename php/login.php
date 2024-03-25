<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Neucha">
	<link rel="icon" href="../images/ShareListIcon.png">
	<link rel="stylesheet" href="../css/homepage.css">
	<link rel="stylesheet" href="../css/forms.css">
	<title> ShareList </title>
</head>
<body>
	<?php

		require_once '../html/login_form.html';
		//require_once '../html/homepage_header.html';
		//require_once '../html/homepage_body.html';
		//require_once '../html/homepage_footer.html';

	?>
</body>
</html>

<?php

	require_once './DBconnection.php';

	if (isset($_POST['username']) && isset($_POST['password']))
	{
		$username = $conn->real_escape_string($_POST['username']);
		$query = 'SELECT password FROM users WHERE username = "' . $_POST['username'] . '"';

		$result = $conn->query($query);

		if ($result->num_rows == 1)		// se ottengo una corrispondenza univoca
		{
			$result = $result->fetch_assoc();	// rendo leggibile il result-set

			if (password_verify($_POST['password'], $result['password']))
			{
				$profile_pic_query = 'SELECT profile_pic FROM users WHERE username = "' . $_POST['username'] . '"';		// recupero l'img di profilo dal database
				$profile_pic = $conn->query($profile_pic_query);
				$profile_pic = $profile_pic->fetch_assoc()['profile_pic'];

				session_start();
				$_SESSION['logged_in'] = true;
				$_SESSION['username'] = htmlspecialchars($_POST['username']);
				$_SESSION['profile_pic'] = $profile_pic;
				
				echo '<script>
							window.location.href = \'./personal_page.php\';
					</script>';
			}
			else
			{
				echo '<script>
						var err_message = document.getElementsByClassName(\'err_message\')[1];
						
						err_message.textContent = \'Password errata\';
					</script>';
			}
		}
		else
		{
			echo '<script>
					var err_message = document.getElementsByClassName(\'err_message\')[1];
					
					err_message.textContent = \'Utente non registrato\';
				</script>';
		}
		
	}

	$conn->close();

?>