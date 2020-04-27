
/*

    Folosim un GET pentru a obtine un .JSON de la server respectiv root.json 
    care este oarecum mounting-point-ul
    Am folosit Live Server pentru VSCode ca si sustitut de server
    Fisierele sunt generate pe baza root.json in fisierul proiectului

*/

const backBtn=document.querySelector('#btn-back');

function loadJSON(name,callback)
{
    console.log(name);
    var xobj = new XMLHttpRequest();
    xobj.overrideMimeType('application/json');
    xobj.open('GET', '../public/js/' + name + '.json',true);
    xobj.onreadystatechange=function(){
        if(xobj.readyState==4 && xobj.status=='200')
        {
            callback(xobj.responseText);
        }
    }
    xobj.send(null);
}
function loadComponents(item)
{
    loadJSON(item.getElementsByTagName('P')[0].textContent.toLowerCase(),function(response){
        let data=JSON.parse(response);
        document.querySelector('.main-container').innerHTML='';
        renderComponents(data);
    })
}
function applyListeners(folders)
{
    folders.forEach(item =>{
        item.addEventListener('dblclick',event =>{
            loadComponents(item);
        });
    })
}
window.onload=function(){  
    loadJSON('root',function(response){
    let data=JSON.parse(response);
    document.querySelector('.main-container').innerHTML='';
    renderComponents(data);
    applyListeners(document.querySelectorAll('.folder'));
})
}
backBtn.onclick=function(){  
    loadJSON('root',function(response){
    let data=JSON.parse(response);
    document.querySelector('.main-container').innerHTML='';
    renderComponents(data);
    applyListeners(document.querySelectorAll('.folder'));
})
}

function renderComponents(data)
{
    let container=document.querySelector('.main-container');
    data[0]['children'].forEach(function(element){
        let component=document.createElement('div');
        let graphics=document.createElement('img');
        let title=document.createElement('p');
        title.textContent=element['name'];
        switch(element.type)
        {
            case 'folder':
            {
                component.className='folder';
                graphics.src='../public/images/folder.svg';
                break;
            }
            case 'text-file':
            {
                component.className='file';
                graphics.src='../public/images/text-file.svg';
                break;
            }
        }
        component.appendChild(graphics);
        component.appendChild(title);
        container.appendChild(component);
    })
}
