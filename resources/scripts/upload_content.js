let backdrop = document.querySelector('.backdrop');
let modal = document.querySelector('.upload-modal');
let uploadButton = document.querySelector('#btn-upload');
let closeModalButton = document.querySelector('#modal-btn-close');
let dropArea=document.querySelector('#modal-file-drop');
let fileSelector=document.querySelector('#file-selector');  
let chooseFilesButton=document.querySelector('#modal-btn-files');
let addFolder=document.querySelector('#btn-new-folder');

let isOpened=false;

uploadButton.onclick=function(){
   // backdrop.style.display = 'block'; 
    backdrop.style.opacity="1";
    backdrop.style.visibility='visible';
    modal.style.opacity='1';
    modal.style.visibility='visible';
}
closeModalButton.onclick=function(){
  //  backdrop.style.display='none';
    backdrop.style.opacity='0';
    backdrop.style.visibility='hidden';
    modal.style.opacity='0';
    modal.style.visibility='hidden';
    const parent=document.getElementById('modal-file-drop');
    while(parent.firstChild)
    {
            parent.firstChild.remove();
    }
    const parentReducedView=document.getElementById('modal-up-list');
    while(parentReducedView.firstChild)
    {
        parentReducedView.firstChild.remove();
    }
}

 addFolder.onclick=function(){
     var container=document.createElement('div');
     container.className='empty-folder';
     var img=document.createElement('img');
     img.src='images/empty-folder.svg';
     var text=document.createElement('p');
     text.innerHTML = 'New Folder';
     container.appendChild(img);
     container.appendChild(text);
     document.querySelector('.main-container').appendChild(container);
 }

chooseFilesButton.onclick=function()
{
    fileSelector.click();
    fileSelector.onchange=function(event){
        var files=fileSelector.files;
        handleFiles(files);
    }
}
dropArea.onclick=function(){
    fileSelector.click();
    fileSelector.onchange=function(event){
        var files=fileSelector.files;
        handleFiles(files);
    }
}

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
    files.forEach(previewFileOnUp);
}

function previewFileOnUp(file)
{
    var reader=new FileReader();
    reader.fileName=file.name;
    reader.fileSize=file.size;
    reader.readAsDataURL(file);
    reader.onloadend=function(){
        
        var div=document.createElement('div');
        var aboutFile=document.createElement('div');
        aboutFile.className="up-elem-meta";
        div.className="up-elem-list";
        var extension=document.createElement('p');
        var name=document.createElement('p');
        var size=document.createElement('p');
        var previewImg=document.createElement('img');
        extension.textContent="Extension:."+reader.fileName.split('.').pop();
        name.textContent="Name:"+reader.fileName;
        size.textContent="Size:"+Math.ceil(reader.fileSize/1024)+"KB";
        aboutFile.appendChild(name);
        aboutFile.appendChild(size);
        aboutFile.appendChild(extension);
        previewImg.src=reader.result;
        div.appendChild(previewImg );
        div.appendChild(aboutFile);
        document.getElementById('modal-file-drop').appendChild(div);
        /*
            Clonam elementul la adaugare intrucat nu putem adauga acealasi element 
            de multiple ori intr-o ierarhie copilNod-parinteNod
        */
        document.getElementById('modal-up-list').appendChild(div.cloneNode(true));
    }
}