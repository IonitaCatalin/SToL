const backButton = document.querySelector('#btn-back');

var folder_parents = new Array();


function getCookieValue(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2)
        return parts.pop().split(";").shift();
}


function loadFiles(current_folder = '')
{

    if((folder_parents.length >= 1)  && (folder_parents[folder_parents.length - 1] != current_folder)) {
        folder_parents.push(current_folder);
    }

    backButton.onclick = function() { folder_parents.pop(); loadFiles(folder_parents.pop()); };

    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost/ProiectTW/api/items/' + current_folder);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4)
        {
            //console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            const items_data = JSON.parse(response.data);
            //console.log(items_data);

            if(response.status=='success' && xhr.status==200) {
                console.log('Am incarcat datele pentru ' + current_folder);
                if(current_folder == ''){   // cererea pt date in root
                    let root_data = items_data.shift();
                    folder_parents.push(root_data.item_id);
                }
                // render data
                renderComponents(items_data);
            }
            else {
                console.log('NU am incarcat datele');
            }

        }
    }
}


function renderComponents(data)
{

    let container = document.querySelector('.main-container');
    container.innerHTML = '';

    data.forEach (
        function(element)
        {
            let component = document.createElement('div');
            component.setAttribute("id", element['item_id']);
            let graphics = document.createElement('img');
            let title = document.createElement('p');
            title.textContent = element['name'];

            switch(element.content_type)
            {
                case 'folder':
                {
                    component.className='folder';
                    graphics.src='../page/images/folder.svg';
                    component.addEventListener('dblclick', event => { loadFiles(element['item_id']); });
                    break;
                }
                case 'file':
                {
                    component.className='file';
                    graphics.src='../page/images/text-file.svg';
                    break;
                }
            }
            component.appendChild(graphics);
            component.appendChild(title);
            container.appendChild(component);
        }
    );
    drag_n_drop_apply_listeners();   // e importanta ordinea executiei scripturilor
    context_menu_apply_listeners();     
}

window.onload = function(){  
    loadFiles();
}