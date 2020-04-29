const registerButton = document.getElementById('register_button');
const backButton = document.getElementById('back_button');

function toggleAlert(message = null, error = true, disable = false)
{
    const alert = document.querySelector('.alert');
    const alertText = document.getElementById('alert_text');

    if(disable == true) {
    	alert.style.display = 'none';
		alertText.innerHTML = '';
    } else {
    	alert.style.display = 'block';
		if(error) {
			alert.style.backgroundColor = '#f44336';
		} else {
			alert.style.backgroundColor = '#33cc33';
			document.getElementById('email_field').value = '';
    		document.getElementById('username_field').value = '';
    		document.getElementById('password_field').value = '';
		}
		alertText.innerHTML = message;
    }

}


function postRegisterData()
{
	toggleAlert(null, null, true);	// inchid cutia de eroare daca ramasese deschisa anterior

    const email = document.getElementById('email_field').value;
    const username = document.getElementById('username_field').value;
    const password = document.getElementById('password_field').value;

	const params = {
		email: email,
		username: username,
		password: password
	}

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://localhost/ProiectTW/api/user/register');
	//xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.send(JSON.stringify(params));


	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4) {
			const response = JSON.parse(xhr.responseText);

			if(xhr.status == 200 && response.status == 'success') {
				toggleAlert(response.message, false);
			}
			else {
				toggleAlert(response.message, true);
			}
		}
	}

}

//document.addEventListener("DOMContentLoaded", postRegisterData);
registerButton.addEventListener('click', postRegisterData);



