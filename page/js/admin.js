const saveButton = document.getElementById('admin_save_button');
const backButton = document.getElementById('admin_back_button');
const exportButton = document.getElementById('export-csv-btn');
const gdriveButton=document.getElementById('button-gdrive');
const dropboxButton=document.getElementById('button-dropbox');
const onedriveButton=document.getElementById('button-onedrive');

var onedriveStatus;
var dropboxStatus;
var gdriveStatus;
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
				if(xhr.status==409)
				{
					toggleAlert('Your are not authorize to use the following tools under your current rights',true,false);
				}
			}

		}
	}
	let xhrServices=new XMLHttpRequest();
	xhrServices.open('GET', 'http://localhost/ProiectTW/api/admin/services');
	xhrServices.setRequestHeader('Content-type', 'application/json');
	xhrServices.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
	xhrServices.send();

	xhrServices.onreadystatechange = function() {
		if (xhrServices.readyState === 4)
		{
			const response = JSON.parse(xhrServices.responseText);
			if(response.status=='success' && xhrServices.status==200)
			{
				const services_data = response.data;
				
				onedriveStatus=services_data.onedrive;
				dropboxStatus=services_data.dropbox;
				gdriveStatus=services_data.googledrive;
				updateAvailableServices(services_data.onedrive,services_data.dropbox,services_data.googledrive)
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
function updateAvailableServices(onedrive,dropbox,googledrive)
{
	
	if(onedrive)
	{
		onedriveButton.style.backgroundColor = "red";
		onedriveButton.textContent = 'Disable';
	}
	else
	{
		onedriveButton.style.backgroundColor='#0066ff';
		onedriveButton.textContent='Enable';
		
	}
	if(dropbox)
	{
		dropboxButton.style.backgroundColor = "red";
		dropboxButton.textContent = 'Disable';
	}
	else
	{
		dropboxButton.style.backgroundColor='#0066ff';
		dropboxButton.textContent='Enable';
	}
	if(googledrive)
	{
		gdriveButton.style.backgroundColor = "red";
		gdriveButton.textContent = 'Disable';
	}
	else
	{
		gdriveButton.style.backgroundColor='#0066ff';
		gdriveButton.textContent='Enable';
	}
}

function download_csv(users) {
	console.log('Download csv file');
	console.log(users);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://localhost/ProiectTW/api/admin/download_csv');
	xhr.setRequestHeader('Content-type', 'application/json');
	xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
	const params = { users: users }
	console.log(params);
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

function changeStatusForOnedrive()
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://localhost/ProiectTW/api/admin/services/onedrive');
	xhr.setRequestHeader('Content-type', 'application/json');
	xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
	const params = { allow: !onedriveStatus }
	console.log(params);
	xhr.send(JSON.stringify(params));

	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4) {

			console.log(xhr.responseText);
			const response = JSON.parse(xhr.responseText);
			if(xhr.status == 200 && response.status == 'success')
			{
				fetchData();
			}
			else {
				toggleAlert(response.message,true,true);
			}
		}
	}
}
function changeStatusForGDrive()
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://localhost/ProiectTW/api/admin/services/googledrive');
	xhr.setRequestHeader('Content-type', 'application/json');
	xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
	const params = { allow: !gdriveStatus }
	console.log(params);
	xhr.send(JSON.stringify(params));

	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4) {

			console.log(xhr.responseText);
			const response = JSON.parse(xhr.responseText);
			if(xhr.status == 200 && response.status == 'success')
			{
				fetchData();
			}
			else {
				toggleAlert(response.message,true,true);
			}
		}
	}
}
function changeStatusForDropbox()
{
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://localhost/ProiectTW/api/admin/services/dropbox');
	xhr.setRequestHeader('Content-type', 'application/json');
	xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
	const params = { allow: !dropboxStatus }
	console.log(params);
	xhr.send(JSON.stringify(params));

	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4) {

			console.log(xhr.responseText);
			const response = JSON.parse(xhr.responseText);
			if(xhr.status == 200 && response.status == 'success')
			{
				fetchData();
			}
			else {
				toggleAlert(response.message,true,true);
			}
		}
	}
}



document.addEventListener("DOMContentLoaded", fetchData);
onedriveButton.addEventListener('click',changeStatusForOnedrive);
gdriveButton.addEventListener('click',changeStatusForGDrive);
dropboxButton.addEventListener('click',changeStatusForDropbox);


