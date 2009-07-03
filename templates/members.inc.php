<?php
  //function members_page($members, $begin, $limit)
  $members = $a[0];
  $begin = $a[1];
  $limit = $a[2];
  
    $previous = $begin - $limit;
    $next = $begin + $limit;
    $idi=0;
    foreach($members as $member) {
    $idi++;
    (($idi%2)==0) ? $num="1" : $num="2";
    	 if($member['id'] == $player->id){
        $name = "<b>".$member['username']."</b>";
	     } else {
        $name = $member['username']; 
       }
	   $memberhtml .= "<tr class=\"row".$num."\">
                    <td style=\"border-left:1px solid #000;\"><a href=\"index.php?page=profile&amp;id=" .$member['id']."\">
                    ".$name."</a></td><td>".$member['level']."</td>
                    <td style=\"border-right:1px solid #000;\"><a href=\"index.php?page=mail&amp;action=compose&amp;to=".$member['username']."\">
                    Mail</a> | <a href=\"index.php?page=battle&id="
                    .$member['id']."\">Battle</a>";
	   if ($player->rank == "admin") {
      $memberhtml .= "| <a href=\"editprofile.php?id=" . $member['id'] . "\">Edit Profile</a>";
	   }
	   $memberhtml .= "</td></tr>";
	 }
$this->html .= "
<a href='index.php?page=profile&amp;begin=".$previous."&amp;limit=".$limit."'>Previous Page</a> | <a href='index.php?page=profile&amp;begin=".$next."&limit=".$limit."'>Next Page</a>
<br /><br />
Show <a href='index.php?page=profile&amp;begin=".$begin."&amp;limit=5'>5</a> | <a href='index.php?page=profile&amp;begin=".$begin."&amp;limit=10'>10</a>  | <a href='index.php?page=profile&amp;begin=".$begin."&amp;limit=20'>20</a> | <a href='index.php?page=profile&amp;begin=".$begin."&amp;limit=30'>30</a> | <a href='index.php?page=profile&amp;begin=".$begin."&amp;limit=40'>40</a> | <a href='index.php?page=profile&amp;begin=".$begin."&amp;limit=50'>50</a> | <a href='index.php?page=profile&amp;begin=".$begin."&amp;limit=100'>100</a> members per page

<br /><br /><br />

<table style='width:100%;border:0;font-size:10px;' cellspacing='0'>
<tr>
<th width='30%'><b>Username</b></td>
<th width='30%'><b>Level</b></td>
<th width='40%'><b>Actions</b></td>
</tr>
".$memberhtml."
</table>
";
	 $this->final_output();

?>
