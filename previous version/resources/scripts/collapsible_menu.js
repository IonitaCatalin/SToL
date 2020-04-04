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