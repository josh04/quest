<?php
/**
 * 
 * Main quests skin
 *
 * @author grego
 * @package skin_public
 */
class skin_quest extends skin_common {
    
   /**
    * quest selection rows
    * 
    * @param array $quest quest details
    * @return string html
    */
    public function quest_row($quest) {
        return "<div class='quest-select'>
        <a style='float:right;' href='index.php?section=public&amp;page=quest&id=".$quest['id']."'>Start this quest!</a>
        <h3>".$quest['title']."</h3>
        Author: ".$quest['author']."<br />
        <p>".$quest['description']."</p></div>";
    }

   /**
    * quest selection page
    * 
    * @param string $quest_html quests you can choose to do
    * @return string html
    */
    public function quest_select($quest_html, $message='') {
        return "<h2>Select a Quest</h2>
        ".$message."
        <div>You can choose a quest to embark on from below.
        When on a quest, you cannot perform various other actions, such as visit other places.</div>
        ".($quest_html!=""?$quest_html:"<div class='quest-select'>There are no quests installed!</div>");
    }

   /**
    * quest log page
    * 
    * @param object $quest main object
    * @param integer $next_event time until next event
    * @param string $quest_html previous stages
    * @return string html
    */
    public function quest($quest, $next_event, $quest_html) {
        $html = "<h2>".$quest->title."</h2>
        By ".$quest->author."
        <div class='explanation'>".$quest->body."</div>
        ".($next_event<=0?"":"<div id='quest-countdown-container' style='text-align:center;'>Next event in <span id='quest-countdown'>".$next_event."</span> seconds</div>")."
        <script>startQuestCountdown();</script>";
        return $html . $quest_html;
    }

   /**
    * single event
    * 
    * @param string $title event title
    * @param string $body event description
    * @return string html
    */
    public function render_event($title, $body) {
        return "<div style='width:500px;margin:8px auto;padding:4px;border:1px solid #FF0;background-color:#FFC;'>
        <strong>".$title."</strong><br />
	".$body."
        </div>";
    }

   /**
    * an encounter
    * 
    * @param string $enconter details
    * @param string $body main event body
    * @param string $username current username
    * @return string html
    */
    public function encounter($encounter, $body, $username) {
        return "<table><tr><td>".$body."<hr />" . $encounter['main'] . "<br style=\"clear:both;\" /></td>
        <td style=\"border-left:1px solid #333;padding:4px;width:150px;height:auto;text-align:right;\">
        <div style=\"text-align:left;\"><strong>Enemies:</strong> ".$encounter['enemies']."<br /><strong>Result:</strong> ".($encounter['success']?"Won":"Lost")."</div>
        <br />".$encounter['gains']."</td>
        </tr></table>";
    }

   /**
    * a challenge
    * 
    * @param object $challenge details
    * @param string $body main event body
    * @param string $user current username
    * @return string html
    */
    public function challenge($challenge, $body, $user) {
        return "<table><tr><td>".$body."<hr />".$challenge['main'] . "<br style=\"clear:both;\" /></td>
            <td style=\"border-left:1px solid #333;padding:4px;width:150px;height:auto;text-align:right;\">
            <div style=\"text-align:left;\">In a <strong>".$challenge['source']."
            </strong> check of ".$challenge['value'].", ".$user." got ".$challenge['result'].".</div>
            <br />".$challenge['gains']."</td>
            </tr></table>";
    }

   /**
    * it's over. quit?
    * 
    * @return string html
    */
    public function finish_quest() {
        return "<div style='text-align:center;'><a href='index.php?section=public&amp;page=quest'>Finish quest</a></div>";
    }

   /**
    * Got gold?
    *
    * @param int $gold got gold?
    * @return string html
    */
    public function got_gold($gold) {
        $got_gold = "<span style='color:#00FF00;'>+".$gold." gold.</span><br />";
        return $got_gold;
    }

   /**
    * Got xp?
    *
    * @param int $xp got xp?
    * @return string html
    */
    public function got_xp($xp) {
        $got_xp = "<span style='color:#0000FF;'>+".$xp." xp.<br />";
        return $got_xp;
    }

   /**
    * Got hp?
    *
    * @param int $hp got hp?
    * @return string html
    */
    public function got_hp($hp) {
        $got_hp = "<span style='color:#FF0000;'>".$hp." hp.<br />";
        return $got_hp;
    }

   /**
    * returns the details of your current quest
    *
    * @param string $title name of quest
    * @param string $author author of quest
    * @param string $description desc of quest
    * @return string html
    */
    public function frontpage_current_quest($title, $author, $description) {
        $current_quest = "<div class='quest-select'>
        <a style='float:right;' href='index.php?section=public&amp;page=quest'>See your progress</a>
        <h3>".$quest['title']."</h3>
        Author: ".$quest['author']."<br />
        <p>".$quest['description']."</p></div>";
        return $current_quest;
    }


}
?>
