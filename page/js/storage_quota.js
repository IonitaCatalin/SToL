function initializeStorageBox()
{
	document.getElementById("btn-disconn").onclick = function() { window.location = "http://localhost/ProiectTW/page/login"; };

	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'http://localhost/ProiectTW/api/user/storage');
	xhr.setRequestHeader('Content-type', 'application/json');
	xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
	xhr.send();

	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4)
		{
			//console.log(xhr.responseText);
			const response = JSON.parse(xhr.responseText);

			if(response.status=='success' && xhr.status==200) {
				response_data = JSON.parse(response.data);
				renderStorageBox(response_data);
			}
			else if(xhr.status==500) {
                toggleAlert(response.message, true, false);
				console.log('NU am primit storage data');
			}

		}
	}
}

function renderStorageBox(data)
{
	let gdrive_data = data.googledrive;
	if(gdrive_data.available == true) {
		document.getElementById("gdrive_used").innerHTML = (gdrive_data.used / 1073741824).toFixed(3);
		document.getElementById("gdrive_total").innerHTML = gdrive_data.total / 1073741824 + " GB";
		let percentage = (gdrive_data.used * 100 / gdrive_data.total) + "%";
		document.getElementById("quota_googledrive").style.width = percentage;
		console.log(percentage);
	} else {
		document.getElementsByClassName("storage-text")[0].style.color = "lightgray";
	}

	let onedrive_data = data.onedrive;
	if(onedrive_data.available == true) {
		document.getElementById("onedrive_used").innerHTML = (onedrive_data.used / 1073741824).toFixed(3);
		document.getElementById("onedrive_total").innerHTML = onedrive_data.total / 1073741824 + " GB";
		let percentage = (onedrive_data.used * 100 / onedrive_data.total) + "%";
		document.getElementById("quota_onedrive").style.width = percentage;
	} else {
		document.getElementsByClassName("storage-text")[1].style.color = "lightgray";
	}

	let dropbox_data = data.dropbox;
	if(dropbox_data.available == true) {
		document.getElementById("dropbox_used").innerHTML = (dropbox_data.used / 1073741824).toFixed(3);
		document.getElementById("dropbox_total").innerHTML = dropbox_data.total / 1073741824 + " GB";
		let percentage = (dropbox_data.used * 100 / dropbox_data.total) + "%";
		document.getElementById("quota_dropbox").style.width = percentage;
	} else {
		document.getElementsByClassName("storage-text")[2].style.color = "lightgray";
	}

}

