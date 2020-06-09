var uploadButton = document.querySelector('#btn-upload');
var closeModalButton = document.querySelector('#modal-btn-close');
var dropArea=document.querySelector('#modal-file-drop');
var fileSelector=document.querySelector('#file-selector');  
var chooseFilesButton=document.querySelector('#modal-btn-files');
var uploadFilesButton=document.querySelector('#modal-btn-upload');
var redundant=document.querySelector('#redundant');

var activeTransfer=false;
var currentTransferId;
var uploadedFilesCount;
var files;

dropArea.addEventListener('dragenter',preventDefaults,false);
dropArea.addEventListener('dragover',preventDefaults,false);
dropArea.addEventListener('dragleave',preventDefaults,false);
dropArea.addEventListener('drop',preventDefaults,false);

function toggleModal(disable = false)
{
    let backdrop = document.querySelector('.backdrop');
    let modal = document.querySelector('.upload-modal');

    if(disable == true) {
        backdrop.style.opacity="1";
        backdrop.style.visibility='visible';
        backdrop.style.display='block';
        modal.style.opacity='1';
        modal.style.visibility='visible';
        
    } else {
        if(files!=null)
            files.splice(0,files.length);
        activeTransfer=false;
        document.getElementById('modal-file-drop').innerHTML='';
        backdrop.style.opacity='0';
        backdrop.style.visibility='hidden';
        modal.style.opacity='0';
        modal.style.visibility='hidden';
    }
}
uploadButton.onclick=function(){
    toggleModal(true);
}
uploadFilesButton.onclick=function()
{
    uploadedFilesCount=0;
    activeTransfer=true;
    console.log(files);
    for(i=0;i<files.length;i++)
    {
        document.getElementsByClassName('up-elem-status')[i].textContent="Uploading.Please wait";
        document.getElementsByClassName('up-elem-status')[i].style.color='#e8b910'
        
        console.log("Upload:"+activeTransfer);  
        if(activeTransfer) {
            uploadSingleFile(files[i],i);
        }
    }
}
closeModalButton.onclick=function(){
    if(activeTransfer) {
        if(uploadedFilesCount!=files.length){
            activeTransfer=false;   
            fetch('http://localhost/ProiectTW/api/upload/'+currentTransferId,{method:'delete'});
            toggleModal(false);
        }
        else {
            toggleModal(false);
        }
    }
    else {
        toggleModal(false);
    }
    const parent=document.getElementById('modal-file-drop');
    const parentReducedView=document.getElementById('modal-up-list');
    while(parent.firstChild){
        parent.firstChild.remove();
    }
    while(parentReducedView.firstChild){
        parentReducedView.firstChild.remove();
    }
}

chooseFilesButton.onclick=function()
{
    fileSelector.click();
    fileSelector.onchange=function(event){
        files=Array.from(fileSelector.files);
        handleFiles(files);
    }
}
dropArea.onclick=function(){

    fileSelector.click();
    fileSelector.onchange=function(event){
        files=Array.from(fileSelector.files);
        handleFiles(files);
    }
}

function getCookieValue(name) {
    let value = "; " + document.cookie;
    let  parts = value.split("; " + name + "=");
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
    files=Array.from(dt.files);
    handleFiles(files);
}

function handleFiles(files)
{
    files=[...files];
    files.forEach(previewFileOnUp);
}

function uploadSingleFile(file,index)
{
    const requestBody = {
        filename:file.name,
        filesize:file.size,
        mode:redundant.checked?'redundant':'fragmented'
    }
    console.log(requestBody);
    fetch('http://localhost/ProiectTW/api/upload/'+folder_parents[folder_parents.length - 1],{
        method:'post',body:JSON.stringify(requestBody), headers:{
        'Content-Type': 'application/json',
        'Authorization':'Bearer ' + getCookieValue('jwt_token')}})
        .then(function(response) {
            elemStatus=document.getElementsByClassName('up-elem-status')[index];
            if(response.status==409)
            {
                elemStatus.textContent='File names already taken in the current folder';
                elemStatus.style.color='#FF0000'
            }
            else if(response.status==500)
            {
                elemStatus.textContent='An unexpected error appeared';
                elemStatus.style.color='#FF0000';
            }
            else if(response.status==200)
            {
                response.json().then(jsonResponse=>{
                    currentTransferId=jsonResponse.data.url.split("/")[6];
                    sendFileByChunks(file,0,jsonResponse.data.chunk,jsonResponse.data.url,index);
                })
            }
        })
}


function sendFileByChunks(file,start,chunkSize,url,index)
{
    if(start<file.size)
    {
        const chunk=file.slice(start,start+chunkSize);
        console.log("Start:"+start+"/"+"End:"+(start+chunkSize));
        fetch(url, {method: 'put', body: chunk})
        .then(res => {
            elemStatus=document.getElementsByClassName('up-elem-status')[index];
            if(res.status==200) {
                if(activeTransfer) {
                    sendFileByChunks(file,start+chunkSize,chunkSize,url,index);
                }
            }
            else if(res.status==201) {
                elemStatus.textContent='Uploaded succesfully!';
                elemStatus.style.color='#094AB2'
                uploadedFilesCount++;
            }
            else if(res.status==416) {
                elemStatus.textContent='Chunk size sent is not supported by server instance';
                elemStatus.style.color='#FF0000'
            }
            else if(res.status==403)
            {
                elemStatus.textContent='Uploading a file in redundancy mode requires at least two storage services';
                elemStatus.style.color='#FF0000';
            }
            else if(res.status==400)
            {
                res.json().then(jsonResponse=>{
                    elemStatus.textContent=res.message;
                    elemStatus.style.color='#FF0000';
                })
            }
            else if(res.status==413)
            {
                elemStatus.textContent='Insufficient storage';
                elemStatus.style.color='#FF0000';
            }
           
        })
    }
}

function previewFileOnUp(file)
{
        
        var div=document.createElement('div');
        var aboutFile=document.createElement('div');
        aboutFile.className="up-elem-meta";
        div.className="up-elem-list";
        var name=document.createElement('p');
        var status=document.createElement('p');
        status.className="up-elem-status";
        name.textContent=file.name;
        name.style.textAlign='center';
        status.textContent='Ready for uploading';
        status.style.color='#00cc00';
        status.style.textAlign='center';
        status.style.fontWeight='bold';
        aboutFile.appendChild(name);
        aboutFile.appendChild(status);
        div.appendChild(aboutFile);
        document.getElementById('modal-file-drop').appendChild(div);
        /*
            Clonam elementul la adaugare intrucat nu putem adauga acealasi element 
            de multiple ori intr-o ierarhie copilNod-parinteNod
        */
        document.getElementById('modal-up-list').appendChild(div.cloneNode(true));
}