
function getCookieValue(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2)
        return parts.pop().split(";").shift();
}


function moveItems(item_id, new_parent_id)
{
    let xhr = new XMLHttpRequest();
    xhr.open('PUT', 'http://localhost/ProiectTW/api/items/' + item_id + "/" + new_parent_id);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4)
        {
            //console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);

            if(response.status=='success' && xhr.status==200) {
                // render data
                console.log('Am mutat ' + item_id + " in " + new_parent_id);
                loadFiles(folder_parents[folder_parents.length - 1]); // reafisez datele din folderul curent
            }
            else {
                console.log('NU am reusit sa mut item-ul');
            }

        }
    }
}

function dragStart(event) {
    //console.log('start');
    event.dataTransfer.setData("text", this.id);
    //setTimeout(() => (this.className = 'invisible'), 0);
    this.style.opacity = .5;
}

function dragFileEnd() {
    //console.log('end');
    this.className = 'file';
    this.style.opacity = 1;
}

function dragFolderEnd() {
    //console.log('end');
    this.className = 'folder';
    this.style.opacity = 1;
}

function dragOver(event) {
    event.preventDefault();
    this.classList.add('hovered')
    //console.log('over');
}
function dragEnter(event) {
    event.preventDefault();
    this.classList.add('hovered');
    //console.log('enter');
}
function dragLeave() {
    this.classList.remove('hovered');
    //console.log('leave');
}
function dragDrop(event) {
    //console.log('drop');
    this.classList.remove('hovered');
    var dropped_item_id = event.dataTransfer.getData("text");
    if(dropped_item_id != this.id) {
    	if(this.id != 'btn-back') {
		    console.log("Transfer " + dropped_item_id + " in " + this.id);
		    moveItems(dropped_item_id, this.id);
		} else {
			if(folder_parents.length <= 1) {
				console.log("Esti deja in root");
				return;
			}
		    console.log("Transfer " + dropped_item_id + " in parintele parintelui? :) " + folder_parents[folder_parents.length - 2]);
		    moveItems(dropped_item_id, folder_parents[folder_parents.length - 2]);
		}
	} else {
		document.getElementById(dropped_item_id).style.opacity = 1;
	}
}

// apelat din load_storage.js de fiecare data cand se actualizeaza fisierele afisate
function drag_n_drop_apply_listeners() {

	var all_files = document.querySelectorAll('.file');
	all_files.forEach (
	    function(element)
	        {
	            element.draggable = true;
	            element.style.cursor = 'pointer';
	            element.addEventListener('dragstart', dragStart);
	            element.addEventListener('dragend', dragFileEnd);
			}
	);

	var all_folders = document.querySelectorAll('.folder');
	all_folders.forEach (
		function(element)
		{
			element.draggable = true;
			element.style.cursor = 'pointer';
			element.addEventListener('dragstart', dragStart);
			element.addEventListener('dragend', dragFolderEnd);
	        element.addEventListener('dragover', dragOver);
	        element.addEventListener('dragenter', dragEnter);
	        element.addEventListener('dragleave', dragLeave);
	        element.addEventListener('drop', dragDrop);
		}
	);

	backButton.addEventListener('dragover', dragOver);
	backButton.addEventListener('dragenter', dragEnter);
	backButton.addEventListener('dragleave', dragLeave);
	backButton.addEventListener('drop', dragDrop);
	//alert("s-au aplicat listenerele");
}