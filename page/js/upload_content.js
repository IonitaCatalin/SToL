var backdrop = document.querySelector('.backdrop');
var modal = document.querySelector('.upload-modal');
var uploadButton = document.querySelector('#btn-upload');
var closeModalButton = document.querySelector('#modal-btn-close');
var dropArea=document.querySelector('#modal-file-drop');
var fileSelector=document.querySelector('#file-selector');  
var chooseFilesButton=document.querySelector('#modal-btn-files');
var addFolder=document.querySelector('#btn-new-folder');
var uploadFilesButton=document.querySelector('#modal-btn-upload');
var files;8

let isOpened=false;

uploadButton.onclick=function(){
    backdrop.style.opacity="1";
    backdrop.style.visibility='visible';
    modal.style.opacity='1';
    modal.style.visibility='visible';
}
uploadFilesButton.onclick=function()
{
    for(i=0;i<files.length;i++)
    {
        uploadSingleFile(files[i]);
    }
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
     let container=document.createElement('div');
     container.className='empty-folder';
     let img=document.createElement('img');
     img.src='images/empty-folder.svg';
     let text=document.createElement('p');
     text.innerHTML = 'New Folder';
     container.appendChild(img);
     container.appendChild(text);
     document.querySelector('.main-container').appendChild(container);
 }

chooseFilesButton.onclick=function()
{
    fileSelector.click();
    console.log(fileSelector.files);
    fileSelector.onchange=function(event){
        let files=fileSelector.files;
        handleFiles(files);
    }
}
dropArea.onclick=function(){
    fileSelector.click();
    fileSelector.onchange=function(event){
        files=fileSelector.files;
        handleFiles(files);
    }
}

dropArea.addEventListener('dragenter',preventDefaults,false);
dropArea.addEventListener('dragover',preventDefaults,false);
dropArea.addEventListener('dragleave',preventDefaults,false);
dropArea.addEventListener('drop',preventDefaults,false);

function getCookieValue(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2)
        return parts.pop().split(";").shift();
}

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

function uploadSingleFile(file)
{
    const uploadBody = {
        filename:file.name,
        filesize:file.size
    }
    fetch('http://localhost/ProiectTW/api/upload/67b6e87381a8fb18c96c7acca3b6c35d',
    {method:'post',body:JSON.stringify(uploadBody), headers:{
        'Content-Type': 'application/json',
        'Authorization':'Bearer ' + getCookieValue('jwt_token')}}
        )
        .then(res=>res.json()
                    .then(data=>{
                        if(res.status=-200)
                        {
                            sendFileByChunks(file,0,data.data.chunk,data.data.url);
                        }
                        else if(res.status==409)
                        {
                            //Nume deja luat
                        }
                        else if(res.status==400)
                        {
                            //Parent id nu e bun
                        }
                    }));   
}

function sendFileByChunks(file,start,chunkSize,url)
{
    if(start<file.size)
    {
        const chunk=file.slice(start,start+chunkSize);
        console.log("Start:"+start+"/"+"End:"+(start+chunkSize));
        fetch(url, {method: 'put', body: chunk})
        .then(res => {
            console.log(res.status);
            if(res.status==200)
            {
                sendFileByChunks(file,start+chunkSize,chunkSize,url);
            }
            else if(res.status==201)
            {
                
            }
            else if(res.status==413)
            {
                
            }
           
        })
    }
}

function previewFileOnUp(file)
{
    var reader=new FileReader();
    reader.fileName=file.name;
    reader.fileSize=file.size;
    reader.readAsDataURL(file)
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