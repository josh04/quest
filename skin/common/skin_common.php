<?php
/**
 * skin_common.class.php
 *
 * Contains static skin elements, such as header html. No functions called or processing done.
 * @package skin_common
 * @author josh04
 */

class skin_common {

    public $lang_error;

   /**
    * displays error; needs skinning
    *
    * @param string $error error text
    * @return string html
    */
    public function error_page($error) {
        $error_page = "<h2>Error</h2><div class='error'>".$error."</div>
			<p><a href=\"index.php\">Return home</a> | <a href=\"#\" onclick=\"javascript:history.go(-1);return false;\">Back one page</a></p>
			<div><div>";
        return $error_page;
    }
    
    /**
    * return the static start of the skin
    *
    * @param string $title page title
    * @param string $css name of css file
    * @return string html
    */
    public function start_header($title, $skin) {
        
        $start_header = "
                    <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'> 
                    <html xml:lang='en' lang='en' xmlns='http://www.w3.org/1999/xhtml'>
                    <head>
                    <title>".$title."</title>
                    <meta http-equiv='content-type' content='text/html; charset=utf-8' />
                    <link rel='stylesheet' type='text/css' href='./skin/common/" . $skin . "' />
                    <script type='text/javascript' language='JavaScript' src='./skin/common/functions.js'></script>
                    </head>
                    <body>
                    <div id='wrapper'>
                    <div id='header-text'>".$title."</div>
                    ";
        
        return $start_header;
    }
    
    /**
    * return the logged-in menu
     * (TODO) menu should be generated
    *
    * @param player $player player object
    * @param string $mail new message html
    * @param string $admin admin menu html
    * @return string html
    */
    public function menu_player($player, $mail, $admin) {
        $menu_player .= "
            <div id='left'>
                <div class='left-section'>
                    <strong>Username:</strong> <a href='index.php?page=profile&amp;id=".$player->id."'>".$player->username."</a>
                    <ul>
                       <li><strong>Level:</strong> ".$player->level."</li>
                       <li><strong>EXP:</strong> ".$player->exp."/".$player->exp_max." (".$player->exp_percent."%)</li>
                       <li><strong>Health:</strong> ".$player->hp."/".$player->hp_max."</li>
                       <li><strong>Energy:</strong> ".$player->energy."/".$player->energy_max."</li>
                       <li><strong>Money:</strong> &#163;".$player->gold."</li>
                    </ul>
                        ".$mail."
                </div>
                <div class='left-section'>
                    <ul>
                        <li class='header'>Game Menu</li>
                        <li><a href='index.php'>Home</a></li>
                        <li><a href='index.php?page=mail'>Mail [".$player->unread."]</a></li>
                        <li><a href='index.php?page=campus'>Campus</a></li>
                        <li><a href='index.php?page=ranks'>Player Stats</a></li>
                        <li><a href='index.php?page=members'>Member List</a></li>
                        <li><a href='index.php?page=profile_edit'>Edit Profile</a></li>
                    </ul>
                </div>
                ".$admin."
                <div class='left-section'>
                    <ul>
                        <li class='header'>Other</li>
                        <li><a href='index.php?page=help'>Help</a></li>
                        <li><a href='index.php?page=ticket'>Tickets</a></li>
                        <li><a href='index.php?page=login'>Logout</a></li>
                    </ul>
                </div>
            </div>
            ";
        return $menu_player;
        
    }

    /**
    * return the admin menu
    *
    * @return string html
    */
    public function menu_admin() {
        $menu_admin .= "
            <div class='left-section'>
                <ul>
                    <li class='header'>Admin</li>
                    <li><a href='admin.php'>Control Panel</a></li>
                    <li><a href='index.php?section=admin&amp;page=ticket'>Ticket Control</a></li>
                    <li><a href='index.php?section=admin&amp;page=blueprints'>Item Blueprints</a></li>
                    <li><a href='admin.php?page=tasks'>Task List</a></li>
                </ul>
            </div>";
        return $menu_admin;
    }

    /**
    * return new mail row
    *
    * @param integer $id user id
    * @param string $user username
    * @param string $subject message subject
    * @return string html
    */
    public function mail_row($id, $user, $subject) {
        $mail_row .= "<li>
                      <a style='background:#FCC;' href='index.php?page=mail&amp;action=read&amp;id=".$id."'>
                      <strong>From:</strong> ".$user."<br />
                      <strong>Subject:</strong><br /> ".$subject ."</a>
                      </li>";
        return $mail_row;
    }

    /**
    * return mail wrap
    *
    * @param string $mail_rows mail list elements
    * @return string html
    */
    public function mail_wrap($mail_rows) {
        $mail_wrap = "<ul>".$mail_rows."</ul>";
        return $mail_wrap;
    }

    /**
     * guest menu html
     * @param integer $players number of online players
     * @param integer $money total money
     * @return string html
     */
    public function menu_guest($players, $money) {
        $menu_guest .="
            <div id='left'>
                <div class='left-section'>
                    <ul>
                        <li class='header'>General</li>
                        <li><a href='index.php'>Home</a></li>
                        <li><a href='index.php?page=login'>Register</a></li>
                        <li><a href='index.php?page=ranks'>Player stats</a></li>
                        <li><a href='index.php?page=guesthelp'>Help</a></li>
                    </ul>

                    <ul>
                        <li class='header'>Quick stats</li>
                        <li><a style=\"cursor:default;\">Players online: ".$players."</a></li>
                        <li><a style=\"cursor:default;\">Money in game: ".$money."</a></li>
                    </ul>
                </div>
            </div>";
        return $menu_guest;
    }

    /**
     * glues the left side to the right. yeah.
     * @return string html
     */
    public function glue() {
        $glue = "<div id='right'>
                 <div id='content'>";
        return $glue;
    }

    /**
     * returns footer html
     * @return string html
     */
    public function footer() {
         $footer .= "
                    </div>
                    </div>
                    <div id='footer'>
                        <div id='footer-text'>
                            <span style='font-size:1.5em;'>Quest</span><br />
                            <span style='font-weight:normal;'>Based on ezRPG 0.1 by Zeggy</span><br />
                            New code by Justin, Jamie, Josh04 and Grego<br />
                            This version by Josh04 and Grego
                        </div>
                    </div>
                </div>
                </body>
            </html>";
        return $footer;
    }

   /**
    * for making shiny red boxes
    * (TODO) USE THIS EVERYWHERE
    *
    * @param string $message error
    * @return string html
    */
    public function error_box($message) {
        $error_box = "<div class='error'>".$message."</div>";
        return $error_box;
    }

   /**
    * for making shiny green boxes
    *
    * @param string $message what happened?
    * @return string html
    */
    public function success_box($message) {
        $success_box = "<div class='success'>".$message."</div>";
        return $success_box;
    }

}
?>
