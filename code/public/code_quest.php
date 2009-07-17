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

        $code_quest = $this->quest_switch();

        parent::construct($code_quest);
    }

   /**
    * decides which page to display
    *
    * @return array
    */
    public function quest_switch() {


        if ($this->player->quest == '0') {
                if(isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $quest_switch = $this->quest_start();
                    return $quest_switch;
                }
            $quest_switch = $this->quest_select();
            return $quest_switch;
        }

        $quest_switch = $this->quest_log();
        return $quest_switch;
    }

   /**
    * generates quest selection page
    *
    * @return array
    */
    public function quest_select($message='') {
        $quest_html = '';
        $questq = $this->db->execute("SELECT * FROM `quests`");
        if($questq) while($quest = $questq->fetchrow()) $quest_html .= $this->skin->quest_row($quest);

        $quest_code = $this->skin->quest_select($quest_html, $message);
        return $quest_code;
    }

   /**
    * allows user to start quest
    *
    * @return array
    */
    public function quest_start() {
        $id = $_GET['id'];
        $questq = $this->db->execute("SELECT `id` FROM `quests` WHERE `id`=?",array($_GET['id']));
        if($questq->numrows()==1) {
        $this->db->execute("UPDATE `players` SET `quest`=? WHERE `id`=?",array($_GET['id']."{start:".time()."}",$this->player->id));
        $quest_code = $this->quest_log();
        return $quest_code;
        } else {
        $quest_code = $this->quest_select($this->skin->lang_error->quest_not_found);
        return $quest_code; }
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
        $this->next_event = $this->args['last']+$event['duration'];
        if($event->jump) {
                if(time()>=$this->args['last']+$event['duration']) {
                        $this->args['last'] = $this->args['last']+$event['duration'];
                        $this->current = $event->jump;
                        }
                if($this->location==$this->current) return $quest_html;
                if($event->jump=="{{END}}") {
                        $this->db->execute("UPDATE `players` SET `quest`='0' WHERE `id`=?",array($this->player->id));
                        return $quest_html; }
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
        $code = $this->db->GetOne("SELECT `quest` FROM `players` WHERE `id`=?",array($this->player->id));
        preg_match("/([0-9]+)(?::([0-9]+))?(?:\{([a-z0-9]+:[a-z0-9]+;?)\})?/is",$code,$this->args);
        foreach($this->args as $a=>$b) {
        if($a<3) continue;
        $c = explode(":",$b);
        $this->args[($c[0])] = $c[1]; }
        $this->args['last'] = $this->args['start'];

        $file = 'quests/quest-'.$this->args[1].'.xml';
        if(file_exists($file)) $quest = simplexml_load_file($file);
        else {
                $this->db->execute("UPDATE `players` SET `quest`='0' WHERE `id`=?",array($this->player->id));
                header('location:index.php?page=quest');
                }

        $this->location = $quest->start;
        $this->current = ($args[2]?$args[2]:$quest->start);

        foreach($quest->event as $event) {
	        foreach($event->attributes() as $a=>$b) $event[$a]=$b;
        $this->events->$event['id'] = $event;
        }

        $quest_html = $this->event_jump($this->location);
        $quest_html = str_replace("{{USER}}",$this->player->username,$quest_html);

        return $this->skin->quest($quest,$this->next_event-time(),$quest_html);
    }

}
?>