<?php
  //function profile_page($profile)
  $profile = $a[0];
	  $avatar = ($profile->avatar) ? "<img src='".$profile->avatar."' alt='User Avatar'>" : "<img src='images/avatar.png' alt='User Avatar'>";
    $msn = ($profile->msn) ? "<i>MSN</i>: ".$profile->msn."<br />\n" : "";
    $aim = ($profile->aim) ? "<i>AIM</i>: ".$profile->aim."<br />\n" : "";
    $skype= ($profile->skype) ? "<i>Skype</i>: ".$profile->skype: "";

    $colour = ($profile->hp == 0) ? "<span style='color:#FF0000;'>Unconscious</span>" : "<span style='color:#008000;'>Conscious</span>";
    $email = ($profile->show_email==1) ? "<i>Email</i>: ".$profile->email."<br />\n" : "Hidden";
    $registered = date('F j, Y', $profile->registered);
    $diff = time() - $profile->registered;
    $age = intval(($diff / 3600) / 24);
    $editprofile = ($this->player->id==$profile->id) ? "<a href=\"index.php?page=editprofile\">Edit Profile</a>" : "<a href='index.php?page=battle&amp;id=".$profile->id."'>Fight</a> | 
<a href='index.php?page=profile&amp;friends=add&amp;id=".$profile->id."'>Add to friends</a>";
    $onoff = ($profile->last_active > (time()-(60*15))) ? "online" : "offline";
if($profile->id==65) $moreboxes = "<tr><td><div class=\"bblue\">
<ul style=\"padding-left:32px;font-size:12px;\">
<strong>These Award!</strong><br />
".implode("<br />\n",get_awards($profile,$db))."
</ul>
</div></td></tr>";

    if ($profile->getfriends()) {
      foreach($profile->friends as $friend) {
        $friends .= "<li><a href='index.php?page=profile&amp;id=".$friend['friend_id']."'>".$friend['username']."</a>   
<a href=\"index.php?page=profile&amp;friends=add&amp;id=".$friend['friend_id']."\" title=\"Add user as friend\"><img src=\"images/icons/user_add.png\" style=\"border:0;\" /></a>   
<a href=\"index.php?page=mail&action=compose&to=".$friend['username']."\" title=\"Send user message\"><img src=\"images/icons/email.png\" style=\"border:0;\" /></a>   
</li>";
      }
    } else {
      $friends = "<li>This user is friendless. <a href=\"index.php?page=profile&amp;friends=add&amp;id=".$profile->id."\">Become the first!</a></li>";
    }

$this->html .= "
<fieldset>
<legend><b>".$profile->username."</b>'s Profile</legend>
<div style=\"font-family:Trebuchet MS;\">

<!-- LHS -->
<table style=\"font-size:16px;\"><tr><td style=\"width:50%;\">
<table><tr><td colspan=\"2\"><strong>Basic Info</strong></td></tr>

<tr><td style='width:50%;'>Username:</td>
<td style='width:50%;'>".$profile->username." 
<a style=\"border:0\" title=\"User is ".$onoff."\"><img src=\"images/icons/status_".$onoff.".png\" /></a></td></tr>

<tr><td colspan='2'><i>".$profile->description."<i></td><br></tr>

<tr><td colspan=\"2\">".$avatar."</td></tr>

<tr><td>Gender:</td><td>".$profile->gender."</td></tr>
<tr><td>Registered:</td><td>".$registered."</td></tr>
<tr><td>In-Game Age:</td><td>".$age." days</td></tr>

<tr><td colspan=\"2\"><strong>Game Info</strong></td></tr>
<tr><td>Level:</td><td>".$profile->level."</td></tr>
<tr><td>Status:</td><td>".$colour."</td></tr>
<tr><td>Wins:</td><td>".$profile->kills."</td></tr>
<tr><td>Losses:</td><td>".$profile->deaths."</td></tr>

</table>

<!-- RHS -->
</td><td style=\"width:50%;\">
<table><tr><td>  </td></tr>
<tr><td><strong>Contact</strong></td></tr>
<tr><td><div class=\"bblue\" style=\"font-size:12px;\">
<strong>You can contact ".$profile->username." in the following ways:</strong><br />
<i><a href=\"index.php?page=mail&action=compose&to=".$profile->username."\" class=\"faq\" style=\"font-weight:normal;\">CHERUB Internal Mail</a></i><br />
".$email.$msn.$aim.$skype."
</div></td></tr>

<tr><td><strong>Friends</strong></td></tr>

<tr><td><div class=\"bcyan\">
<ul style=\"padding-left:32px;font-size:12px;\">
".$friends."
</ul>
</div></td></tr>

".$moreboxes."

</table>

</td></tr></table>
<br /><br />
<center>
".$editprofile."


<div class=\"bblue\"><form action='index.php?page=profile&amp;id=".$profile->id."' method='post'>
<input type='text' name='donate' />
<input type='submit' accesskey='S' value='Donate to ".$profile->username."' />
</form></div>

</center>
</div>
</fieldset> ";
    $this->final_output();
?>
