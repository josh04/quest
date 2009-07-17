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

function startQuestCountdown() {
setInterval(questCountdown,1000);
}

function questCountdown() {
a = document.getElementById('quest-countdown');
b = document.getElementById('quest-countdown-container');
a.innerHTML = (a.innerHTML - 1);
if(a.innerHTML<=0) b.innerHTML = '<a href=\"index.php?page=quest\">Next event...</a>';
}