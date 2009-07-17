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
    * main quest page
    * 
    * @param object $quest main object
    * @param string $quest_html previous stages
    * @return string html
    */
    public function quest($quest, $quest_html) {
        $html = "<h2>".$quest->title."</h2>
	By ".$quest->author."
	<div style='border:1px solid #99F;background-color:#CCF;padding:4px;margin:4px;'>".$quest->body."</div>";
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
    * @param string $user current username
    * @return string html
    */
    public function encounter($encounter, $body, $user) {
        return "<table><tr><td>".$body."<hr />
        " . $encounter[0] . "<br style=\"clear:both;\" /></td>
        <td style=\"border-left:1px solid #333;padding:4px;width:150px;height:auto;text-align:left;\">
        <strong>Enemies:</strong> ".$encounter->enemies."<br /><strong>Result:</strong> ".($encounter->success?"Won":"Lost")."
        <br /><br />".$this->gains($encounter['gold'],$encounter['experience'])."</td>
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
        return "<table><tr><td>".$body."<hr />
        ".$challenge . "<br style=\"clear:both;\" /></td>
        <td style=\"border-left:1px solid #333;padding:4px;width:150px;height:auto;text-align:right;\">
        In a <strong>".$challenge->source."</strong> check of ".$challenge->value.", ".$user." got ".$challenge->result.".
        <br /><br />".$this->gains($challenge['gold'],$challenge['experience'])."</td>
        </tr></table>";
    }

   /**
    * a list of enemies
    * 
    * @param array $enemies
    * @return string html
    */
    public function enemy_list($enemies) {
        $count = count($enemies);
        for($i=0;$i<$count;$i++) {
                if($i==$count-1) $ret .= $enemies[$i];
                else if($i==$count-2) $ret .= $enemies[$i] . " and ";
                else $ret .= $enemies[$i] . ", ";
                }
        return $ret;
    }

   /**
    * the rewards of an event
    * 
    * @param string $gold gold received
    * @param string $experience experience earned
    * @return string html
    */
    public function gains($gold, $experience) {
        return ($gold>0?"+".$gold." gold".($experience>0?"<br />":""):"")."
        ".($experience>0?"+".$experience." experience":"");
    }

}
?>
