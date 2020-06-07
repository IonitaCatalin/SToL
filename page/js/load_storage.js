const backButton = document.querySelector('#btn-back');
const searchBox = document.querySelector('#search-box');
const filesButton = document.getElementById('navbar-files');
const favoritesButton = document.getElementById('navbar-favourites');

var folder_parents = new Array();
//var loaded_items = new Array();
var favorited_items = new Map();  // map item_id - content_type

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

    backButton.onclick = function() { folder_parents.pop(); loadFiles(folder_parents[folder_parents.length - 1]); };

    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost/ProiectTW/api/items/' + current_folder);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4)
        {
            const response = JSON.parse(xhr.responseText);
            const items_data = JSON.parse(response.data);

            if(response.status=='success' && xhr.status==200) {
                console.log('Am incarcat datele pentru ' + current_folder);
                if(current_folder == ''){  
                    let root_data = items_data.shift(); // obtine prima pozitie
                    folder_parents.push(root_data.item_id);
                }
                // construiesc si un map cu itemele favorited, ma ajuta pentru meniul contextual
                favorited_items.clear(); // reset
                items_data.forEach (
                    function(element)
                    {
                        if(element["favorited"]) {
                            favorited_items.set(element["item_id"], element["content_type"]);   // map item_id - content_type
                        }
                    }
                );
                renderComponents(items_data);
            }
            else {
                console.log('NU am incarcat datele');
            }

        }
    }

    // console.log(folder_parents);
    // console.log(folder_parents.length);
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
            if(element['name'].length > 14) {
                title.textContent = element['name'].substr(0, 12) + "...";
            }
            else {
                title.textContent = element['name'];
            }

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

function searchForFileByName(name)
{
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost/ProiectTW/api/search/' + name);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4)
        {
            const response = JSON.parse(xhr.responseText);
            const items_data = JSON.parse(response.data);

            if(response.status=='success' && xhr.status==200) {
                renderComponents(items_data);
            }
            else {
                console.log('Nu s-au putut obtine rezultatele cautarii dupa nume');
            }
        }
    }
}

function loadFavoritedItems()
{
    console.log("LOADED favorites");

    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost/ProiectTW/api/favorites/get');
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4)
        {
            const response = JSON.parse(xhr.responseText);
            const items_data = JSON.parse(response.data);

            if(response.status=='success' && xhr.status==200) {
                renderComponents(items_data);
            }
            else {
                console.log('Nu s-au putut obtine rezultatele cautarii dupa nume');
            }
        }
    }
}



window.onload = function(){  
    loadFiles();
    initializeStorageBox();
    filesButton.addEventListener('click', (e) => { loadFiles(); } );
    favoritesButton.addEventListener('click', loadFavoritedItems);
    searchBox.addEventListener('keyup', (e) => {
        const searchString = e.target.value;
        if(searchString!=='')
            searchForFileByName(searchString);
    });
}