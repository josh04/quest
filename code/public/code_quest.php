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

        if($this->player->hp<=0) return $this->quest_select($this->skin->lang_error->player_currently_incapacitated);
        if($this->player->energy<=0) return $this->quest_select($this->skin->lang_error->player_no_energy);

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
                if(isset($this->args['ev-'.$id])) $encounter = $this->stat_return($this->args['ev-'.$id], array('success','jump','enemies','hp','xp','gold','main'));
                else $encounter = $this->event_encounter($event->encounter);

                $event->body = $this->skin->encounter($encounter, $event->body, $this->player->username);
                $event->jump = $encounter['jump'];
                if(!isset($this->args['ev-'.$id])) $this->args['ev-'.$id] = $this->stat_imply($encounter, array('success','jump','enemies','hp','xp','gold','main'));
                }

        if($event->challenge) {
                if(isset($this->args['ev-'.$id])) $challenge = $this->stat_return($this->args['ev-'.$id], array('jump', 'source', 'value', 'result', 'xp', 'main'));
                else $challenge = $this->event_challenge($event->challenge);

                $event->body = $this->skin->challenge($challenge, $event->body, $this->player->username);
                $event->jump = $challenge['jump'];
                if(!isset($this->args['ev-'.$id])) $this->args['ev-'.$id] = $this->stat_imply($challenge, array('jump', 'source', 'value', 'result', 'xp', 'main'));
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
        $prehp = $this->player->hp;
        if(!isset($this->fight)) {
                $this->fight = new code_fight($this->section, $this->page);
        }

        foreach($encounter->combatant as $combatant) {
                $enemy = $this->fight->create_enemy();
                foreach($combatant->attributes() as $a=>$b) {
                	$a = (string) $a;
                	$enemy->$a = $b;
                	}
		$enemy->username = $combatant[0];
                $enemies[] = strval($enemy->username);
        	$ret = $this->fight->battle($enemy,array('returnval'=>'boolean','save_gold'=>false,'save_xp'=>false));
                $this->player = $this->fight->player;
        	if($ret==false) break;
        }
        $final['main'] = ($ret==true?$encounter->success:$encounter->failure);
        $final['jump'] = $final['main']['jump'];
        $final['gold'] = $final['main']['gold'];$final['xp'] = $final['main']['xp'];
        $final['hp'] = $this->player->hp - $prehp;
        $final['enemies'] = $this->skin->enemy_list($enemies);
        $final['success'] = ($ret?true:false);
        $this->player->exp = $this->player->exp+$final['xp'];
        $this->player->gold = $this->player->gold+$final['gold'];
        $this->player_update(array('exp'=>$this->player->exp, 'gold'=>$this->player->gold));
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
        $this->player->exp = $this->player->exp+$ret['xp'];
        $this->player_update(array('exp'=>$this->player->exp));
        return $ret;
    }

   /**
    * updates player's stats
    *
    * @param array new values
    * @return boolean
    */
    public function player_update($records) {
        $this->db->AutoExecute('players', $records, 'UPDATE', $this->player->id);
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

        $file = 'quests/quest-'.md5($this->settings['quests_code'].$this->args[1]).'.xml';
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
        return ($q?true:false);
    }

   /**
    * stores some stats
    *
    * @param array $input stats to be encoded
    * @return array $titles which ones to encode
    */
    public function stat_imply($input,$titles) {
        $arr = array();
        foreach($titles as $a=>$b) {
                $arr[$a] = str_replace(",","{131}",$input[$b]);
                }
        return urlencode(implode(",",$arr));
    }

   /**
    * returns stored stats
    *
    * @param string $code encoded string with stats
    * @return array $titles stats to draw out by name
    */
    public function stat_return($code,$titles) {
        $arr = array();
        $c = explode(",",urldecode($code));
        if(count($c)!=count($titles)) return array();
        foreach($titles as $a=>$b) {
                $arr[($titles[$a])] = str_replace("{131}",",",$c[$a]);
                }
        return $arr;
    }

}
?>