<?php
/**
 * ticking tocking clocking
 *
 * @author josh04
 * @package code_common
 */
class code_cron {

    public $db;

   /**
    * time to cron shit up
    * 
    */
    public function update() {
        $cron_query = $this->db->execute("SELECT * FROM `cron`");
        while ($cron = $cron_query->fetchrow()) {
            $time = time();
            if ($time > ($cron['last_active']+$cron['period'])) {
                $this->$cron['function']();
                $update_cron['last_active'] = $time;
                $cron_update_query = $this->db->AutoExecute('cron', $update_cron, 'UPDATE', 'id='.$cron['id']);
            }
        }
        
    }
    
   /**
    * get energy back
    * 
    */
    public function reset_energy() {
        $player_query = $this->db->execute("UPDATE `players` SET `energy`=`maxenergy`");
    }
    
   /**
    * recover health naturally?
    * 
    */
    public function recover_health() {
        $player_query = $this->db->execute("UPDATE `players` SET `hp`=`maxhp`");
    }

   /**
    * money for all
    * 
    */
    public function interest() {
        $player_query = $this->db->execute("UPDATE `players` SET `interest`=0");
    }
}
?>
