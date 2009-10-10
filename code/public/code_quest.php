<?php
/**
 * Quest system
 *
 * @author grego
 * @package code_public
 */
class code_quest extends code_common {

    public $player_class = "code_player_rpg";

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct($code_other = "") {
        if ($code_other) {
             parent::construct($code_other);
             return;
        }  
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
        $quest_query = $this->db->execute("SELECT * FROM `quests`");
        if ($quest_query) {
            while ($quest = $quest_query->fetchrow()) {
                $quest_html .= $this->skin->quest_row($quest);
            }
        }

        $quest_code = $this->skin->quest_select($quest_html, $message);
        return $quest_code;
    }

   /**
    * allows user to start quest
    *
    * @return array
    */
    public function quest_start() {
        $id = intval($_GET['id']);

        if ($this->player->hp<=0) {
            $quest_start = $this->quest_select($this->skin->error_box($this->lang->player_currently_incapacitated));
            return $quest_start;
        }
        if ($this->player->energy<=0) {
            $quest_start = $this->quest_select($this->skin->error_box($this->lang->player_no_energy));
            return $quest_start;
        }

        $quest_query = $this->db->execute("SELECT `id` FROM `quests` WHERE `id`=?",array($id));
        if ($quest_query->numrows()==1) {
            $this->db->execute("UPDATE `players` SET `quest`=? WHERE `id`=?", array($id."{start:".time()."}",$this->player->id));
            $quest_code = $this->quest_log();
            return $quest_code;
        } else {
            $quest_code = $this->quest_select($this->skin->error_box($this->lang->quest_not_found));
            return $quest_code;
        }
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
        $stat_string = 'ev-'.$id;
        
        if ($event->encounter) {

                if (isset($this->quest_stages[$stat_string])) {
                    $encounter = $this->stat_return($this->quest_stages[$stat_string], array('success','jump','enemies','hp','xp','gold','main'));
                } else {
                    $encounter = $this->event_encounter($event->encounter);
                }
                $encounter['gains'] = $this->gains($encounter['gold'],$encounter['xp'],$encounter['hp']);
                $event->body = $this->skin->encounter($encounter, $event->body, $this->player->username);
                $event->jump = $encounter['jump'];
                
                
                if (!isset($this->quest_stages[$stat_string])) {
                    $this->quest_stages[$stat_string] = $this->stat_imply($encounter, array('success','jump','enemies','hp','xp','gold','main'));
                }
        }

        if ($event->challenge) {
                if (isset($this->quest_stages[$stat_string])) {
                    $challenge = $this->stat_return($stat_string, array('jump', 'source', 'value', 'result', 'xp', 'main'));
                } else {
                    $challenge = $this->event_challenge($event->challenge);
                }

                $challenge['gains'] = $this->gains($challenge['gold'],$challenge['xp']);
                $event->body = $this->skin->challenge($challenge, $event->body, $this->player->username);
                $event->jump = $challenge['jump'];

                if (!isset($this->quest_stages[$stat_string])) {
                    $this->quest_stages[$stat_string] = $this->stat_imply($challenge, array('jump', 'source', 'value', 'result', 'xp', 'main'));
                }
        }

        $quest_html .= $this->skin->render_event($event->title,$event->body);
        
        $this->update_progress();

        $this->quest_stages['next_event'] = $this->quest_stages['last'] + $event['duration'];
        
        if ($event->jump) {
                if (time() >= ($this->quest_stages['last'] + $event['duration'])) {
                        $this->quest_stages['last'] = $this->quest_stages['last'] + $event['duration'];
                        $this->current = $event->jump;
                }

                if ($this->quest_stages['location'] == $this->current) {
                    return $quest_html;
                }

                if ($event->jump == "{{END}}") {
                        $this->db->execute("UPDATE `players` SET `quest`='0' WHERE `id`=?", array($this->player->id));
                        return $quest_html.$this->skin->finish_quest();
                }
                $this->quest_stages['location'] = $event->jump;
                $quest_html .= $this->event_jump($this->quest_stages['location']);
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
        //Player cannot attack any more
        if ($this->player->energy == 0) {
            $this->error_page($this->lang->player_no_energy);
        }

        //Player is unconscious
        if ($this->player->hp == 0) {
            $this->error_page($this->lang->player_currently_incapacitated);
        }

        $prehp = $this->player->hp;
        if (!isset($this->fight)) {
                $this->fight = new code_fight($this->section, $this->page);
                $this->fight->player =& $this->player;
        }

        foreach($encounter->combatant as $combatant) {
            $this->fight->enemy = $this->fight->create_enemy();
            foreach($combatant->attributes() as $a=>$b) {
                $a = (string) $a;
                $this->fight->enemy->$a = $b;
            }
            $this->fight->enemy->username = $combatant[0];
            $enemies[] = strval($this->fight->enemy->username);

            $this->fight->return_value = 'boolean';
            $this->fight->save_gold = false;
            $this->fight->save_xp = false;
            $this->fight->use_energy = false;
            $battle_result = $this->fight->battle();
            if (!$battle_result) {
                break;
            }
        }

        if ($battle_result == true) {
            $final['main'] = $encounter->success;
            $final['success'] = true;
        } else {
            $final['main'] = $encounter->failure;
            $final['success'] = false;
        }

        $final['jump'] = $final['main']['jump'];
        $final['gold'] = $final['main']['gold'];
        $final['xp'] = $final['main']['xp'];
        $final['hp'] = $this->player->hp - $prehp;
        $final['enemies'] = $this->enemy_list($enemies);
        $this->player->exp = $this->player->exp + $final['xp'];
        $this->player->gold = $this->player->gold + $final['gold'];
        $this->player->energy--;
        $this->player->update_player();
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
        $this->player->update_player();
        return $ret;
    }
    
   /**
    * gets skin
    *
    * @return string html
    */
    public function quest_log() {
        $code = $this->db->GetOne("SELECT `quest` FROM `players` WHERE `id`=?",array($this->player->id));
        preg_match("/([0-9]+?)(?::([0-9a-z]+?))?(?:\{(.*)\})?/is",$code,$args);
        $this->quest_id = $args[1];
        $this->current = $args[2];
	$stages = explode(";",$args[3]);
        foreach($stages as $stage) {
            if ($stage == '') {
                continue;
            }

            $stage_array = explode(":", $stage);

            $stage_name = $stage_array[0];
            $stage_value = $stage_array[1];
            $this->quest_stages[$stage_name] = $stage_value;
        }

        $this->quest_stages['last'] = $this->quest_stages['start'];

        $file = 'quests/quest-'.md5($this->settings['quests_code'].$this->quest_id).'.xml';
        if (file_exists($file)) {
            $quest = simplexml_load_file($file);
        } else {
            $this->db->execute("UPDATE `players` SET `quest`='0' WHERE `id`=?", array($this->player->id));
            header('location:index.php?page=quest');
        }

        $this->quest_stages['location'] = $quest->start;

        if (!$this->current) {
            $this->current = $quest->start;
        }
        foreach ($quest->event as $event) {
	        foreach ($event->attributes() as $name => $value) {
                    $event[$name] = $value;
                }
                $e = 't'.$event['id'];
                $this->events->$e = $event;
        }
        
        $quest_html = $this->event_jump($this->quest_stages['location']);
        $quest_html = str_replace("{{USER}}", $this->player->username, $quest_html);

        return $this->skin->quest($quest, $this->next_event - time(), $quest_html);
    }

   /**
    * updates progress in players table
    *
    * @return boolean
    */
    public function update_progress() {
        $args = $this->quest_id . ":" . $this->current . "{";
        foreach($this->quest_stages as $name => $value) {
            if (is_numeric($name)) {
                continue;
            }
            $args .= $name . ":" . $value . ";";
        }
        $args .= "}";
        $player_query = $this->db->execute("UPDATE `players` SET `quest`=? WHERE `id`=?", array($args, $this->player->id));
        
        if ($player_query) {
            true;
        } else {
            false;
        }
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

   /**
    * a list of enemies
    *
    * @param array $enemies
    * @return string html
    */
    public function enemy_list($enemies) {
        $count = count($enemies);
        for ($i=0;$i<$count;$i++) {
            if ($i == $count-1) {
                $enemy_list .= $enemies[$i];
            } else if ($i == $count-2) {
                $enemy_list .= $enemies[$i] . " and ";
            } else {
                $enemy_list .= $enemies[$i] . ", ";
            }
        }
        $enemy_list .= ".";
        return $enemy_list;
    }

   /**
    * the rewards of an event
    *
    * @param string $gold gold received
    * @param string $xp xp earned
    * @return string html
    */
    public function gains($gold=0, $xp=0, $hp=0) {
        if ($gold > 0) {
            $gains .= $this->skin->got_gold($gold);
        }

        if ($xp > 0) {
            $gains .= $this->skin->got_xp($xp);
        }

        if ($hp > 0) {
            $gains .= $this->skin->got_hp($hp);
        }

        return $gains;
    }
}
?>