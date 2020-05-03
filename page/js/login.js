const loginButton = document.getElementById('login_button');

function toggleAlert(message = null, error = true, disable = false)
{
    const alert = document.querySelector('.alert');
    const alertText = document.getElementById('alert_text');

    if(disable == true) {
    	alert.style.display = 'none';
		alertText.innerHTML = '';
    } else {
    	alert.style.display = 'block';
		alert.style.backgroundColor = '#f44336';
		alertText.innerHTML = message;
    }

}


function postLoginData()
{
	toggleAlert(null, null, true);	// inchid cutia de eroare daca ramasese deschisa anterior

    const username = document.getElementById('username_field').value;
    const password = document.getElementById('password_field').value;

	const params = {
		username: username,
		password: password
	}

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://localhost/ProiectTW/api/user/login');
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.send(JSON.stringify(params));

	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4) {
			const response = JSON.parse(xhr.responseText);
			if(xhr.status == 200 && response.status == 'success') {
				console.log('Reust');
				//toggleAlert(response.message, false);
				location.href = 'http://localhost/ProiectTW/page/files';
			}
			else {
				toggleAlert(response.message, true);
			}
		}
	}

}

loginButton.addEventListener('click', postLoginData);
