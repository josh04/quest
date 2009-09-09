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
        $player_query = $this->db->execute("UPDATE `rpg` SET `energy`=`energy_max`");
    }
    
   /**
    * recover health naturally?
    * 
    */
    public function recover_health() {
        $player_query = $this->db->execute("UPDATE `rpg` SET `hp`=`hp_max`");
    }

   /**
    * recover health naturally?
    *
    */
    public function natural_heal($repeat) {
        $player_query = $this->db->execute("UPDATE `rpg` SET `hp`=`hp`+? WHERE `hp`+? < `hp_max`", array($repeat, $repeat));
    }

   /**
    * money for all
    * 
    */
    public function interest($repeat) {
        $player_query_interest = $this->db->execute("UPDATE `bank` SET `interest_owed`=`interest_owed`+(`bank_gold_saved`*0.3);");
        
        $player_query_deposit = $this->db->execute("UPDATE `bank` SET `bank_gold_saved`=`bank_gold_saved`+`bank_gold_deposited`;");

        if ($repeat > 1) {
            $player_query_interest = $this->db->execute("UPDATE `bank` SET `interest_owed`=`interest_owed`+(`bank_gold_saved`*0.3*".($repeat--).");");
        }

        $player_query_deposit_two = $this->db->execute("UPDATE `bank` SET `bank_gold_deposited`=0;");
    }
}
?>
