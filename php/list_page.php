<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Neucha">
	<link rel="icon" href="../images/ShareListIcon.png">
	<link rel="stylesheet" href="../css/list_page.css">
	<script defer src="../js/list_page.js"></script>
	<title> ShareList </title>
</head>
<body onload="firstLoading(<?php echo $_GET['list_id'];?>)">

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

<?php } else {

		// dato che uso metodo GET, devo controllare che il 'list_id' nell'url sia corretto
		$participation_query = 'SELECT * FROM participants WHERE user = "' . $_SESSION['username'] . '"  AND list = ' . $_GET['list_id'];

		$result = $conn->query($participation_query);

		if ($result->num_rows == 0)
	{ ?>

		<div class="section not_logged">
			<p>
				Per poter visualizzare il contenuto di questa pagina devi partecipare alla lista.
			</p>
			<button id="login_btn" onclick="window.location.href='./personal_page.php'">Torna indietro</button>
		</div>

	<?php } else {

		$list_query = 'SELECT list_name, creator, purpose, blocked FROM lists WHERE list_id = "' . $_GET['list_id'] . '";';

		$list = $conn->query($list_query);
		$list = $list->fetch_assoc();

		$blocked = ($list['blocked']) ? true : false;

	?>

		<section class="form_container inactive">	<!-- pop-up per aggiunta elementi -->
			<div class="<?php echo $list['purpose']?>">
				<span class="exit_btn" onclick="addElementsPopUp()">X</span>
				<form id="addElem_form" action="../php/list_page.php?list_id=<?php echo $_GET['list_id']?>" method="post" onsubmit="addElemValidation(event)">
					<h3>Aggiungi un Elemento</h3>

					<input name="elem_name" class="form_field" type="text" placeholder="Nome"></input>
					<p class="err_message"></p>

					<select name="elem_type" class="form_field">
						<option value="">Categoria</option>
						<option value="../images/type_drink.png">Bibite</option>
						<option value="../images/type_carbo.png">Carboidrati</option>
						<option value="../images/type_meat.png">Carne</option>
						<option value="../images/type_sweets.png">Dolci</option>
						<option value="../images/type_fruit.png">Frutta</option>
						<option value="../images/type_milk.png">Latticini</option>
						<option value="../images/type_fish.png">Pesce</option>
						<option value="../images/type_veg.png">Verdura</option>
						<option value="../images/type_extra.png">Altro</option>
					</select>
					<p class="err_message"></p>

					<input name="elem_quantity" class="form_field" type="number" min="1" max="50" placeholder="Quantità (max 50)"></input>
					<p class="err_message"></p>

					<input id="sumbit_btn" class="form_field" type="submit" value="AGGIUNGI"></input>

				</form>
			</div>
		</section>
		
		<header class="<?php echo $list['purpose']?>">
			<span id="exit_arrow" onclick="window.location.href='./personal_page.php'">&vltri;</span>
			<h1> <?php echo $list['list_name']; ?> </h1>
			<h3>di <?php echo $list['creator']; ?></h3>
		</header>

		<div id="container" class="section">
			<div id="utilities" class="section">
				<div id="buttons_container">
					<img src="../images/print.png" alt="stampa" onclick="window.print()">

					<?php if ($_SESSION['username'] == $list['creator']) // accessibile solo al creatore della lista
					{ ?>
						<button id="delete_btn" class="buttons <?php echo $list['purpose']?>" onclick="deleteList(<?php echo $_GET['list_id']?>)" <?php
							if ($blocked)	// bottoni disabled se lista bloccata
								echo 'disabled';
						?>> Elimina Lista </button>
					<?php } ?>

				</div>
				<div id="list_container">
					<span id="part_list_btn" onclick="showParticipants(<?php echo $_GET['list_id']?>)"> Lista Partecipanti &nabla; </span>
				</div>
			</div>

			<div id="elements_list" class="section">
				<ul>
				</ul>
			</div>

			<div id="list_modifiers" class="section">
				<div>
				<?php 
					if ($_SESSION['username'] != $list['blocked'])	// per utenti normali
				{ ?>

					<button class="buttons <?php echo $list['purpose']?>" onclick="addElementsPopUp(<?php echo $_GET['list_id']?>)" <?php
							if ($blocked)	// bottoni disabled se lista bloccata
								echo 'disabled';
						?>> Aggiungi </button>
					<button class="buttons <?php echo $list['purpose']?>" onclick="changeStatus(<?php echo $_GET['list_id']?>)" <?php
							if ($blocked)	// bottoni disabled se lista bloccata
								echo 'disabled';
						?>> Blocca </button>

				<?php } else 	// per l'utente che ha bloccato la lista
				{ ?>

					<button class="buttons <?php echo $list['purpose']?>" onclick="changeStatus(<?php echo $_GET['list_id']?>)"> Termina Spesa </button>

				<?php } ?>

				</div>
			</div>
		</div>

	<?php }
} ?>

</body>
</html>

<?php

	if (isset($_POST['elem_name']))
	{
		if (!$list['blocked'])	// posso inserire solo se la lista non è bloccata
		{
			$elem_name = $conn->real_escape_string($_POST['elem_name']);
			$list_id = $conn->real_escape_string($_GET['list_id']);
			$elem_type = $conn->real_escape_string($_POST['elem_type']);
			$quantity = $conn->real_escape_string($_POST['elem_quantity']);

			// controllo che l'elemento non sia ancora stato inserito
			$check_elem_query = 'SELECT * FROM elements WHERE elem_name="' . $elem_name . '" AND list=' . $list_id;

			$check_result = $conn->query($check_elem_query);

			if ($check_result->num_rows != 0)
			{
				echo '<script>
						var container = document.getElementsByClassName(\'form_container\')[0];
						var err_message = document.getElementsByClassName(\'err_message\')[0];
						
						container.classList.remove(\'inactive\');
						err_message.textContent = \'Elemento già inserito\';
					</script>';
			}
			else
			{
				$insert_query = 'INSERT INTO elements VALUES("' . $elem_name . '", ' . $list_id . ', "' . $elem_type . '", ' . $quantity . ')';

				$result = $conn->query($insert_query);

				// aggiorno i contatori dell'utente
				$update_query = 'UPDATE users SET elements_added = elements_added + 1 WHERE username="' . $_SESSION['username'] . '";';

				$result = $conn->query($update_query);

				// controllo il contatore per eventuali trofei sbloccati
				$counter_query = 'SELECT elements_added FROM users WHERE username="' . $_SESSION['username'] . '";';

				$result = $conn->query($counter_query);
				$elements_added = $result->fetch_assoc()['elements_added'];

				if ($elements_added == 1)
				{
					$trophy_query = 'INSERT INTO achievements VALUES (3, "' . $_SESSION['username'] . '", "' . date('Y-m-d') . '");';

					$trophy_result = $conn->query($trophy_query);
				}
				else if ($elements_added == 100)
				{
					$trophy_query = 'INSERT INTO achievements VALUES (8, "' . $_SESSION['username'] . '", "' . date('Y-m-d') . '");';

					$trophy_result = $conn->query($trophy_query);
				}
			}
		}
		else
		{
			echo '<script>
					var container = document.getElementsByClassName(\'form_container\')[0];
					var err_message = document.getElementsByClassName(\'err_message\')[2];
						
					container.classList.remove(\'inactive\');
					err_message.textContent = \'Impossibile aggiungere: lista bloccata\';
				</script>';
		}

		echo '<script>
			    if (window.history.replaceState)
					window.history.replaceState(null, null, window.location.href);
			</script>';
	}

	$conn->close();

?>