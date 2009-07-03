<?php

$cssq = $this->db->execute("SELECT * FROM `skins` WHERE `id`=?",array($this->player->skin));
$css = $cssq->fetchrow();

if(!isset($this->player->skin)) {
    $css['cssfile'] = "default.css";
}


if($this->player->id) {
  include("player_header.inc.php");
} else {  
  include("guest_header.inc.php");
}

?>
