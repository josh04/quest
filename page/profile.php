<?php
// profile.php : for profiles!
// Also does friends and the members list. And donations.

//Check for user ID
if ($_GET['id']) {
  $profile = new player;
  $profile->db =& $db;
	if (!$profile->get_user_by_id(intval($_GET['id']))) {
		header("Location: index.php?page=members");
  }
  if ($_POST['donate']) {
  
    if (($player->gold < abs(intval($_POST['donate'])))) {
      $display->simple_page("You don't have enough gold to donate that much.","","bred");
      exit;
    }
    
    if ($profile->id == $player->id) {
      $display->simple_page("You can't donate money to yourself.","","bred"); 
      exit;
    }
    $player->gold = $player->gold - abs(intval($_POST['donate']));
    $profile->gold = $profile->gold + abs(intval($_POST['donate']));
    $db->execute("UPDATE players SET gold=? 
                  WHERE id=?", 
                  array($profile->gold, 
                        $profile->id));
    $db->execute("UPDATE players SET `gold`=? 
                  WHERE `id`=?", 
                  array($player->gold, 
                        $player->id));
                        
    addlog($profile->id, "You have been given ".abs(intval($_POST['donate'])).
                        " gold pieces by ".$player->username.".", $db );
                        
    $display->simple_page("You have donated ".abs(intval($_POST['donate']))." 
            gold pieces to ".$profile->username.".","","byellow");
    exit;
  }
  if ($_GET['friends']){
    switch($_GET['friends']){
      case "add":
        if ($_GET['id'] == $player->id) {
          $display->simple_page("You can't add yourself as a friend.","","bred");
          exit;
        } else {
          $query = $db->execute("INSERT INTO friends (player_id, friend_id) 
                                 VALUES (?,?)", array($player->id, intval($_GET['id'])));
          $logmsg = "You have a friend request from 
                    <a href='index.php?page=profile&amp;friends=accept&amp;id="
                    .$player->id."'>".$player->username."</a>. 
                    Please click <a href='index.php?page=profile&amp;friends=accept&amp;id="
                    .$player->id . "'>here</a> to accept.";

	        addlog(intval($_GET['id']), $logmsg, $db);
          $display->simple_page("You have added ".$profile->username." as a friend.","","byellow");
        }
        include("templates/private_footer.php");
        exit;
      case "accept":
        $query = $db->execute("UPDATE `friends` 
                              SET `validated`=1 
                              WHERE `friend_id`=? 
                              AND player_id=? ", 
                              array($player->id,
                                    intval($_GET['id'])));
        $display->simple_page("You have accepted ".$profile->username."'s friend request.","","byellow");
        exit;
    }
  }
  $display->profile($profile);
  exit;
}

$limit = ($_GET['limit']) ? intval($_GET['limit']) : 30; //Use user-selected limit of players to list
$begin = ($_GET['begin']) ? abs(intval($_GET['begin'])) : $player->id - intval($limit / 2); //List players with the current player in the middle of the list

$total_players = $db->getone("SELECT count(ID) AS `count` FROM `players`");

$begin = ($begin >= $total_players) ? abs($total_players - $limit) : $begin; //Can't list players don't don't exist yet either

$begin = ($begin < 0) ? 0 : $begin;

$lastpage = (($total_players - $limit) < 0) ? 0 : $total_players - $limit; //Get the starting point if the user has browsed to the last page

$memberlist = $db->execute("SELECT `id`, `username`, `level` 
                      FROM `players` ORDER BY `level` DESC 
                      LIMIT ?,?", 
                      array($begin, $limit));

while($member = $memberlist->fetchrow()) {
  $members[] = $member;
}

$display->members($members, $begin, $limit);

?>
