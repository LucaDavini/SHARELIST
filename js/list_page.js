/* LIST'S ELEMENTS LOADING */
function firstLoading (list_id) {
	var elements_list = document.getElementById('elements_list');

	if (document.body.contains(elements_list))
	{
		loadElements(list_id);

		setInterval(loadElements, 2000, list_id);
	}
}

function loadElements (list_id) {
	const request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		if (this.readyState === 4 && this.status === 200)
		{
			var list = document.getElementById('elements_list').getElementsByTagName('ul')[0];

			while (list.childNodes.length)		// elimino tutta la lista per mantenere l'aggiornamento costante
				list.removeChild(list.firstChild);

			if (this.responseText != 'Empty List')
			{
				var elements = JSON.parse(this.responseText);

				elements.forEach((elem)=>{
					var new_item = document.createElement('li');

					var element_card = document.createElement('div');
					element_card.classList.add('element_card');
					element_card.classList.add(elem['type']);

					var img_div = document.createElement('div');
					img_div.classList.add('img_div');
					
					var elem_img = document.createElement('img');
					elem_img.setAttribute('src', elem['type']);
					elem_img.setAttribute('alt', 'categoria');

					var name = document.createElement('span');
					name.textContent = elem['elem_name'];

					var quantity = document.createElement('span');
					quantity.textContent = 'x' + elem['quantity'];

					var add_elem = document.createElement('span');
					add_elem.classList.add('add_elem');
					add_elem.setAttribute('onclick', 'updateQuantity(event, ' + list_id + ')');
					add_elem.textContent = '+';

					var remove_elem = document.createElement('span');
					remove_elem.classList.add('remove_elem');
					remove_elem.setAttribute('onclick', 'updateQuantity(event, ' + list_id + ')');
					remove_elem.textContent = '-';

					// costruisco la lista
					img_div.appendChild(elem_img);

					element_card.appendChild(img_div);
					element_card.appendChild(name);
					element_card.appendChild(quantity);

					new_item.appendChild(element_card);
					new_item.appendChild(add_elem);
					new_item.appendChild(remove_elem);

					list.appendChild(new_item);
				});
			}
		}
	};

	request.open('GET', '../php/loadElements.php?list_id=' + list_id);
	request.send();
}

/* SHOW PARTICIPANTS */
function showParticipants (list_id) {
	const request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		if (this.readyState === 4 && this.status === 200)
		{
			var participants_array = JSON.parse(this.responseText);
			var div = document.getElementById('utilities').getElementsByTagName('div')[1];
			var part_list = document.getElementById('part_list');

			if (!div.contains(part_list))
			{
				// costruisco il container
				var part_list = document.createElement('div');
				part_list.setAttribute('id', 'part_list');
	
				// costruisco i contenitori per i vari partecipanti
				participants_array.forEach((user)=>{
					var next_user = document.createElement('p');
					next_user.textContent = user;
		
					part_list.appendChild(next_user);
				});
	
				// inserisco un bottone per l'aggiunta di altri partecipanti
				var add_participant = document.createElement('p');
				add_participant.textContent = '+';
				add_participant.setAttribute('id', 'add_participant');
				add_participant.setAttribute('onclick', 'showInput(' + list_id + ')');

				part_list.appendChild(add_participant);
	
				div.appendChild(part_list);
			}
			else
			{
				part_list.remove();
			}
		}
	};

	request.open('GET', '../php/showParticipants.php?list_id=' + list_id);
	request.send();
}

/* ADD PARTICIPANTS */
function showInput (list_id) {
	var add_participant = document.getElementById('add_participant');
	add_participant.removeAttribute('onclick');
	add_participant.textContent = '';

	var input = document.createElement('input');
	input.setAttribute('name', 'new_participant');
	input.setAttribute('type', 'text');
	input.setAttribute('onkeydown', 'addParticipant(event,' + list_id + ')');

	add_participant.appendChild(input);
}

function addParticipant (event, list_id) {
	var input = document.getElementById('add_participant').getElementsByTagName('input')[0];
	var user = input.value;

	if (event.keyCode == 13)	// se è stato premuto invio
	{
		const request = new XMLHttpRequest();
	
		request.onreadystatechange = function () {
			if (this.readyState === 4 && this.status === 200)
			{
				if (this.responseText)	// se inserimento riuscito
				{
					part_list.remove();
					showParticipants(list_id);
				}
				else
				{
					input.style.border = 'solid red';
				}
			}
		};
	
		request.open('GET', '../php/addParticipant.php?list=' + list_id + '&user=' + user);
		request.send();
	}
}

/* DELETE A LIST */
function deleteList (list_id) {
	var confirm = window.confirm('Stai per eliminare la lista. Vuoi farlo veramente?');

	if (confirm)	// se è stato premuto 'OK'
	{
		const request = new XMLHttpRequest();

		request.onreadystatechange = function () {
			if (this.readyState === 4 && this.status === 200)
			{
				if (this.responseText)	// eliminato tutto con successo
				{
					window.location.href = '../php/personal_page.php';
				}
				else
				{
					window.alert('Impossibile eliminare la lista in questo momento.');
					location.reload();
				}
			}
		};

		request.open('GET', '../php/deleteList.php?list_id=' + list_id);
		request.send();
	}
}

/* ADD ELEMENTS */
function addElementsPopUp (list_id) {
	var container = document.getElementsByClassName('form_container')[0];

	if (container.classList.contains('inactive'))
		container.classList.remove('inactive');
	else
		container.classList.add('inactive');
}

function addElemValidation (event) {
	event.preventDefault();
	var isValid = true;

	var form_fields = document.getElementsByClassName('form_field');

	for (var i = 0; i < form_fields.length - 1; i++)
	{
		if (form_fields[i].value === '')
		{
			form_fields[i].style.borderColor = 'red';

			isValid = false;
		}
		else
		{
			form_fields[i].style.borderColor = '';
		}
	}

	if (isValid)
		document.getElementById('addElem_form').submit();
}

/* CHANGE LIST ELEMENTS */
function updateQuantity (event, list_id) {
	var list_item = event.target.parentNode;
	var element = list_item.getElementsByTagName('span')[0].textContent;
	var quantity = list_item.getElementsByTagName('span')[1].textContent;
	var operator = event.target.textContent;
	var operation = '';		// varia in base a operator e quantity

	quantity = Number(quantity.slice(1));	// devo eliminare la x

	if (operator == '-' && quantity == 1)
		operation = 'remove';
	else if (operator == '-' && quantity > 1)
		operation = 'decrement';
	else if (operator == '+' && quantity < 50)
		operation = 'increment';
	else	// impedisco di incrementare oltre 50
		return;

	const request = new XMLHttpRequest();
	
	request.onreadystatechange = function () {
		if (this.readyState === 4 && this.status === 200)
		{
			if (this.responseText)	// se operazione andata a buon fine
			{	
				if (operation == 'remove')
				{
					list_item.remove();
				}
				else
				{
					if (operation == 'decrement')
						quantity--;
					else if (operation == 'increment')
						quantity++;
			
					list_item.getElementsByTagName('span')[1].textContent = 'x' + quantity;
				}
			}
		}
	};
	
	request.open('GET', '../php/updateQuantity.php?list_id=' + list_id + '&elem=' + element + '&operation=' + operation);
	request.send();
}

/* BLOCK or FREE LIST */
function changeStatus (list_id) {
	const request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		if (this.readyState === 4 && this.status === 200)
		{
			if (this.responseText)
				window.alert('È già andato ' + this.responseText + ' a fare la spesa.');

			location.reload();
		}
	}

	request.open('GET', '../php/changeListStatus.php?list_id=' + list_id);
	request.send();
}