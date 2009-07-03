<?php
$loginerror = $a[0];

//Index page, either login or home,
//depending on whether we're logged 
//in or not

    if ($this->player->is_member == 1) {
      if ($this->player->stat_points > 0) {
      	$stathtml = "<i>You have <b>".$this->player->stat_points."</b> stat points to spend.<br />
	       <a href='index.php?page=stats'>Click Here</a> to spend them.</i>";
      } else {
	     $stathtml = '<br /><i>You currently have no stat points to spend.</i>';
      }

      $onlinelistq = $this->db->execute("SELECT id, username FROM players WHERE (last_active > (".(time()-(60*15))."))");

      $idi=0;
      $onlinelist = "";
      while($onlinelistr = $onlinelistq->fetchrow()) {
      $idi++;
      if ($idi!=1) $onlinelist .= ", ";
      $onlinelist .= "
<a href='index.php?page=profile&id=".$onlinelistr['id']."'>".$onlinelistr['username']."</a>";
      }

      $news = $this->db->execute("SELECT n.*, p.username FROM news AS n 
                            LEFT JOIN players AS p ON n.player_id=p.id
                            ORDER BY n.date DESC");
      ($news) ? print "" : "Error retrieving news.";


      while($row = $news->fetchrow()) {
        $newshtml .= "<p>" . $row['msg'] . "<br />
              - <a href='index.php?page=profile&amp;id=" . $row['player_id'] . "' class='nochange'>" 
              . $row['username'] . "</a></p><hr />";
      }

      $diff = time() - $this->player->registered;
      $age = intval(($diff / 3600) / 24);
      $percent = intval(($this->player->exp / $this->player->maxexp) * 100);
      $date = date('F j, Y, g:i a', $this->player->registered);
$this->html .= "
<table width='100%' border='0'>
<tr>
<td width='50%'>
<span style='font-weight:bold;font-size:18px;font-family:verdana;'>Welcome ".$this->player->username."</span>
<br /><br />".$this->player->description."<br />
<b>Email:</b> ".$this->player->email."<br />
<b>Registered:</b> ".$date."<br />
<b>Character Age:</b> ".$age." days<br />
<b>Wins/Losses:</b> ".$this->player->kills."/".$this->player->deaths."<br />
<br />
".$stathtml."
</td>
<td width='50%'>
<br><br><br>
<b>Level:</b> ".$this->player->level."<br />
<b>EXP:</b> ".$this->player->exp."/".$this->player->maxexp." (".$percent."%)<br />
<b>HP:</b> ".$this->player->hp."/".$this->player->maxhp."<br />
<b>Energy:</b> ".$this->player->energy."/".$this->player->maxenergy."<br />
<b>Tokens:</b> ".$this->player->gold."<br />
<br />
<b>Strength:</b> ".$this->player->strength."<br />
<b>Vitality:</b> ".$this->player->vitality."<br />
<b>Agility:</b>".$this->player->agility."<br />
</td>
</tr>
</table>
<br /><br />
<h4>Users Online:</h4>
<div class='bblue'>" . $onlinelist . "</div>
<h4>Latest News:</h4>
".$newshtml."
";
    } else {
$this->html .= "
<table width='100%' border='0'>
<tr>
<td style='width:60%;'>
Let the adventure begin!
Welcome to CHERUBQuest!
Login now to play, or <a href='index.php?page=login'>Register</a> to join the game and become an agent!
<br /><br />
<i>CHERUBQuest is the first CHERUB persistent online browser game, or PBBG. Join now to begin the adventure!
<br /><br />You can go around campus, earning tokens and stat points, hiring weapons and armour, and challenging other agents to fights in the Combat Training Centre. <br /><br />All queries and suggestions, as well as pointing out any mistakes should be directed towards the admin staff either through CHERUB Forums or through e-mail at admin@cherubquest.uni.cc
<br /><br />Train against your friends and in the process earn tokens off them, or lose them if you are defeated. Buy weapons and armour from the equipment store and use it to arm yourself against your friends!
<br /><br />Good luck agent, and good luck becoming the best on campus!</i>
</td>
<td width='40%'>
<strong>Login:</strong><br />
<form method='post' action='index.php?page=login&amp;action=login'>
Username:<br />
<input type='text' name='username' value='".$_POST['username']."' style=\"background-image:url(http://www.gregoland.uni.cc/content/images/icons/user.png);background-repeat:no-repeat;padding-left:16px;\" />
<br />Password:<br />
<input type='password' name='password' style=\"background-image:url(http://www.gregoland.uni.cc/content/images/icons/key.png);background-repeat:no-repeat;padding-left:16px;\" /><br />
<input name='login' type='submit' value='Login' /></form><br />
".$loginerror."<br />
<a href='index.php?page=login'>Click here to register!</a>
</td>
</tr>
</table>
<p>
    <a href='http://validator.w3.org/check?uri=referer'><img
        src='http://www.w3.org/Icons/valid-xhtml10-blue' border='0'
        alt='Valid XHTML 1.0 Transitional' height='31' width='88' /></a> 
 <a href='http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.cherubquest.uni.cc/'>
  <img style='border:0;width:88px;height:31px'
       src='http://jigsaw.w3.org/css-validator/images/vcss' 
       alt='Valid CSS!' /></a>
  </p>
";
    }
    $this->final_output();
?>