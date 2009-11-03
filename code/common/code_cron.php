<?php
/**
 * ticking tocking clocking
 * (TODO) hooks for functions
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

   /**
    * gets shit from twitter
    */
    public function twitter() {

        $check_query = $this->db->execute("SELECT `twitter_id` FROM `twitter` ORDER BY `twitter_id` DESC LIMIT 1");

        if ($check = $check_query->fetchrow()) {
            $search_string = "?since_id=".$check['twitter_id'];
        }
        //Create the connection handle

        $curl_conn = curl_init();

        //Set up the URL to query Twitter
        $user_followers = "https://twitter.com/statuses/replies/postyourangst.json".$search_string;
        
        //Set cURL options

        curl_setopt($curl_conn, CURLOPT_URL, $user_followers); //URL to connect to
        curl_setopt($curl_conn, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //Use basic authentication
        curl_setopt($curl_conn, CURLOPT_USERPWD, 'postyourangst:ilikeit'); //Set u/p
        curl_setopt($curl_conn, CURLOPT_SSL_VERIFYPEER, false); //Do not check SSL certificate (but use SSL of course), live dangerously!
        curl_setopt($curl_conn, CURLOPT_RETURNTRANSFER, 1); //Return the result as string

        // Result from querying URL. Will parse as xml
        $output_angst = curl_exec($curl_conn);

        // close cURL resource. It's like shutting down the water when you're brushing your teeth.
        curl_close($curl_conn);

        $array_angst = json_decode($output_angst, true);

        $curl_conn = curl_init();

        //Set up the URL to query Twitter
        $user_followers = "https://twitter.com/statuses/replies/postyourglee.json".$search_string;
        
        //Set cURL options

        curl_setopt($curl_conn, CURLOPT_URL, $user_followers); //URL to connect to
        curl_setopt($curl_conn, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //Use basic authentication
        curl_setopt($curl_conn, CURLOPT_USERPWD, 'postyourglee:ilikeit'); //Set u/p
        curl_setopt($curl_conn, CURLOPT_SSL_VERIFYPEER, false); //Do not check SSL certificate (but use SSL of course), live dangerously!
        curl_setopt($curl_conn, CURLOPT_RETURNTRANSFER, 1); //Return the result as string

        // Result from querying URL. Will parse as xml
        $output_glee = curl_exec($curl_conn);

        // close cURL resource. It's like shutting down the water when you're brushing your teeth.
        curl_close($curl_conn);

        $array_glee = json_decode($output_glee, true);

        if (empty($array_angst) && empty($array_glee)) {
            return;
        }
        
        foreach ($array_angst as $tweet) {
            $angst_insert['angst'] = htmlentities($tweet['text'], ENT_QUOTES, 'utf-8');
            $angst_insert['type'] = 0;
            $angst_insert['time'] = strtotime($tweet['created_at']);
            $this->db->AutoExecute('angst', $angst_insert, 'INSERT');
            
            $twitter_insert['twitter_id'] = $tweet['id'];
            $twitter_insert['angst_id'] = $this->db->Insert_Id();
            $twitter_insert['twitter_user_id'] = $tweet['user']['id'];
            $this->db->AutoExecute('twitter', $twitter_insert, 'INSERT');
        }

        foreach ($array_glee as $tweet) {
            $angst_insert['angst'] = htmlentities($tweet['text'], ENT_QUOTES, 'utf-8');
            $angst_insert['type'] = 1;
            $angst_insert['time'] = strtotime($tweet['created_at']);
            $this->db->AutoExecute('angst', $angst_insert, 'INSERT');

            $twitter_insert['twitter_id'] = $tweet['id'];
            $twitter_insert['angst_id'] = $this->db->Insert_Id();
            $twitter_insert['twitter_user_id'] = $tweet['user']['id'];
            $this->db->AutoExecute('twitter', $twitter_insert, 'INSERT');
        }

    }
}
?>
