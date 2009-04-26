<?php
  //function edit_profile_page()
    ($this->player->show_email) ? $showemail = "checked='checked'" : ""; 

$skinlist = "";
$skinsq = $this->db->execute("SELECT * FROM `skins`");
    while($skins = $skinsq->fetchrow()) {
    if($this->player->skin == $skins['id']) $extra=" selected=\"selected\""; else $extra="";
    if($this->player->skin == $skins['id']) $myskinimage=$skins['image'];

$extra .= " style=\"background-image:url(http://www.gregoland.uni.cc/content/images/icons/" . $skins['image'] . ".png);background-position:2px 4px;background-repeat:no-repeat;padding: 4px 20px 4px 20px;\"";

    $skinlist .= "<option value=\"".$skins['id']."\"".$extra.">".$skins['name']."</option>";
    }

$genderlist = "";
($this->player->gender=="Unknown") ? $genderlist.="<option selected=\"selected\">Unknown</option>" : $genderlist.="<option>Unknown</option>\n";
($this->player->gender=="Male") ? $genderlist.="<option selected=\"selected\">Male</option>" : $genderlist.="
<option>Male</option>\n";
($this->player->gender=="Female") ? $genderlist.="<option selected=\"selected\">Female</option>" : $genderlist.="<option>Female</option>\n";
($this->player->gender=="Neuter") ? $genderlist.="<option selected=\"selected\">Neuter</option>" : $genderlist.="<option>Neuter</option>\n";

$this->html .= "
<fieldset>
<legend><a href=\"javascript:show_notes('edit1')\">Edit Profile</a></legend>
<div id=\"edit1\"><form action='index.php?page=editprofile&amp;action=profile' method='post'>
<table><tr><td>

<!-- LHS -->
<table cellpadding=\"4\">
<tr><td>Username:</td><td><strong>".$this->player->username."</strong></td></tr>

<tr><td>Email:</td><td><input type='text' name='email' value='".$this->player->email."' /></td></tr>

<tr><td>Show Email:</td><td><input type='checkbox' name='showemail' ".$showemail." /></td></tr>

<tr><td>Description:</td><td><input type='text' name='description' value=\"".$this->player->description."\"/></td></tr>

<tr><td>Avatar URL:</td><td><input type='text' name='avatar' value='".$this->player->avatar."'/></td></tr>

<tr><td colspan=\"2\"><input type='submit' value='Submit' /></td></tr>
</table></td>

<!-- RHS -->
<td>
<table cellpadding=\"4\">
<tr><td>Gender:</td><td><select name=\"gender\">".$genderlist."</select></td></tr>

<tr><td>MSN Address:</td><td><input type='text' name='msn' value=\"".$this->player->msn."\"/></td></tr>

<tr><td>AIM Address:</td><td><input type='text' name='aim' value=\"".$this->player->aim."\"/></td></tr>

<tr><td>Skype ID:</td><td><input type='text' name='skype' value=\"".$this->player->skype."\"/></td></tr>
</table>
</td></tr></table>
</form></div>
</fieldset>


<fieldset>
<legend><a href=\"javascript:show_notes('edit2')\">Change Password</a></legend>
<div id=\"edit2\" style=\"display:none;\"><form action='index.php?page=editprofile&amp;action=password' method='post'>
<table cellpadding=\"4\">

<tr><td><input type='hidden' name='id' value='".$this->player->id."'/></td><td></td></tr>

<tr><td>Current password:</td><td>
<input type='password' name='current' style=\"background-image:url(http://www.gregoland.uni.cc/content/images/icons/key.png);background-repeat:no-repeat;padding-left:16px;\" />
</td></tr></table>

<div class=\"bblue\"><table><tr><td>New password:</td><td><input type='password' name='new' /></td></tr>

<tr><td>Confirm new Password:</td><td><input type='password' name='confirm' /></td></tr></table></div>

<table><tr><td colspan=\"2\"><input type='submit' value='Submit'/></td></tr>
</table>
</form></div>
</fieldset>

<fieldset>
<legend><a href=\"javascript:show_notes('edit3')\">Display Options</a></legend>
<div id=\"edit3\" style=\"display:none;\"><form action='index.php?page=editprofile&amp;action=disp' method='post'>
<table cellpadding=\"4\">

<tr><td style=\"line-height:24px;\">Skin:</td><td><select name='skin' style=\"background-image:url(http://www.gregoland.uni.cc/content/images/icons/".$myskinimage.".png);background-position:2px 4px;background-repeat:no-repeat;padding-left:24px;\">
".$skinlist."
</select></td></tr>

<tr><td colspan=\"2\"><input type='submit' value='Submit' /></td></tr>
</table>
</form></div>
</fieldset>
";
    $this->final_output();
?>
