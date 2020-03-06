
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
    const parent=document.getElementById('modal-file-drop');
    while(parent.firstChild)
            parent.firstChild.remove();

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
    // let reader=new FileReader();
    // reader.readAsDataURL(file);
    // reader.onloadend=function(){
    //     var div=document.createElement('div');
    //     var img=document.createElement('img');
    //     var fileName=document.createTextNode(reader.result);
    //     div.appendChild(img);
    //     div.appendChild(fileName);
    //     img.src=reader.result;
    //     img.style.height='90px';
    //     div.style='inline-block';
    //     img.style.width='90px';
    //     document.getElementById('modal-file-drop').appendChild(div);
        
    // }
    let reader=new FileReader();
    reader.fileName=file.name;
    reader.readAsDataURL(file);
    reader.onloadend=function(){
        var div=document.createElement('div');
        var fileName=document.createTextNode(reader.fileName);
        var previewImage=document.createElement('img');
        div.style.backgroundColor="rgb(220,220,220)";
        div.style.height="80px";
        div.style.width="100%";
        div.style.padding="0px "
        div.appendChild(fileName);
        document.getElementById('modal-file-drop').appendChild(div);
    }
}






