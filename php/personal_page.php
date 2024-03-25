<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Neucha">
	<link rel="icon" href="../images/ShareListIcon.png">
	<link rel="stylesheet" href="../css/personal_page.css">
	<script defer src="../js/personal_page.js"></script>
	<title> ShareList </title>
</head>
<body onload="firstLoading()">

	<header>
		<h1>ShareList</h1>
	</header>

<?php 
	
	require_once './DBconnection.php';

	session_start();
	if (!isset($_SESSION['logged_in']))
{ ?>

	<div class="section not_logged">
		<p>
			Per poter visualizzare il contenuto di questa pagina devi aver affettuato l'accesso con il tuo account.
		</p>
		<button id="login_btn" onclick="window.location.href='./login.php'">Accedi</button>
	</div>

<?php } else { ?>

	<div class="section account">
		<div>
			<img <?php echo 'src="' . $_SESSION['profile_pic'] . '"' ?> alt="immagine di profilo">
			<p id="username">
				<?php
					echo $_SESSION['username'];
				?>
			</p>
		</div>
		<p>
			<a href="./logout.php">Esci</a>
		</p>
	</div>

	<section id="myLists">
		<h2> Le mie liste </h2>
		<div class="section container">
			<div class="list_card">
				<button id="add_list" onclick="addListPopUp()"> + </button>
			</div>
		</div>
	</section>

	<section id="myAchievements">
		<h2> I miei trofei </h2>
		<div class="section container">
			<div id="trophy1" class="trophy">
				<div>
					<img src="../images/trophy1.png" alt="badge 1">
				</div>
				<div>
					<h4>Nuovo Arrivato</h4>
				</div>
				<div>
					<p>Crea la tua prima lista</p>
				</div>
			</div>
			<div id="trophy2" class="trophy">
				<div>
					<img src="../images/trophy2.png" alt="badge 2">
				</div>
				<div>
					<h4>Vado io!</h4>
				</div>
				<div>
					<p>Fai la spesa per la tua prima volta</p>
				</div>
			</div>
			<div id="trophy3" class="trophy">
				<div>
					<img src="../images/trophy3.png" alt="badge 3">
				</div>
				<div>
					<h4>Vorrei questo..</h4>
				</div>
				<div>
					<p>Aggiungi un elemento a una lista</p>
				</div>
			</div>
			<div id="trophy4" class="trophy">
				<div>
					<img src="../images/trophy4.png" alt="badge 4">
				</div>
				<div>
					<h4>Variopinto</h4>
				</div>
				<div>
					<p>Crea un lista per ogni tipo</p>
				</div>
			</div>
			<div id="trophy5" class="trophy">
				<div>
					<img src="../images/trophy5.png" alt="badge 5">
				</div>
				<div>
					<h4>Impegnatissimo</h4>
				</div>
				<div>
					<p>Partecipa a 10 liste contemporaneamente</p>
				</div>
			</div>
			<div id="trophy6" class="trophy">
				<div>
					<img src="../images/trophy6.png" alt="badge 6">
				</div>
				<div>
					<h4>Il Creatore</h4>
				</div>
				<div>
					<p>Crea 20 liste</p>
				</div>
			</div>
			<div id="trophy7" class="trophy">
				<div>
					<img src="../images/trophy7.png" alt="badge 7">
				</div>
				<div>
					<h4>Shopping Mania</h4>
				</div>
				<div>
					<p>Vai a fare la spesa 10 volte</p>
				</div>
			</div>
			<div id="trophy8" class="trophy">
				<div>
					<img src="../images/trophy8.png" alt="badge 8">
				</div>
				<div>
					<h4>Golosone</h4>
				</div>
				<div>
					<p>Aggiungi elementi alle liste 100 volte</p>
				</div>
			</div>
			<div id="trophy9" class="trophy">
				<div>
					<img src="../images/trophy9.png" alt="badge 9">
				</div>
				<div>
					<h4>Cittadino del Mondo</h4>
				</div>
				<div>
					<p>Crea 10 liste per viaggi</p>
				</div>
			</div>
			<div id="trophy10" class="trophy">
				<div>
					<img src="../images/trophy10.png" alt="badge 10">
				</div>
				<div>
					<h4>Festaiolo</h4>
				</div>
				<div>
					<p>Crea 10 liste per feste</p>
				</div>
			</div>
		</div>
	</section>
	
<?php } ?>

</body>
</html>

<?php

	if (isset($_POST['list_name']))
	{
		$list_name = $conn->real_escape_string($_POST['list_name']);
		$creator = $conn->real_escape_string($_SESSION['username']);
		$purpose = $conn->real_escape_string($_POST['purpose']);
		$participants = $conn->real_escape_string($_POST['participants']);

		$participants = explode('\r\n', $participants);	// creo l'array dei partecipanti

		// inserisco la lista
		$insert_list_query = 'INSERT INTO lists(list_name, creator, purpose)
					VALUES ("' . $list_name . '", "' . $creator . '", "' . $purpose . '")';

		$result = $conn->query($insert_list_query);

		$id_query = 'SELECT MAX(list_id) AS list_id FROM lists';	// ricerco la lista appena inserita

		$result = $conn->query($id_query);
		$list_id = $result->fetch_assoc()['list_id'];

		// inserisco le tuple per i vari partecipanti (anche il creatore)
		$participants_query = 'INSERT INTO participants VALUES ("' . $creator . '", ' . $list_id . ')';
		$result = $conn->query($participants_query);

		for ($i = 0; $i < count($participants); $i++)
		{
			if ($participants[$i] != '')
			{
				$participants_query = 'INSERT INTO participants VALUES ("' . $participants[$i] . '", ' . $list_id . ')';
			
				$result = $conn->query($participants_query);
			}
		}

		// aggiorno i contatori dell'utente
		$update_query = 'UPDATE users SET ' . $purpose . '_lists=' . $purpose . '_lists + 1 WHERE username="' . $creator . '";';

		$result = $conn->query($update_query);

		// controllo i possibili contatori aggiornati
		$counter_query = 'SELECT ordinary_lists, guests_lists, party_lists, trip_lists, holiday_lists FROM users WHERE username="' . $creator . '";';

		$result = $conn->query($counter_query);
		$result = $result->fetch_assoc();

		$sum = 0;
		$variopinto_check = true;		// va a false se almeno uno dei contatori è a 0

		foreach($result as $counter)
		{
			if ($counter == 0)
				$variopinto_check = false;
			else
				$sum += $counter;
		}

		// controllo se sono stati sbloccati trofei e nel caso aggiungo la tupla in 'achievements'
		if ($sum == 1)
		{
			$trophy_query = 'INSERT INTO achievements VALUES (1, "' . $creator . '", "' . date('Y-m-d') . '");';

			$trophy_result = $conn->query($trophy_query);
		}
		else if ($sum == 20)
		{
			$trophy_query = 'INSERT INTO achievements VALUES (6, "' . $creator . '", "' . date('Y-m-d') . '");';

			$trophy_result = $conn->query($trophy_query);
		}

		if ($variopinto_check)
		{
			$trophy_query = 'INSERT INTO achievements VALUES (4, "' . $creator . '", "' . date('Y-m-d') . '");';

			$trophy_result = $conn->query($trophy_query);
		}

		if ($purpose == 'trip' && $result['trip_lists'] == 10)
		{
			$trophy_query = 'INSERT INTO achievements VALUES (9, "' . $creator . '", "' . date('Y-m-d') . '");';

			$trophy_result = $conn->query($trophy_query);
		}
		else if ($purpose == 'party' && $result['party_lists'] == 10)
		{
			$trophy_query = 'INSERT INTO achievements VALUES (10, "' . $creator . '", "' . date('Y-m-d') . '");';

			$trophy_result = $conn->query($trophy_query);
		}

		// apro direttamente la pagina della lista
		echo '<script>
					window.location.href = \'./list_page.php?list_id=' . $list_id . '\';
				</script>';
	}

	$conn->close();

?>