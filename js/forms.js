/* Form Validation */
function formValidation (event) {
	event.preventDefault();
	var isValid = true;

	var input_fields = document.getElementById('signIn_form').getElementsByTagName('input');
	var err_messages = document.getElementsByClassName('err_message');

	/* name check */
	if (input_fields[0].value === '')
	{
		input_fields[0].style.borderColor = 'red';
		err_messages[0].textContent = '';

		isValid = false;
	}
	else if (/[^a-zA-Z\s]/.test(input_fields[0].value))
	{
		input_fields[0].style.borderColor = 'red';
		err_messages[0].textContent = 'Inserisci un nome valido';

		isValid = false;
	}
	else
	{
		input_fields[0].style.borderColor = '';
		err_messages[0].textContent = '';
	}

	/* surname check */
	if (input_fields[1].value === '')
	{
		input_fields[1].style.borderColor = 'red';
		err_messages[1].textContent = '';

		isValid = false;
	}
	else if (/[^a-zA-Z\s''']/.test(input_fields[1].value))
	{
		input_fields[1].style.borderColor = 'red';
		err_messages[1].textContent = 'Inserisci un cognome valido';

		isValid = false;
	}
	else
	{
		input_fields[1].style.borderColor = '';
		err_messages[1].textContent = '';
	}

	/* username check */
	if (input_fields[2].value === '')
	{
		input_fields[2].style.borderColor = 'red';
		err_messages[2].textContent = '';

		isValid = false;
	}
	else if (/\s/.test(input_fields[2].value))
	{
		input_fields[2].style.borderColor = 'red';
		err_messages[2].textContent = 'Non sono ammessi spazi';

		isValid = false;
	}
	else
	{
		input_fields[2].style.borderColor = '';
		err_messages[2].textContent = '';
	}

	/* password check */
	if (input_fields[3].value === '')
	{
		input_fields[3].style.borderColor = 'red';
		err_messages[3].textContent = '';

		isValid = false;
	}
	else
	{
		input_fields[3].style.borderColor = '';
		err_messages[3].textContent = '';
	}

	/* password confirmation check */
	if (input_fields[4].value === '')
	{
		input_fields[4].style.borderColor = 'red';
		err_messages[4].textContent = '';

		isValid = false;
	}
	else if (input_fields[4].value !== input_fields[3].value)
	{
		input_fields[4].style.borderColor = 'red';
		err_messages[4].textContent = 'Le password non corrispondono';

		isValid = false;
	}
	else
	{
		input_fields[4].style.borderColor = '';
		err_messages[4].textContent = '';
	}

	if (isValid)
	{
		document.getElementById('signIn_form').submit();
	}
}