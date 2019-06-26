function move(value, progressBarId) {
    var elem = document.getElementById(progressBarId);
    var width = elem.style.width;
    var length = width.length;
    width = width.substring(0, length-1);
    var baseWidth = width;
    var id = setInterval(frame, 10);
    function frame() {
        if(baseWidth < value){
            if (width >= value) {
              clearInterval(id);
            } else {
              width++; 
              elem.style.width = width + '%'; 
            }
        } else {
            if (width <= value) {
            clearInterval(id);
          } else {
            width--;
            elem.style.width = width + '%'; 
          }
        }
    }
}