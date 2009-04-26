function show_notes(id)
{
	el = document.getElementById(id);
	if (el.style.display == 'none')
	{
		el.style.display = '';
	} else {
		el.style.display = 'none';
	}
}

function check_delete(del)
{
        var answer = confirm("Are you sure you want to delete record " +del+"?")
        if (answer){
        window.location = "?del="+del; }
}

function show_help(id)
{
        window.open("help_popup.php?id="+id, "Help Files", "width=300, height=300, directories=no, location=no, menubar=no, resizable=no, scrollbars=no, toolbar=no");
}

function checkAll() {
	count = document.inbox.elements.length;
    for (i=0; i < count; i++) 
	{
    	if(document.inbox.check.checked == 1)
    		{document.inbox.elements[i].checked = 1; document.inbox.check.checked=1;}
    	else {document.inbox.elements[i].checked = 0; document.inbox.check.checked=0;}
	}
}


function faq_highlight(mid,mcount,mcolor) {
	for (i=1; i<=mcount; i++)
		{
		if(i!=mid) document.getElementById('m' + i).className='bgrey';
		}
	mscript = "faq_dehighlight('"+mcount+"','"+mcolor+"')";
	setTimeout(mscript,5000);
}

function faq_dehighlight(mcount,mcolor) {
	for (i=1; i<=mcount; i++)
		{
		document.getElementById('m' + i).className='b'+mcolor;
		}

}




