
//Fisier JSON convertit in js object ,continutlu poate fi gasit in root.js
var data;
function loadJSON(name,callback)
{
    var xobj = new XMLHttpRequest();
    xobj.overrideMimeType('application/json');
    xobj.open('GET',name+'.json',true);
    xobj.onreadystatechange=function(){
        if(xobj.readyState==4 && xobj.status=='200')
        {
            callback(xobj.responseText);
        }
    }
    xobj.send(null);
}

window.onload=function(){  
    loadJSON('root',function(response){
    data=JSON.parse(response);
    renderRootComponents(data)
})
}
function renderRootComponents(data)
{
    let container=document.querySelector('.main-container');
    console.log(data[0]['children']);
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
                graphics.src='images/folder.svg';
                break;
            }
            case 'text-file':
            {
                component.className='text-file';
                graphics.src='images/text-file.svg';
                break;
            }
        }
        component.appendChild(graphics);
        component.appendChild(title);
        container.appendChild(component);
    })
}
