// Shows or hides an element, depending on its current state
function showHide(id, ida) {
el = document.getElementById(id);
em = document.getElementById(ida);
if(el.style.display=="none") {
        el.style.display = "block";
        em.innerHTML = "Hide";
    } else {
        el.style.display = "none";
        em.innerHTML = "Reply";
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


function resizeTextarea(t) {
  if ( !t.initialRows ) t.initialRows = t.rows;

  a = t.value.split('\n');
  b=0;
  for (x=0; x < a.length; x++) {
    if (a[x].length >= t.cols) b+= Math.floor(a[x].length / t.cols);
  }

  b += a.length;
  userAgentLowerCase = navigator.userAgent.toLowerCase();
  if (userAgentLowerCase.indexOf('opera') != -1) b += 2;

  if (b > t.rows || b < t.rows)
    t.rows = (b < t.initialRows ? t.initialRows : b);
}