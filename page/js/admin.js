const saveButton = document.getElementById('admin_save_button');
const backButton = document.getElementById('admin_back_button');
const exportButton = document.getElementById('export-csv-btn');

function fetchData()
{
	//. ...

	
	// apply listeners
	applyListeners();
}

function applyListeners()
{
	document.getElementById('export-csv-btn').onclick = function()
	{
		let users = new Array();
  		var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
  		for (var checkbox of checkboxes) {
    		users.push(checkbox.value);
  		}
  		console.log(users);
	}
}


document.addEventListener("DOMContentLoaded", fetchData);

//saveButton.addEventListener('click', updateData);

