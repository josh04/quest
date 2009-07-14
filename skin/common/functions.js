// Shows or hides an element, depending on its current state
function showHide(id) {
el = document.getElementById(id);
if(el.style.display=="none") {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
return false;
}