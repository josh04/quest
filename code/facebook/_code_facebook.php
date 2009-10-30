<?php
/**
 * Description of ${name}
 *
 * @author josh04
 * @package code_facebook
 */
class _code_facebook extends code_common {

    public $player_class = "code_player_facebook";
    public $facebook;

    public function __construct($section = "", $page = "", $config = array()) {
        require_once 'code/facebook/facebook.php';
        //unset($_GET['auth_token']);
        $appapikey = '61c37291d70d8a873621849082cc9490';
        $appsecret = '8470b215fcdfd93481a10e0814982405';
        $this->facebook = new Facebook($appapikey, $appsecret);
        parent::__construct($section, $page, $config);
    }

   /**
    * Don't want no menu for facebook.
    *
    * @return
    */
    public function make_menu() {
        return;
    }

   /**
    * gives the player a facebook api
    */
    public function make_player() {
        
        $session_key = md5($this->facebook->api_client->session_key);
        session_id($session_key);
        session_start();
        
        if ($this->player_class != "code_player") {
            require_once("code/player/".$this->player_class.".php");
        }

        $this->player = new $this->player_class($this->settings, $this->config);
        $this->player->page = $this->page;
        $allowed = $this->player->make_player($this->facebook);
        if (!$allowed) {
            $this->error_page($this->lang->page_not_exist);
        }
    }

}
?>
