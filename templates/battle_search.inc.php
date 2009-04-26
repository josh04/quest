<?php
  //function battle_search_page($players="")
$players = ($a[0]) ? $a[0] : "";
$this->html .="
<fieldset>
	<legend><b>Search</b></legend>
	<form method='post' action='index.php?page=battle_search&amp;action=search'>
	<table width='100%'>
		<tr><td width='40%'>Username:</td>
        <td width='60%'><input type='text' name='username' /></td>
    </tr>
		<tr><td width='40%'>Level</td>
        <td width='60%'>
          <input type='text' name='fromlevel' size='4' /> 
          to 
          <input type='text' name='tolevel' size='4' />
        </td>
    </tr>
		<tr>
      <td width='40%'>Status:</td>
      <td width='60%'>
        <select name='alive' size='2'>
          <option value='1' selected='selected'>Conscious</option>
          <option value='0'>Unconscious</option>
        </select>
      </td>
    </tr>
		<tr>
      <td colspan='2'><input type='submit' value='Search' /></td>
    </tr>
	</table>
	</form>
</fieldset>
<br /><br />
<fieldset>
<legend><b>Attack a player</b></legend>
<form method='post' action='index.php?page=battle'>
	<table><tr><td>Username:</td><td><input type='text' name='username' /></td></tr>
	<tr><td colspan='2'><input type='submit' value='Battle!' /></td></tr></table>
</form></fieldset>
    ";
    if (is_object($players)){
    if ($players->recordcount() > 0) {
			$bool = 1;
			$this->html .= "<table width='100%'>";
			while ($player = $players->fetchrow()) {
				$this->html .= "<tr class='row".$bool."'><td width='50%'>
                        <a href='index.php?page=profile&amp;id=".$player['id']."'>"
                        .$player['username']."</a></td><td width='20%'>"
                        .$player['level'] . "</td><td width='30%'>
                        <a href='index.php?page=battle&amp;id="
                        .$player['id']."'>Attack</a></td></tr>";
				$bool = ($bool==1)?2:1;
			}
			$this->html .= "</table>";
		} else {
			$this->html .= "No players found. Try changing your search criteria.";
		}
		}
    $this->final_output();
?>
