<?php
/**
 * Quest system
 *
 * @author grego
 * @package code_public
 */
class code_quest extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_quest");

        $code_quest = $this->quest_log();

        parent::construct($code_quest);
    }

   /**
    * parses the next event on the trail
    *
    * @param string $id next event id
    * @return string html
    */
    public function event_jump($id) {
        $event = $this->events->$id;

        if($event->encounter) {
                $encounter = $this->event_encounter($event->encounter);
                $event->body = $this->skin->encounter($encounter, $event->body, $this->player->username);
                $event->jump = $encounter['jump'];
                }

        if($event->challenge) {
                $challenge = $this->event_challenge($event->challenge);
                $event->body = $this->skin->challenge($challenge, $event->body, $this->player->username);
                $event->jump = $challenge['jump'];
                }

        $quest_html .= $this->skin->render_event($event->title,$event->body);
        if($event->jump) {
                if($this->location==$this->current) return $quest_html;
                if($event->jump=="{{end}}") return $quest_html;
                $this->location = $event->jump;
                $quest_html .= $this->event_jump($this->location);
                }
	return $quest_html;
    }

   /**
    * performs encounter
    *
    * @param array $encounter details
    * @return array
    */
    public function event_encounter($encounter) {
        if(!isset($this->battle)) {
                $this->battle = new code_common_battle;
                $this->battle->player = $this->player;
	        $this->battle->db = $this->db;
        }

        foreach($encounter->combatant as $combatant) {
                foreach($combatant->attributes() as $a=>$b) {
                	$a = (string) $a;
                	$enemy->$a = $b;
                	}
		$enemy->username = $combatant[0];
                $enemies[] = strval($enemy->username);
        	$ret = $this->battle->battle($enemy,array('returnval'=>'details'));
        	if(!$ret->victory) {$failed=1;break;}
        }
        $final = ($failed?$encounter->failure:$encounter->success);
        $final->enemies = $this->skin->enemy_list($enemies);
        $final->success = ($failed?0:1);
        return $final;
    }

   /**
    * performs skill challenge
    *
    * @param array $challenge details
    * @return array
    */
    public function event_challenge($challenge) {
        $s = $challenge['source'];
        $check = $this->player->$s+rand(0,10);
        $ret = ($check>=$challenge['value']?$challenge->success:$challenge->failure);
        $ret->source=$challenge['source'];$ret->value=$challenge['value'];
        $ret->result = $check;
        return $ret;
    }

   /**
    * gets skin
    *
    * @return string html
    */
    public function quest_log() {
        $quest = simplexml_load_file('./././quest-testing-facility/quest.xml');

        $this->location = $quest->start;
        $this->current = "gr";

        foreach($quest->event as $event) {
	        foreach($event->attributes() as $a=>$b) $event[$a]=$b;
        $this->events->$event['id'] = $event;
        }

        $quest_html = $this->event_jump($this->location);
        $quest_html = str_replace("{{USER}}",$this->player->username,$quest_html);

        return $this->skin->quest($quest,$quest_html);
    }

}
?>