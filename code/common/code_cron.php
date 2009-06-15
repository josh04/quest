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
        $cron_query = $this->db->execute("SELECT * FROM `cron` WHERE `enabled`=1");
        while ($cron = $cron_query->fetchrow()) {
            $time = time();
            if ($time > ($cron['last_active']+$cron['period'])) {
                $repeat = intval(($time - $cron['last_active'])/$cron['period']);
                
                for ($i=0;$i<$repeat;$i++) {
                    $this->$cron['function']($repeat);
                }

                $update_cron['last_active'] = $cron['last_active']+($cron['period']*$repeat);
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
    * recover health naturally?
    *
    */
    public function natural_heal($repeat) {
        $player_query = $this->db->execute("UPDATE `players` SET `hp`=`hp`+".$repeat);
    }

   /**
    * money for all
    * 
    */
    public function interest($repeat) {

        $player_query = $this->db->execute("UPDATE `players` SET `interest`=".$repeat."+`interest`");
    }
}
?>
