const generalContextMenu = document.querySelector('.context-general-menu');
const fileContextMenu = document.querySelector('.context-file-menu');
const folderContextMenu = document.querySelector('.context-folder-menu');
const container = document.querySelector('.container-wrapper');
const componentsContainer=document.querySelector('.main-container');

const file_download_opt = document.getElementById('file_download_opt');
const file_rename_opt = document.getElementById('file_rename_opt');
const file_add_fav_opt = document.getElementById('file_add_fav_opt');
const file_remove_opt = document.getElementById('file_remove_opt');

const folder_rename_opt = document.getElementById('folder_rename_opt');
const folder_add_fav_opt = document.getElementById('folder_add_fav_opt');
const folder_remove_opt = document.getElementById('folder_remove_opt');

var selected_item_id = null; // folder sau fisier selectat

function toggleGeneralMenu(mode = 'none', top, left) {
    generalContextMenu.style.display = mode;
    if(mode == 'show') {
        toggleFileMenu('none');
        toggleFolderMenu('none');
        setPosition(generalContextMenu, top, left);
    }
};

function toggleFileMenu(mode = 'none', top, left) {
    fileContextMenu.style.display = mode;
    if(mode == 'show') {
        toggleGeneralMenu('none');
        toggleFolderMenu('none');
        setPosition(fileContextMenu, top, left);
    }
};

function toggleFolderMenu(mode = 'none', top, left) {
    folderContextMenu.style.display = mode;
    if(mode == 'show') {
        toggleFileMenu('none');
        toggleGeneralMenu('none');
        setPosition(folderContextMenu, top, left);
    }
};

function setPosition(context, top, left) {
    context.style.left = left + 'px';
    context.style.top = top + 'px';
    context.style.display = 'block';
};

function hideMenus(event) {
    if (container !== event.target && componentsContainer != event.target) return;
    toggleGeneralMenu('none');
    toggleFileMenu('none');
    toggleFolderMenu('none');
    toggleSelectedItemHighlight('off');
}

function menu_file_download(event) {
    console.log('Download file with id: ' + selected_item_id);
    //...
    document.getElementById(selected_item_id).classList.remove('context-selected');
    toggleFileMenu('none');
}

// atat fisiere cat si foldere
function menu_item_rename(event) {
    console.log('Rename file with id: ' + selected_item_id);
    var selected_item = document.getElementById(selected_item_id);

    // https://stackoverflow.com/a/6814092
    //########
    title = selected_item.getElementsByTagName("p")[0];
    title.style.display = "none";
    text = title.innerHTML;
    input = document.createElement("input");
    input.type = "text";
    input.value = text;
    input.size = text.length;
    title.parentNode.appendChild(input);
    input.focus();
    input.onblur = function() {         // on click outside the input box
        title.parentNode.removeChild(input);    // Remove the input
        title.innerHTML = input.value == "" ? "New File" : input.value;     // Update the title
        title.style.display = "";       // Show the title again
        let xhr = new XMLHttpRequest();
        xhr.open('PATCH', 'http://localhost/ProiectTW/api/items/' + selected_item_id);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
        const params = { newname: title.innerHTML }
        xhr.send(JSON.stringify(params));

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4)
            {
                console.log(xhr.responseText);
                const response = JSON.parse(xhr.responseText);

                if(response.status=='success' && xhr.status==200) {
                    // render data
                    console.log('Am redenumit item-ul cu id ' + selected_item_id);
                    loadFiles(folder_parents[folder_parents.length - 1]); // reafisez datele din folderul curent
                }
                else {
                    console.log('NU am reusit sa redenumesc item-ul cu id ' + selected_item_id);
                }
            }
        }
    };
    //########

    toggleFileMenu('none');
    toggleFolderMenu('none');
}

// pt fisiere si foldere..
function menu_item_remove(event) {
    console.log('Remove file with id: ' + selected_item_id);
    var selected_item = document.getElementById(selected_item_id);

    let xhr = new XMLHttpRequest();
    xhr.open('DELETE', 'http://localhost/ProiectTW/api/items/' + selected_item_id);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4)
        {
            console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);

            if(response.status=='success' && xhr.status==200) {
                // render data
                console.log('Am sters item-ul cu id ' + selected_item_id);
                loadFiles(folder_parents[folder_parents.length - 1]); // reafisez datele din folderul curent
            }
            else {
                console.log('NU am reusit sa sterg item-ul cu id ' + selected_item_id);
            }
        }
    }
    toggleFileMenu('none');
    toggleFolderMenu('none');
}

function initializeFileMenu() {
    file_download_opt.addEventListener('click', menu_file_download);
    file_rename_opt.addEventListener('click', menu_item_rename);
    file_remove_opt.addEventListener('click', menu_item_remove);
}

function initializeFolderMenu() {
    folder_rename_opt.addEventListener('click', menu_item_rename);
    folder_remove_opt.addEventListener('click', menu_item_remove);
}

function showGeneralMenu(event) {
    event.preventDefault();
    if (container != event.target && componentsContainer != event.target) return; // previne aparitia celui general in loc de cel de files sau folder
    toggleSelectedItemHighlight('off');
    console.log('am afisat meniul general');
    toggleGeneralMenu('show', event.pageY, event.pageX);
    console.log(event.pageX+' '+event.pageY);
}

function showFileMenu(event) {
    event.preventDefault();
    console.log('am afisat file meniu');

    toggleSelectedItemHighlight('off'); // elimin highlight pt penultimul lucru selectat
    selected_item_id = this.id;
    toggleSelectedItemHighlight('on');

    toggleFileMenu('show', event.pageY, event.pageX);
}

function showFolderMenu(event) {
    event.preventDefault();
    console.log('am afisat folder meniu');

    toggleSelectedItemHighlight('off'); // elimin highlight pt penultimul lucru selectat
    selected_item_id = this.id;
    toggleSelectedItemHighlight('on');

    toggleFolderMenu('show', event.pageY, event.pageX);
}

function highlighSelectedItem(event) {
    toggleGeneralMenu('none');
    toggleFileMenu('none');
    toggleSelectedItemHighlight('off'); // elimin highlight pt penultimul lucru selectat
    selected_item_id = this.id;
    toggleSelectedItemHighlight('on');
}

function toggleSelectedItemHighlight(mode = 'off') {
    if(mode == 'off') {
        if(selected_item_id != null) {
            document.getElementById(selected_item_id).classList.remove('context-selected');
        }
    } else {
        document.getElementById(selected_item_id).classList.add('context-selected');
    }
}

function context_menu_apply_listeners() {

    window.addEventListener('contextmenu', event => event.preventDefault()); // hmm.. previne click dreapta pe unde n-ar trebui..

    container.addEventListener('click', hideMenus);
    container.addEventListener('contextmenu', showGeneralMenu);

    var all_files = document.querySelectorAll('.file');
    all_files.forEach (
        function(element)
            {
                element.addEventListener('contextmenu', showFileMenu);
                element.addEventListener('click', highlighSelectedItem);
            }
    );

    var all_folders = document.querySelectorAll('.folder');
    all_folders.forEach (
        function(element)
        {
            element.addEventListener('contextmenu', showFolderMenu);
            element.addEventListener('click', highlighSelectedItem);
        }
    );

    initializeFileMenu();
    initializeFolderMenu();
    //alert("context menu listeners applied");
}