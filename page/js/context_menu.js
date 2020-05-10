const generalContextMenu = document.querySelector('.context-general-menu');
const fileContextMenu = document.querySelector('.context-file-menu');
const container = document.querySelector('.container-wrapper');
const componentsContainer=document.querySelector('.main-container');

const comp_download_opt = document.getElementById('comp_download_opt');
const comp_rename_opt = document.getElementById('comp_rename_opt');
const comp_add_fav_opt = document.getElementById('comp_add_fav_opt');
const comp_remove_opt = document.getElementById('comp_remove_opt');

var selected_item_id = null; // folder sau fisier selectat

function toggleGeneralMenu(mode = 'none', top, left) {
    generalContextMenu.style.display = mode;
    if(mode == 'show') {
        toggleFileMenu('none');
        setPosition(generalContextMenu, top, left);
    }
};

function toggleFileMenu(mode = 'none', top, left) {
    fileContextMenu.style.display = mode;
    if(mode == 'show') {
        toggleGeneralMenu('none');
        setPosition(fileContextMenu, top, left);
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
    toggleSelectedItemHighlight('off');
}

function menu_file_download(event) {
    console.log('Download file with id: ' + selected_item_id);
    //...
    document.getElementById(selected_item_id).classList.remove('context-selected');
    toggleFileMenu('none');
}

function menu_file_rename(event) {
    console.log('Rename file with id: ' + selected_item_id);
    var selected_item = document.getElementById(selected_item_id);
    //selected_item.getElementsByTagName("p")[0].innerHTML = "Hmmm...";
    //selected_item.removeChild(selected_item.getElementsByTagName("p")[0]);

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
    };
    //########

    //document.getElementById(selected_item_id).classList.remove('context-selected');
    toggleFileMenu('none');
}

function initializeFileMenu() {
    comp_download_opt.addEventListener('click', menu_file_download);
    comp_rename_opt.addEventListener('click', menu_file_rename);
}

function showGeneralMenu(event) {
    event.preventDefault();
    if (container != event.target && componentsContainer != event.target) return; // previne general menu in loc de cel de componenta
    toggleSelectedItemHighlight('off');
    console.log('am afisat meniul general');
    toggleGeneralMenu('show', event.pageY, event.pageX);
    console.log(event.pageX+' '+event.pageY);
}

function showComponentMenu(event) {
    event.preventDefault();
    console.log('am afisat meniul de componenta');

    toggleSelectedItemHighlight('off'); // elimin highlight pt penultimul lucru selectat
    selected_item_id = this.id;
    toggleSelectedItemHighlight('on');

    toggleFileMenu('show', event.pageY, event.pageX);

    console.log(event.pageX+' '+event.pageY);

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
                //element.removeEventListener('contextmenu', showGeneralMenu, true);
                element.addEventListener('contextmenu', showComponentMenu);
                element.addEventListener('click', highlighSelectedItem);
            }
    );

    initializeFileMenu();
    //alert("context menu listeners applied");
}