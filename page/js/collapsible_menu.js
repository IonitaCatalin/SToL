const checkBox=document.querySelector('#collapsible')
const innerMenu=document.querySelector('.collapsible-content');

function collapseOrExtend()
{
    if(checkBox.checked==true)
    {
        innerMenu.style.visibility='visible';
    }
    else
    {
        innerMenu.style.visibility='hidden';
    }
}
checkBox.addEventListener('click',e=>{
    collapseOrExtend();
});

collapsibleReduced=document.querySelector('#col-btn-logout').onclick=function() { window.location = "http://localhost/ProiectTW/page/login"; };