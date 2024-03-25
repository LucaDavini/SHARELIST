function firstLoading () {
	var myLists = document.getElementById('myLists');
	var myAchievements = document.getElementById('myAchievements');

	if (document.body.contains(myLists))
	{
		loadLists();	// primo caricamento immediato

		setInterval(loadLists, 5000);	// tengo l'utente aggiornato ogni 5sec
	}

	if (document.body.contains(myAchievements))
	{
		loadTrophies();
	}
}

/* LISTS LOADING */
function loadLists () {
	const request = new XMLHttpRequest();	// creo richiesta http

	request.onreadystatechange = function () {
		if (this.readyState === 4 && this.status === 200)
		{
			var lists_container = document.getElementById('myLists').getElementsByClassName('container')[0];

			while (lists_container.childNodes.length > 2)	// rimuovo tutto in modo da mantenere aggiornate le nuove e vecchie tabelle
				lists_container.removeChild(lists_container.lastChild);

			if (this.responseText != 'Empty Result Set')	// escludo il caso in cui l'utente non partecipa ad alcuna lista
			{
				var lists_array = JSON.parse(this.responseText);	// rendo leggibile la risposta

				lists_array.forEach((list)=>{	// creo una list_card per ogni lista a cui l'utente partecipa
					var list_card = document.createElement('div');
					list_card.setAttribute('id', list.list_id);
					list_card.classList.add('list_card', list.purpose);
					list_card.setAttribute('onclick', 'window.location.href = \'../php/list_page.php?list_id=' + list.list_id + '\'');

					var list_name = document.createElement('div');
					var name = document.createElement('h3');
					var creator = document.createElement('p');

					name.textContent = list.list_name;
					creator.textContent = ' di ' + list.creator;

					list_name.appendChild(name);
					list_name.appendChild(creator);
					list_card.appendChild(list_name);
					lists_container.appendChild(list_card);
				});
			}
		}
	};

	request.open('GET', '../php/loadLists.php');
	request.send();
}

/* ADD-LIST FORM */
function addListPopUp () {
	var form_container = document.getElementsByClassName('list_card')[0];
	var add_button = document.getElementById('add_list');

	add_button.remove();	// svuoto la carta per inserirci il form

	// creo i singoli elementi
	var wrapper = document.createElement('div');

	// bottone uscita
	var exit_btn = document.createElement('span');
	exit_btn.classList.add('exit_btn');
	exit_btn.setAttribute('onclick', 'closePopUp()');
	exit_btn.textContent = 'X';

	// form
	var form = document.createElement('form');
	form.setAttribute('id', 'addList_form');
	form.setAttribute('action', '../php/personal_page.php');
	form.setAttribute('method', 'post');
	form.setAttribute('onsubmit', 'addListValidation(event)');

	// titolo
	var form_title = document.createElement('h3');
	form_title.textContent = 'Nuova Lista';

	// nome lista
	var list_name = document.createElement('input');
	list_name.classList.add('form_field');
	list_name.setAttribute('name', 'list_name');
	list_name.setAttribute('type', 'text');
	list_name.setAttribute('placeholder', 'Nome Lista');

	// scopo lista
	var purpose = document.createElement('select');
	purpose.classList.add('form_field');
	purpose.setAttribute('name', 'purpose');

		var opt0 = document.createElement('option');
		opt0.setAttribute('value', '');
		opt0.textContent = 'Scegli uno scopo';

		var opt1 = document.createElement('option');
		opt1.setAttribute('value', 'ordinary');
		opt1.textContent = 'Ordinaria';

		var opt2 = document.createElement('option');
		opt2.setAttribute('value', 'guests');
		opt2.textContent = 'Ospiti';

		var opt3 = document.createElement('option');
		opt3.setAttribute('value', 'party');
		opt3.textContent = 'Festa';

		var opt4 = document.createElement('option');
		opt4.setAttribute('value', 'trip');
		opt4.textContent = 'Viaggio';

		var opt5 = document.createElement('option');
		opt5.setAttribute('value', 'holiday');
		opt5.textContent = 'Festività';

	// partecipanti alla lista
	var participants = document.createElement('textarea');
	participants.classList.add('form_field');
	participants.setAttribute('name', 'participants');
	participants.setAttribute('rows', '3');
	participants.setAttribute('spellcheck', 'false');
	participants.setAttribute('placeholder', 'Partecipanti (separati da invio)');
	participants.setAttribute('oninput', 'participantsCheck(\'input\')');
	participants.setAttribute('onblur', 'participantsCheck(\'blur\')');

	// bottone creazione
	var submit_btn = document.createElement('input');
	submit_btn.classList.add('form_field');
	submit_btn.setAttribute('id', 'submit_btn');
	submit_btn.setAttribute('name', 'submit_btn');
	submit_btn.setAttribute('type', 'submit');
	submit_btn.setAttribute('value', 'Crea');

	// messaggio di errore
	var error = document.createElement('p');
	error.setAttribute('class', 'err_message');

	// concateno i vari elementi creati
	purpose.appendChild(opt0);
	purpose.appendChild(opt1);
	purpose.appendChild(opt2);
	purpose.appendChild(opt3);
	purpose.appendChild(opt4);
	purpose.appendChild(opt5);

	form.appendChild(form_title);
	form.appendChild(list_name);
	form.appendChild(error);
	form.appendChild(purpose);
	form.appendChild(error.cloneNode());
	form.appendChild(participants);
	form.appendChild(error.cloneNode());
	form.appendChild(submit_btn);

	wrapper.appendChild(exit_btn);
	wrapper.appendChild(form);

	form_container.appendChild(wrapper);
}

function closePopUp () {
	var form_container = document.getElementsByClassName('list_card')[0];
	form_container.getElementsByTagName('div')[0].remove();		// rimuovo il form

	// ricostruisco il bottone
	var add_list = document.createElement('button');
	add_list.setAttribute('id', 'add_list');
	add_list.setAttribute('onclick', 'addListPopUp()');
	add_list.textContent = '+';

	form_container.appendChild(add_list);
}

/* ADD-LIST FORM VALIDATION */
function addListValidation (event) {
	event.preventDefault();
	var isValid = true;

	var form_fields = document.getElementsByClassName('form_field');
	var err_messages = document.getElementsByClassName('err_message');

	/* name  check */
	if (form_fields[0].value === '')
	{
		form_fields[0].style.borderColor = 'red';

		isValid = false;
	}
	else
	{
		form_fields[0].style.borderColor = '';
	}

	/* purpose check */
	if (form_fields[1].value === '')
	{
		form_fields[1].style.borderColor = 'red';

		isValid = false;
	}
	else
	{
		form_fields[1].style.borderColor = '';
	}

	/* participants check */
	if (form_fields[2].classList.contains('not_valid'))
	{
		err_messages[2].textContent = 'Lista utenti non corretta';

		isValid = false;
	}


	if (isValid)
	{
		document.getElementById('addList_form').submit();
	}
}

function participantsCheck (type) {	// 'type' serve a differenziare i due check
	var textarea = document.getElementsByClassName('form_field')[2];
	var error_msg = document.getElementsByClassName('err_message')[2];

	var participants = textarea.value;

	if (participants != '')
	{
		participants = participants.split('\n');	// costruisco un array con i vari partecipanti

		var check = false;
		var last_input = participants[participants.length - 1];
		var del;	// serve per trovare l'ultimo utente a seconda del 'type'
	
		if (type === 'blur' && last_input !== '')
		{
			del = 1
			check = true;
		}
		else if (type === 'input' && last_input === '')
		{	
			del = 2
			check = true;
		}

		var user = participants[participants.length - del];

		for (var i = 0; i < participants.length - del; i++)	// controllo che non sia stato già inserito
		{
			if (participants[i] === user)
			{
				check = false;

				textarea.classList.add('not_valid');
				error_msg.textContent = 'Utente già inserito';

				break;
			}
			else
			{
				textarea.classList.remove('not_valid');
				error_msg.textContent = '';
			}
		}

		if (check)
		{
			// ajax check di 'user', ultimo partecipante inserito
			const request = new XMLHttpRequest();
		
			request.onreadystatechange = function () {
				if (this.readyState === 4 && this.status === 200)
				{
					if (!this.responseText)		// ritorna falso se l'utente non esiste
					{
						textarea.classList.add('not_valid');
						error_msg.textContent = 'Utente inesistente';
					}
					else
					{
						textarea.classList.remove('not_valid');
						error_msg.textContent = '';
					}
				}
			}
		
			request.open('GET', '../php/participantsCheck.php?user=' + user);
			request.send();
		}
	}
	else		// accetto che non ci siano partecipanti
	{
		textarea.classList.remove('not_valid');
		error_msg.textContent = '';
	}
}

/* LOADING ACHIEVEMENTS */
function loadTrophies () {
	const request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		if (this.readyState === 4 && this.status === 200)
		{
			if (this.responseText != 'No Trophies')
			{
				var trophy_cards = document.getElementsByClassName('trophy');
				var trophies = JSON.parse(this.responseText);

				trophies.forEach((trophy)=>{
					var id = Number(trophy['trophy_id']);	// prima viene considerato come stringa
					var img = trophy_cards[id - 1].getElementsByTagName('img')[0];
					var text = trophy_cards[id - 1].getElementsByTagName('div');
					var desc = trophy_cards[id - 1].getElementsByTagName('p')[0];

					text[1].style.opacity = '1';
					text[2].style.opacity = '1';
					img.style.filter = 'none';
					desc.textContent += ' (' + trophy['date'] + ')';	// inserisco la data di ottenimento
				});
			}
		}
	};

	request.open('GET', '../php/loadTrophies.php');
	request.send();
}