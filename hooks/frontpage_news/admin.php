<?php

// Admin panel for frontpage_news. Allows you to change the method of integration and
// various related details.

if($_POST['form-type'] == "frontpage_news_config") {
    $updates = array('frontpage_news_mode', 'frontpage_news_title', 'frontpage_news_twitter', 'frontpage_news_rss');
    foreach($updates as $update) {
        if(isset($_POST[$update]) && !empty($_POST[$update])) $this->settings->set($update, $_POST[$update]);
    }
}

$mode    = $this->settings->get['frontpage_news_mode'];
$title   = $this->settings->get['frontpage_news_title'];
$twitter = $this->settings->get['frontpage_news_twitter'];
$rss     = $this->settings->get['frontpage_news_rss'];

$sel = array();
$sel[0] = ($mode == "twitter") ? " checked='checked'" : "";
$sel[1] = ($mode == "rss") ? " checked='checked'" : "";
$sel[2] = ($mode == "manual") ? " checked='checked'" : "";

?>
<form action='' method='post'>
<input type='hidden' name='form-type' value='frontpage_news_config' />

<p>What would you like the title of the news section to be?</p>
<input type='text' style='width:400px;' name='frontpage_news_title' value='<?=$title?>' />

<p>Where would you like the news to be taken from?</p>

<table>
<tr>
    <td style='width:20px;'><input type='radio' id='frontpage_news_mode0' name='frontpage_news_mode' value='twitter' onclick='hide_but("twitter");' <?=$sel[0]?>/></td>
    <td><label for='frontpage_news_mode0' style='margin-top:2px;'>Twitter</label></td>
</tr>

<tr>
    <td colspan='2'><div id='block_twitter' class='quest-select' style='margin:0px;'>
        <label for='frontpage_news_twitter' style='margin:0;'>Twitter account name:</label>
        <input type='text' id='frontpage_news_twitter' name='frontpage_news_twitter' value='<?=$twitter?>' style='width:200px;' />
    </div></td>
</tr>

<tr>
    <td style='width:20px;'><input type='radio' id='frontpage_news_mode1' name='frontpage_news_mode' value='rss' onclick='hide_but("rss");' <?=$sel[1]?>/></td>
    <td><label for='frontpage_news_mode1' style='margin-top:2px;'>Blog/RSS feed</label></td>
</tr>

<tr>
    <td colspan='2'><div id='block_rss' class='quest-select' style='margin:0px;'>
        <label for='frontpage_news_rss' style='margin:0;'>Feed URL (RSS or Atom):</label>
        <input type='text' id='frontpage_news_rss' name='frontpage_news_rss' value='<?=$rss?>' style='width:460px;' />
    </div></td>
</tr>

<tr>
    <td style='width:20px;'><input type='radio' id='frontpage_news_mode2' name='frontpage_news_mode' value='manual' onclick='hide_but("manual");' <?=$sel[2]?>/></td>
    <td><label for='frontpage_news_mode2' style='margin-top:2px;'>Write your own</label></td>
</tr>

<tr>
    <td colspan='2'><div id='block_manual' class='quest-select' style='margin:0px;'>
        Coming soon...
    </div></td>
</tr>

<tr>
    <td colspan='2'>
        <input type='submit' value='Save changes' />
    </td>
</tr>

</table>

</form>

<script>
function hide_but(show) {
    disableate(document.getElementById('block_twitter'), true );
    disableate(document.getElementById('block_rss'), true );
    disableate(document.getElementById('block_manual'), true );

    disableate(document.getElementById('block_'+show), false );
}

hide_but('<?=$mode?>');

function disableate(el,val) {
    try {
        el.disabled = val;
        el.style.opacity = el.disabled ? 0.5 : 1;
    }
    catch(E){}
                
    if (el.childNodes && el.childNodes.length > 0) {
        for (var x = 0; x < el.childNodes.length; x++) {
            disableate(el.childNodes[x], val);
        }
    }
}
</script>