var uploadButton = document.querySelector('#btn-upload');
var closeModalButton = document.querySelector('#modal-btn-close');
var dropArea=document.querySelector('#modal-file-drop');
var fileSelector=document.querySelector('#file-selector');  
var chooseFilesButton=document.querySelector('#modal-btn-files');
var addFolder=document.querySelector('#btn-new-folder');
var uploadFilesButton=document.querySelector('#modal-btn-upload');
var files;

var isOpened=false;
var activeTransfer=false;
var currentTransferId;
var uploadedFiles;

function toggleModal(disable = false)
{
    let backdrop = document.querySelector('.backdrop');
    let modal = document.querySelector('.upload-modal');

    if(disable == true) {
        backdrop.style.opacity="1";
        backdrop.style.visibility='visible';
        modal.style.opacity='1';
        modal.style.visibility='visible';
        
    } else {
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
    console.log(files);
    uploadedFiles=0;
    activeTransfer=true;
    for(i=0;i<files.length;i++)
    {
       console.log("Upload:"+activeTransfer);  
        if(activeTransfer)
        {
            uploadSingleFile(files[i]);
        }
    }
}
closeModalButton.onclick=function(){
    if(activeTransfer==true)
    {
        if(uploadedFiles!=files.length)
        {
            activeTransfer=false;   
            fetch('http://localhost/ProiectTW/api/upload/'+currentTransferId,{method:'delete'});
            toggleModal(false);
        }
        else
        {
            toggleModal(false);
        }
    }
    else
    {
        toggleModal(false);
    }

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

//  addFolder.onclick=function(){
//      let container=document.createElement('div');
//      container.className='empty-folder';
//      let img=document.createElement('img');
//      img.src='images/empty-folder.svg';
//      let text=document.createElement('p');
//      text.innerHTML = 'New Folder';
//      container.appendChild(img);
//      container.appendChild(text);
//      document.querySelector('.main-container').appendChild(container);
//  }

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
    {
        method:'post',body:JSON.stringify(uploadBody), headers:{
        'Content-Type': 'application/json',
        'Authorization':'Bearer ' + getCookieValue('jwt_token')}}
        )
        .then(res=>res.json()
                    .then(data=>{
                        currentTransferId=data.data.url.split("/")[6];
                        console.log(currentTransferId); 
                        if(res.status=200)
                        {
                                sendFileByChunks(file,0,data.data.chunk,data.data.url);
                        }
                        else if(res.status==409)
                        {
                            console.log('aaa');
                            //Nume deja luat
                        }
                        else if(res.status==400)
                        {
                            console.log('bbb');
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
            console.log(res.text());
            console.log(res.status);
            if(res.status==200)
            {
                if(activeTransfer)
                {
                    sendFileByChunks(file,start+chunkSize,chunkSize,url);
                }
                else
                {
                    console.log('Intrerupere tranfer');
                }
            }
            else if(res.status==201)
            {
                console.log('Gata transferul');
                uploadedFiles++;
            }
            else if(res.status==413)
            {
                console.log('ccc');
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