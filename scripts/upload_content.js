
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
    backdrop.style.display = 'block'; 
    modal.style.display = 'grid'
}
closeModalButton.onclick=function(){
    backdrop.style.display='none';
    modal.style.display='none';
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
    var element=document.createElement('img');
    element.src='icons/folder.svg';
    element.className='folder';
    document.querySelector('.main-container').appendChild(element);
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
    files.forEach(previewFile);
}

function previewFile(file)
{
    let reader=new FileReader();
    reader.fileName=file.name;
    reader.fileSize=file.size;
    reader.readAsDataURL(file);
    reader.onloadend=function(){
        var div=document.createElement('div');
        div.className="up-elem";
        var infoDiv=document.createElement('div');
        var extension=document.createElement('p');
        var fileName=document.createElement('p');
        var fileSize=document.createElement('p');
        infoDiv.style.display="inline-block";
        extension.textContent="Extension:."+reader.fileName.split('.').pop();
        fileName.textContent="Name:"+reader.fileName;
        fileSize.textContent="Size:"+Math.ceil(reader.fileSize/1024)+"KB";
        [fileName,fileSize,extension].forEach(function(arg){
            arg.style.margin="auto";
        })
        infoDiv.appendChild(fileName);
        infoDiv.appendChild(fileSize);
        infoDiv.appendChild(extension);
        var previewImage=document.createElement('img');
        div.style.backgroundColor="rgb(220,220,220)";
        div.style.height="80px";
        div.style.width="100%";
        div.style.padding="0px"
        div.style.display="grid";
        div.style.gridTemplateColumns="0.3fr 1fr";
        previewImage.src=reader.result;
        previewImage.style.height="100%";
        previewImage.style.width="100px";
        previewImage.style.borderRadius="15px";
        div.appendChild(previewImage);
        div.appendChild(infoDiv);

        document.getElementById('modal-file-drop').appendChild(div);
        document.getElementById('modal-up-list').appendChild(div.cloneNode(true));
    }
}






