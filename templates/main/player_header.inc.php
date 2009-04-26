<?php 
  $newmail = $this->db->execute("SELECT m.*, p.username FROM mail AS m
                            LEFT JOIN players AS p ON m.from=p.id 
                            WHERE m.to=? AND m.status=0
                            ORDER BY m.time DESC", array($this->player->id));
  
  if ($newmail->recordcount()) {
    $mailhtml = "<ul><li class='first'>Mail</li>";
    while ($mail = $newmail->fetchrow()) {
      $mailhtml .= "<li>
      <a style='background:#FCC;' href='index.php?page=mail&amp;ac
tion=read&amp;id=" . $mail['id'] . "'>
      <strong>From:</strong> " . $mail['username'] . "<br />
      <strong>Subject:</strong><br /> " . $mail['subject'] . "</a>
      </li>";
    }
    $mailhtml .= "</ul>";
  } elseif (!$newmail) {
    print "Error retrieving mail.";
  }

  $shirts = array( 'Orange Shirt', 'Orange Shirt', 'Orange Shirt',
                 'Red Shirt', 'Red Shirt', 'Red Shirt',
                 'Blue Shirt', 'Blue Shirt', 'Blue Shirt', 
                 'Grey Shirt', 'Grey Shirt', 'Grey Shirt',
                 'Navy Shirt', 'Navy Shirt', 'Navy Shirt',
                 'Black Shirt', 'Black Shirt', 'Black Shirt',
                 'White Shirt', 'White Shirt', 'White Shirt'
                );
  
  $this->player->shirt = $shirts[ $this->player->level ];

$percent = intval(($this->player->exp / $this->player->maxexp) * 100);
$header .= "
<b>Username:</b> <span style='font-size:12px;'><a href=\"index.php?page=profile&id=".$this->player->id."\" class=\"nochange\">".$this->player->username."</a></span><br />
<b>Level:</b> ".$this->player->level."<br />
<b>EXP:</b> ".$this->player->exp."/".$this->player->maxexp." (".$percent."%)<br />
<b>Health:</b> ".$this->player->hp."/".$this->player->maxhp."<br />
<b>Energy:</b> ".$this->player->energy."/".$this->player->maxenergy."<br />
<b>Money:</b> £".$this->player->gold."<br />
<b>Shirt:</b> ".$this->player->shirt."<br /><br />
".$mailhtml."
</div><div class='left-section'>
<ul>
<li><a class='header'>Game Menu</a></li><li><a href='index.php'>Home</a></li>
<li><a href='index.php?page=mail'>Mail [".unread_messages($this->player->id, $this->db)."]</a></li>
<li><a href='index.php?page=campus'><strong>Campus</strong></a></li>
<li><a href='index.php?page=globalstats'>Player Stats</a></li>
<li><a href='index.php?page=profile'>Member List</a></li>
<li><a href='index.php?page=editprofile'> Edit Profile</a></li>
</ul>
</div>";

if ($this->player->rank == 'admin') { //Do we get the admin menu?
$header .= "
<div class='left-section'>
<ul><li><a class='header'>Admin</a></li><li><a href='admin.php'>Control Panel</a></li><li><a href='admin.php?page=ticket'>Ticket Control</a></li><li><a href='admin.php?page=phpmy'>phpMyAdmin</a></li><li><a href='admin.php?page=tasks'>Task List</a></li>
</ul>
</div>";
} 

$header .= "
<div class='left-section'>
<ul>
<li><a class='header'>Other</a></li><li><a href='index.php?page=help'>Help</a></li><li><a href='index.php?page=forums'>Forums</a></li><li><a href='index.php?page=staff'>Our Staff</a></li><li><a href='index.php?page=ticket'>Tickets</a></li><li><a href='index.php?page=login'>Logout</a></li>
</ul>
";
?>
