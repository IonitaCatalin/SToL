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
    folder_parents.push(current_folder);
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
                // render data
                console.log('Am incarcat datele pentru ' + current_folder);
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

}

window.onload = function(){  
    loadFiles();
}