
let backdrop = document.querySelector('.backdrop');
let modal = document.querySelector('.upload-modal');
let uploadButton = document.querySelector('.btn-upload');
let closeModalButton = document.querySelector('.modal-btn-close');
let mainContainer = document.querySelector('.grid-wrapper');
let dragArea=document.querySelector('.file-drag-area');

let isOpened=false;

function openModal(){
    backdrop.style.display = 'block'; 
    modal.style.display = 'grid'
}
function closeModal()
{
    backdrop.style.display='none';
    modal.style.display='none';
   
}

uploadButton.onclick=openModal;
closeModalButton.onclick=closeModal;
dragArea.onclick=function(){
    document.getElementById('file-selector').click();
}



