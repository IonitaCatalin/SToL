const saveButton = document.getElementById('admin_save_button');
const backButton = document.getElementById('admin_back_button');
const exportButton = document.getElementById('export-csv-btn');

function toggleAlert(message = null, error = true, disable = false)
{
	const alert = document.querySelector('.alert');
	const alertText = document.getElementById('alert-text');

	if(disable == true) {
		alert.style.display = 'none';
		alertText.innerHTML = '';
	} else {
		alert.style.display = 'block';
		if(error) {
			alert.style.backgroundColor = '#f44336';
		} else {
			alert.style.backgroundColor = '#33cc33';
		}
		alertText.innerHTML = message;
	}

}

function getCookieValue(name) {
	var value = "; " + document.cookie;
	var parts = value.split("; " + name + "=");
	if (parts.length == 2)
		return parts.pop().split(";").shift();
}

function fetchData()
{
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'http://localhost/ProiectTW/api/admin/users');
	xhr.setRequestHeader('Content-type', 'application/json');
	xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
	xhr.send();

	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4)
		{
			const response = JSON.parse(xhr.responseText);
 
			if(response.status=='success' && xhr.status==200)
			{
				const users_data = JSON.parse(response.data);
				renderTableRows(users_data);
			}
			else {
				console.log('Nu am incarcat datele !!!');
			}

		}
	}
	
	// apply listeners
	applyListeners();
}

function renderTableRows(users)
{
	let table = document.getElementById("tableBody");
	tableBody.innerHTML = '';
	var rowCounter = 0;

	users.forEach (
		function(element)
		{
			let row = table.insertRow(rowCounter);
			rowCounter++;
			let cell0 = row.insertCell(0);
			let cell1 = row.insertCell(1);
			let cell2 = row.insertCell(2);
			let cell3 = row.insertCell(3);
			let cell4 = row.insertCell(4);
			let cell5 = row.insertCell(5);
			let cell6 = row.insertCell(6);

			let checkbox = document.createElement('input');
			checkbox.setAttribute("type", "checkbox");
			checkbox.setAttribute("value", element['username']);
			cell0.appendChild(checkbox);

			cell1.innerHTML = element['username'];
			cell2.innerHTML = element['email'];
			cell3.innerHTML = element['number'];
			cell4.innerHTML = element['onedrive'];
			cell5.innerHTML = element['dropbox'];
			cell6.innerHTML = element['googledrive'];
		}
	);
}

function download_csv(users) {
	console.log('Download csv file');
	console.log(users);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://localhost/ProiectTW/api/admin/download_csv');
	xhr.setRequestHeader('Content-type', 'application/json');
	xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
	const params = { users: users }
	xhr.send(JSON.stringify(params));

	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4) {

			console.log(xhr.responseText);
			const response = JSON.parse(xhr.responseText);

			if(xhr.status == 200 && response.status == 'success')
			{
				toggleAlert(response.message, false);
				//console.log(response.data.url);
				const url = response.data.url;
				const a = document.createElement('a');
				a.style.display = 'none';
				a.href = url;
				a.click();
				toggleAlert(null, null, true);
			}
			else {
				toggleAlert(response.message, true);
			}
		}
	}
}

function applyListeners()
{
	document.getElementById('export-csv-btn').onclick = function()
	{
		let users = new Array();
		var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked:not([id="select-all"])');
		for (var checkbox of checkboxes) {
			users.push(checkbox.value);
		}
		download_csv(users);
	}

	document.getElementById('select-all').onclick = function()
	{
		let users = new Array();
		var checkboxes = document.querySelectorAll('input[type="checkbox"]:not(:checked)');
		for (var checkbox of checkboxes) {
			checkbox.checked = true;
		}
	}

	//saveButton.addEventListener('click', updateData);
}



document.addEventListener("DOMContentLoaded", fetchData);


