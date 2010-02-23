<?php
/**
 * Quest system
 * (TODO) Add rewards
 *
 * @author grego
 * @package code_public
 */
class code_quest extends code_common {

    public $player_flags = array("rpg");
    private $id = 0;
    private $xml;
    public $events = array();
    public $path = array();
    public $elapsed = 0;
    public $finished = false;

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_quest");

        $code_quest = $this->quest_switch();

        return $code_quest;
    }

   /**
    * decides which page to display
    *
    * @return array
    */
    public function quest_switch() {

        if (empty($this->player->quest)) {
                if(isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $this->initiateQuest();
                    $this->do_event( $this->xml->start );
                    $this->saveProgress();
                    $quest_switch = $this->drawQuest();
                    return $quest_switch;
                }

            $quest_switch = $this->quest_select();
            return $quest_switch;
        }

        $this->initiateQuest( true );
        $this->do_event( $this->xml->start );
        $this->saveProgress();
        $quest_switch = $this->drawQuest();
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
    * starts biting through the Quest, doing setup and suchlike
    * 
    * @return void
    */
    public function initiateQuest( $fromdb = false ) {
        if( $fromdb ) {
            $dec = json_decode($this->player->quest);
            $this->id = $dec[0];
        } else {
            $this->id = intval($_GET['id']);
        }

        if ($this->player->hp<=0) {
            $quest_start = $this->quest_select($this->skin->error_box($this->lang->player_currently_incapacitated));
            return $quest_start;
        }
        if ($this->player->energy<=0) {
            $quest_start = $this->quest_select($this->skin->error_box($this->lang->player_no_energy));
            return $quest_start;
        }

        $quest_query = $this->db->execute("SELECT `id` FROM `quests` WHERE `id`=?", array( $this->id ) );
        if ($quest_query->numrows()==1) {

            $file = 'quests/quest-'.md5($this->settings->get['quests_code'].$this->id).'.xml';
            if (file_exists($file)) {
                $this->xml = simplexml_load_file($file);
                foreach($this->xml->event as $e) {
                    $this->events[((string)$e->attributes()->id)] = $e;
                    foreach($e->attributes() as $a=>$b) {
                        $this->events[((string)$e->attributes()->id)]->$a = $b;
                    }
                }

                $db_entry = json_decode($this->player->quest);
                if(is_array( $db_entry ) && !empty($db_entry)) {
                    $this->start = $db_entry[1];
                    if(isset($db_entry[2])) foreach( $db_entry[2] as $p=>$q ) {
                        // Copy array details across to our path
                        $this->path[$p] = $q;
                    }
                } else {
                    $this->start = time();
                }
            } else {
                $this->db->execute("UPDATE `players` SET `quest`='0' WHERE `id`=?", array($this->player->id));
                header('location:index.php?section=public&page=quest');
            }
            
        } else {
            $quest_code = $this->quest_select($this->skin->error_box($this->lang->quest_not_found));
            return $quest_code;
        }

    }

   /**
    * Executes a single event, doing all them stuff involved in event execution
    *
    * @param $event_id The id of the event we're executing, defined in XML
    * @return void
    */
    public function do_event( $event_id ) {

        if( $event_id=="{{END}}" ) {
            $this->finishQuest();
        }

        // Nothing to do
        if( empty($event_id) || empty($this->events[(string)$event_id]) ) {
            return;
        }

        $event = $this->events[(string)$event_id];

        // If we've already done it, we'll move on
        if($path = $this->path[(string)$event_id]) {
            $this->elapsed += (int)$event->duration;
            $this->save[(string)($event->id)] = $path;
            $this->do_event( $path[0] );
            return;
        }

        // It's not your time yet
        if( $this->start + $this->elapsed > time() ) {
            return;
        }

        $this->elapsed += (int)$event->duration;

        $jump = (string)$event->jump;
        $success = true;
        $string = "";
        $return['success'] = true;

        if( $event->type=="encounter" ) {
            $return = $this->do_encounter( (string)$event->id );
            $string = $return['string'];
        }

        if( $event->type=="challenge" ) {
            $return = $this->do_challenge( (string)$event->id );
            $string = $return['string'];
        }

        if($return['success']) {
            if(isset($event->success)) {
                $jump = (string)$event->success->attributes()->jump;
            }
        } else {
            $success = false;
            if(isset($event->failure)) {
                $jump = (string)$event->failure->attributes()->jump;
            }
        }

        $this->save[(string)($event->id)] = array( $jump, $success, $string );
        $this->path[(string)($event->id)] = array( $jump, $success, $string );

        if(!empty($this->events[$jump])) {
            $this->do_event($jump);
        } else {
            $this->do_event("{{END}}");
        }
    }

   /**
    * do a single encounter
    *
    * @return array a fixed array( 'success'=>bool, 'string'=>string )
    */
    public function do_encounter( $event_id ) {
        $encounter = $this->events[(string)$event_id]->encounter;

        //Player cannot attack any more
        if ($this->player->energy == 0) {
            $this->page_generation->error_page($this->lang->player_no_energy);
        }

        //Player is unconscious
        if ($this->player->hp == 0) {
            $this->page_generation->error_page($this->lang->player_currently_incapacitated);
        }

        if (!isset($this->fight)) {
                $this->core('fight');
                $this->fight->player =& $this->player;
        }

        foreach($encounter->combatant as $combatant) {
            $this->fight->enemy = $this->fight->create_enemy();
            foreach($combatant->attributes() as $a=>$b) {
                $a = (string) $a;
                $this->fight->enemy->$a = $b;
            }
            $this->fight->enemy->username = $combatant[0];
            $enemies[] = (string)$this->fight->enemy->username;

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
            $success = true;
        } else {
            $success = false;
        }

        $string = array( $enemies , $success );
        return array( 'success'=>$success, 'string'=>$string );
    }

   /**
    * perform a challenge
    *
    * @return array a fixed array( 'success'=>bool, 'string'=>string )
    */
    public function do_challenge( $event_id ) {
        $challenge = $this->events[(string)$event_id]->challenge;

        $source = (string)$challenge->attributes()->source;
        $value = (string)$challenge->attributes()->value;

        $check = $this->player->$source + rand(0, 10);
        $success = ( $check >= $value ? true : false );

        $string = array( $source , $value , $check );
        return array( 'success'=>$success, 'string'=>$string );
    }

   /**
    * save progress
    *
    * @return void
    */
    public function saveProgress() {
        if( $this->finished ) {
            return;
        }
        $this->player->quest = json_encode( array( $this->id, $this->start, $this->save ) );
        $this->db->execute("UPDATE `players` SET `quest`=? WHERE `id`=?", array( $this->player->quest, $this->player->id ) );
    }

   /**
    * finish the quest
    *
    * @return void
    */
    public function finishQuest() {
        // And finally, we can make the quest disappear
        $this->finished = true;
        $this->db->execute("UPDATE `players` SET `quest`='' WHERE `id`=?", array( $this->player->id ) );
    }

   /**
    * draw the quest, history and all
    *
    * @return void
    */
    public function drawQuest() {
        $events = $this->draw_event( $this->xml->start );
        $time = ($this->start + $this->elapsed + 1) - time();
        $quest = $this->skin->quest( $this->xml, $time, $events );

        return $quest;
    }

/******************************************************************************/
/* DRAWING */
/******************************************************************************/

   /**
    * Draws a single event, calling next in path
    *
    * @param $event_id The id of the event we're drawing, defined in XML
    * @return string the html to output to the page
    */
    public function draw_event( $event_id ) {

        if( $event_id == "{{END}}" ) {
            return $this->skin->finish_quest();
        }

        // Nothing to do
        if( empty($event_id) || empty($this->events[(string)$event_id]) ) {
            return;
        }

        $event = $this->events[(string)$event_id];
        $path = $this->path[(string)$event_id];
        $jump = (string)$path[0];
        $body = array($event->body);

        if( $event->type=="encounter" ) {
            $body[] = $this->draw_encounter( $event_id );
        }

        if( $event->type=="challenge" ) {
            $body[] = $this->draw_challenge( $event_id );
        }

        if( $path[1] == true ) {
            if(isset($event->success)) {
                $body[] = (string)$event->success;
            }
        } else {
            $success = false;
            if(isset($event->failure)) {
                $body[] = (string)$event->failure;
            }
        }

        $body = $this->prepare_string( implode("<hr />",$body) );
        $ret = $this->skin->render_event( $this->prepare_string($event->title), $body );

        if($jump=="{{END}}") {
            $ret .= $this->skin->finish_quest();
        }

        if(!empty($this->path[$jump])) {
            $ret .= $this->draw_event($jump);
        }

        return $ret;
    }

   /**
    * Draws a single encounter
    *
    * @param $event_id The id of the event cum encounter we're drawing
    * @return string the html to output to the page
    */
    public function draw_encounter( $event_id ) {
        $load = $this->path[(string)$event_id];
        $ret = $this->skin->encounter( $this->enemy_list($load[2][0]), $load[1]);
        return $ret;
    }

   /**
    * Draws a single challenge
    *
    * @param $event_id The id of the event cum encounter we're drawing
    * @return string the html to output to the page
    */
    public function draw_challenge( $event_id ) {
        $load = $this->path[(string)$event_id];
        $ret = $this->skin->challenge( $load[2][0], $load[2][1], $load[2][2]);
        return $ret;
    }

   /**
    * Prepares a string, doing some variable substitution
    *
    * @param $string The string to prepare
    * @return string the prepared string
    */
    public function prepare_string( $string ) {
        $string = str_replace( "{{USER}}", $this->player->username, $string );
        return $string;
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
}
?>