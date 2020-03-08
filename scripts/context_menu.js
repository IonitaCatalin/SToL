if (document.addEventListener) {
    document.addEventListener('contextmenu', function(e) {
      alert("Context-menu"); 
      //Meniu 
      e.preventDefault();
    }, false);
  } else {
    document.attachEvent('oncontextmenu', function() {
      alert("Context-menu");
      window.event.returnValue = false;
    });
  }