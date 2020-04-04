const contxtMenu=document.querySelector('.contxt-menu');
const componentContxtMenu=document.querySelector('contxt-component');
const mainContainer=document.querySelector('.container-wrapper');
var isContextVisible=false;
var isComponentContextVisible=false;

function waitForElementToDisplay(selector, time) {
  if(document.querySelector(selector)!=null) {
      return;
  }
  else {
      setTimeout(function() {
          waitForElementToDisplay(selector, time);
      }, time);
  }
}

function bindEventListenerToCompContext(selector)
{
  waitForElementToDisplay(selector,800);
  let component=document.querySelector(selector);
  component.addEventListener('contextmenu',e=>{
      e.preventDefault();
      setPosition({top:e.pageY,left:e.pageX});
      toggleComponentMenu("show");
      return false;
  });
}

document.querySelector('.main-container').addEventListener('contextmenu',e=>{
  e.preventDefault();
})

const toggleMenu = command=>{
    contxtMenu.style.display = command === 'show' ? 'block' : 'none';
    isContextVisible=!isContextVisible;
};
const toggleComponentMenu=command=>{
  toggleMenu('none');
  componentContxtMenu.style.display=command === 'show' ? 'block' : 'none';
  isComponentContextVisible=!isComponentContextVisible;

};

const setPosition = ({top,left})=>{
      contxtMenu.style.left=left+'px';
      contxtMenu.style.top=top+'px';
      contxtMenu.style.opacity='1';
      toggleMenu('show');
};

mainContainer.addEventListener('click',e=>{
  if(isContextVisible)
  {
    toggleMenu('none');
    contxtMenu.style.opacity='0';
  }
    
});

mainContainer.addEventListener('contextmenu',e=>{
    e.preventDefault();
    setPosition({top:e.pageY,left:e.pageX});
    return false;
});

bindEventListenerToCompContext('.file');





