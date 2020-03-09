const contxtMenu=document.querySelector('.contxt-menu');
const mainContainer=document.querySelector('.file-wrapper');
let isMenuVisible=false;

document.querySelector('.main-container').addEventListener('contextmenu',e=>{
  e.preventDefault();
})

const toggleMenu = command=>{
    contxtMenu.style.display = command === "show" ? "block" : "none";
    isMenuVisible=!isMenuVisible;
};

const setPosition = ({top,left})=>{
      contxtMenu.style.left=left+'px';
      contxtMenu.style.top=top+'px';
      console.log(contxtMenu.style.left);
      toggleMenu("show");
};

mainContainer.addEventListener("click",e=>{
  if(isMenuVisible)
    toggleMenu("hide");
});

mainContainer.addEventListener("contextmenu",e=>{
    e.preventDefault();
    setPosition({top:e.pageY,left:e.pageX});
    return false;
});

