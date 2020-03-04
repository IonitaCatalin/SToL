
let backdrop = document.querySelector('.backdrop');
let modal = document.querySelector('.upload-modal');
let uploadButton = document.querySelector('.btn-upload');
let closeModalButton = document.querySelector('#modal-btn-close');
let mainContainer = document.querySelector('.grid-wrapper');
let dropArea=document.querySelector('#modal-file-drop');

let isOpened=false;

uploadButton.onclick=function(){
    backdrop.style.display = 'block'; 
    modal.style.display = 'grid'
}
closeModalButton.onclick=function(){
    backdrop.style.display='none';
    modal.style.display='none';
}
// dragArea.onclick=function(){
//     document.getElementById('file-selector').click();
// }


dropArea.addEventListener('dragenter',preventDefaults,false);
dropArea.addEventListener('dragover',preventDefaults,false);
dropArea.addEventListener('dragleave',preventDefaults,false);
dropArea.addEventListener('drop',preventDefaults,false);

function preventDefaults(e)
{
    e.preventDefault();
    e.stopPropagation();
}

dropArea.addEventListener('drop',handleDrop,false);

function handleDrop(e)
{
    let dt=e.dataTransfer;
    let files=dt.files;
    handleFiles(files);
}

function handleFiles(files)
{
    files=[...files];
    files.forEach(previewFile);
}

function previewFile(file)
{
    let reader=new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend=function(){
        let img=document.createElement('img');
        img.src=reader.result;
        document.getElementById('modal-file-drop').appendChild(img);
        img.width=50;
        img.height=50;
        
    }
}






