
// Shows or hides an element, depending on its current state
function showHide(id,icon) {

el = document.getElementById(id);
if(icon!=undefined) ol = document.getElementById(icon);
if(el.style.display=="none") {
        el.style.display = "block";
        if(ol!=undefined) ol.src = "images/dropdown_open.png";
    } else {
        el.style.display = "none";
        if(ol!=undefined) ol.src = "images/dropdown_closed.png";
    }

}