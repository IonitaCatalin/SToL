
let backdrop = document.querySelector('.backdrop');
let modal = document.querySelector('.upload-modal');
let uploadButton = document.querySelector('.btn-upload');
let closeModalButton = document.querySelector('#modal-btn-close');
let mainContainer = document.querySelector('.grid-wrapper');
let dropArea=document.querySelector('#modal-file-drop');
let fileSelector=document.querySelector('#file-selector');  

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
            parent.firstChild.remove();
}
dropArea.onclick=function(){
    fileSelector.click();
    fileSelector.onchange=function(event){
        var files=fileSelector.files;
        document.getElementById('info-text').style.display="none";
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
    document.getElementById('info-text').style.display="none";

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
        var infoDiv=document.createElement('div');
        var extension=document.createElement('p');
        var fileName=document.createElement('p');
        var fileSize=document.createElement('p');
        infoDiv.style.display="inline-block";
        extension.textContent="Extension:"+reader.fileName.split('.').pop();
        fileName.textContent="Name:"+reader.fileName;
        fileSize.textContent="Size:"+Math.ceil(reader.fileSize/(3*1024))+"MB";
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
        div.appendChild(previewImage);
        div.appendChild(infoDiv);
        document.getElementById('modal-file-drop').appendChild(div);
    }
}






