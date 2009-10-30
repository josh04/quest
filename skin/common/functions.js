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

// pops up a help window
function popup_help(id) {
    window.open('popup_help.php?id='+id, 'popup_help', 'width=400,height=300');
}

// moves some help items around in their list
function help_move(dir, id) {
    d = document.getElementById('help_order_'+id);

    if(dir=='up')
        nuVal= parseInt(d.value) - 1;
    else
        nuVal = parseInt(d.value) + 1;

    coll = document.getElementsByTagName('input');

    for(i=0;i<coll.length;i++) {
        if(coll[i].name.substr(0,11) != 'help_order[') continue;
        if(coll[i].value == nuVal) {
            coll[i].value = d.value;
            d.value = nuVal;
            hold = d.parentNode.innerHTML;
            d.parentNode.innerHTML = coll[i].parentNode.innerHTML;
            coll[i].parentNode.innerHTML = hold;
        }
    }
}
