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

// Update multiple checkboxes at once
function update_multiple(name, checked) {
    d = document.getElementsByName(name+'[]');
    for(i=0;i<d.length;i++) {
        d[i].checked = checked;
    }
}

// Update parent of multiple checkboxes
function update_multiple_parent(parent, children) {
    el = document.getElementById(parent);
    d = document.getElementsByName(children+'[]');
    for(i=0;i<d.length;i++) {
        if(d[i].checked==false) { el.checked = false; return false; }
    }
    el.checked = true;
}

function startQuestCountdown() {
    setInterval(questCountdown,1000);
}

function questCountdown() {
    a = document.getElementById('quest-countdown');
    b = document.getElementById('quest-countdown-container');
    a.innerHTML = (a.innerHTML - 1);
    if(a.innerHTML<=0)
        b.innerHTML = '<a href="index.php?page=quest">Next event...</a>';
}

function popup_help(id) {
    window.open('popup_help.php?id='+id, 'popup_help', 'width=400,height=300');
}