<?php
/**
 * ticking tocking clocking
 *
 * @author josh04
 * @package code_common
 */
class code_cron {

    public $db;

    public function __construct($config = array()) {
        $this->db = code_database_wrapper::get_db($config);
    }

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
                
                $this->$cron['function']($repeat);

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
        $player_query = $this->db->execute("UPDATE `players` SET `energy`=`energy_max`");
    }
    
   /**
    * recover health naturally?
    * 
    */
    public function recover_health() {
        $player_query = $this->db->execute("UPDATE `players` SET `hp`=`hp_max`");
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
    public function interest() {

        $player_query = $this->db->execute("UPDATE `players` SET `interest`=1");
    }
}
?>
