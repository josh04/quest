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

        if ($this->player->quest == '0' || $this->player->quest == '' || !isset($this->player->quest)) {
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
        $id = 't'.$id;
        $event = $this->events->$id;

        if($event->encounter) {
                if(isset($this->args['ev-'.$id])) {
                $c = explode(",",urldecode($this->args['ev-'.$id]));
                $encounter['main'] = $c[3];
                $encounter['success'] = $c[0];$encounter['jump'] = $c[1];$encounter['enemies'] = $c[2];
                } else $encounter = $this->event_encounter($event->encounter);

                $event->body = $this->skin->encounter($encounter, $event->body, $this->player->username);
                $event->jump = $encounter['jump'];
                if(!isset($this->args['ev-'.$id])) $this->args['ev-'.$id] = urlencode($encounter['success'] . "," . $encounter['jump'] . "," . $encounter['enemies'] . "," . $encounter['main']);
                }

        if($event->challenge) {
                if(isset($this->args['ev-'.$id])) {
                $c = explode(",",urldecode($this->args['ev-'.$id]));
                $challenge['main'] = $c[4];
                $challenge['jump'] = $c[0];$challenge['source'] = $c[1];
                $challenge['value'] = $c[2];$challenge['result'] = $c[3];
                } else $challenge = $this->event_challenge($event->challenge);

                $event->body = $this->skin->challenge($challenge, $event->body, $this->player->username);
                $event->jump = $challenge['jump'];
                if(!isset($this->args['ev-'.$id])) $this->args['ev-'.$id] = urlencode($challenge['jump'] . "," . $challenge['source'] . "," . $challenge['value'] . "," . $challenge['result'] . "," . $challenge['main']);
                }

        $quest_html .= $this->skin->render_event($event->title,$event->body);
        $this->update_progress();
        $this->next_event = $this->args['last']+$event['duration'];
        if($event->jump) {
                if(time()>=$this->args['last']+$event['duration']) {
                        $this->args['last'] = $this->args['last']+$event['duration'];
                        $this->current = (string) $event->jump;
                        }
                if($this->location==$this->current) return $quest_html;
                if($event->jump=="{{END}}") {
                        $this->db->execute("UPDATE `players` SET `quest`='0' WHERE `id`=?",array($this->player->id));
                        return $quest_html.$this->skin->finish_quest(); }
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
        $final['main'] = ($failed?$encounter->failure:$encounter->success);
        $final['jump'] = $final['main']['jump'];
        $final['enemies'] = $this->skin->enemy_list($enemies);
        $final['success'] = ($failed?0:1);
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
        $ret['source']=$challenge['source'];$ret['value']=$challenge['value'];
        $ret['result'] = $check;$ret['main']=$ret;
        return $ret;
    }

   /**
    * gets skin
    *
    * @return string html
    */
    public function quest_log() {
        $code = $this->db->GetOne("SELECT `quest` FROM `players` WHERE `id`=?",array($this->player->id));
        preg_match("/([0-9]+?)(?::([0-9a-z]+?))?(?:\{(.*)\})?/is",$code,$this->args);
	$this->args[3] = explode(";",$this->args[3]);
        foreach($this->args[3] as $a=>$b) {
        if($b=='') continue;
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
        $this->current = (integer) ($args[2]?$args[2]:$quest->start);

        foreach($quest->event as $event) {
	        foreach($event->attributes() as $a=>$b) $event[$a]=$b;
        $e = 't'.$event['id'];
        $this->events->$e = $event;
        }

        $quest_html = $this->event_jump($this->location);
        $quest_html = str_replace("{{USER}}",$this->player->username,$quest_html);

        return $this->skin->quest($quest,$this->next_event-time(),$quest_html);
    }

   /**
    * updates progress in players table
    *
    * @return boolean
    */
    public function update_progress() {
        $args = $this->args[1] . ":" . $this->current . "{";
        foreach($this->args as $a=>$b) {
                if(is_numeric($a)) continue;
                $args .= $a . ":" . $b . ";";
                }
        $args .= "}";
        $q = $this->db->execute("UPDATE `players` SET `quest`=? WHERE `id`=?",array($args,$this->player->id));
        return $q;
    }

}
?>