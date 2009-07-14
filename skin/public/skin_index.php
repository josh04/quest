<?php
/**
 * skin_default.class.php
 *
 * Contains static skin elements related to the index page.
 * @package display
 * @author josh04
 */

class skin_index extends skin_common {

   /**
    * returns the main player-logged-in screen
    *
    * @param player $player player object
    * @param string $stats stats html
    * @param string $news news html
    * @param string $online names of online players
    * @return string html
    */
    public function index_player($player, $stats, $news, $online_list) {
        $index_player = "
                <h2>Welcome ".$player->username."</h2>
            ".$stats."

            <table style=\"width:100px;margin:8px auto;\">
                <tr><th style=\"width: 100px;\">Strength</th><td>".$player->strength."</td></tr>
                <tr><th>Vitality</th><td>".$player->vitality."</td></tr>
                <tr><th>Agility</th><td>".$player->agility."</td></tr>
            </table>

            <h4>Users online</h4>
            <div style=\"margin: 0px 12px;\">".$online_list."</div>
            ".($news?"<h4>Latest news</h4>".$news:"");
        return $index_player;
    }

   /**
    * returns the main guest screen
    *
    * @param string $username last entered username
    * @param string $loginerror login error message
    * @return string html
    */
    public function index_guest($username, $login_message) {
        $index_guest .= "
                <div class='login' style='float:right;border:1px solid #CCC;padding:8px;margin:4px;width:30%;'>
                    <form method='post' action='index.php?page=login'>
                        <strong>Log in</strong><br />
                        <label for='login-username'>Username:</label><br />
                        <input type='text' id='login-username' name='username' value='".$username."' style='background:url(images/icons/user.png) no-repeat 2px 2px;background-repeat:no-repeat;padding-left:16px;' /><br />
                        <label for='login-password'>Password:</label><br />
                        <input type='password' id='login-password' name='password' style='background:url(images/icons/key.png) no-repeat 2px 2px;padding-left:16px;' /><br />
                        <input name='login' type='submit' value='Login' /><br />
                    </form>
                    ".$login_message."<br />
                    <a href='index.php?page=login&amp;action=register'>Click here to register!</a>
                </div>
            <div>
                Let the adventure begin!<br />
                Welcome to <strong>CHERUBQuest!</strong><br />
                Login now to play, or <a href='index.php?page=login&amp;action=register'>Register</a> to join the game and become an agent!
                
                <p>CHERUBQuest is the first CHERUB persistent online browser game, or PBBG. Join now to begin the adventure!</p>
                <p>You can go around campus, earning tokens and stat points, hiring weapons and armour, and challenging other agents to fights in the Combat Training Centre. <br /><br />All queries and suggestions, as well as pointing out any mistakes should be directed towards the admin staff either through CHERUB Forums or through e-mail at admin@cherubquest.uni.cc</p>
                <p>Train against your friends and in the process earn tokens off them, or lose them if you are defeated. Buy weapons and armour from the equipment store and use it to arm yourself against your friends!</p>
                <p>Good luck agent, and good luck becoming the best on campus!</p>
            </div>";
        return $index_guest;
    }

   /**
    * returns the link if you have stats points to spend
    *
    * @param integer $stat_points number of available stat points
    * @return string html
    */
    public function stats_link($stat_points) {
        $stats_link = "<div class=\"success\">You have ".$stat_points." stat points to spend.
	       <a href='index.php?page=stats'>Click here</a> to spend them.</div>";
        return $stats_link;
    }

   /**
    * returns the member name link
    *
    * @param integer $id user id
    * @param string $username username
    * @return string html
    */
    public function member_online_link($id, $username) {
        $member_online_link = "<a href='index.php?page=profile&amp;id=".$id."'>".$username."</a> ";
        return $member_online_link;

    }

   /**
    * returns a news entry
    *
    * @param integer $id user id
    * @param string $username username
    * @param string $message news message
    * @return string html
    */
    public function news_entry($id, $username, $message) {
        $news_entry = "<div>".$message."<br /> - <a href='index.php?page=profile&amp;id=".$id."'>".$username."</a></div>";
      return $news_entry;
    }

   /**
    * returns a news entry
    *
    * @param string $username html-friendly username
    * @param string $email html-friendly email
    * @param string $register_error registration error
    * @return string html
    */
    public function register($username, $email, $register_error) {
        $register .= "
            ".$register_error."
            <form method='POST' action='index.php?page=login&amp;action=register_submit'>
                <table width='100%'>
                    <tr><td width='40%'><b>Username</b>:</td><td><input type='text' name='username' value='".$username."' /></td></tr>
                    <tr><td colspan='2'>Enter the username that you will use to login to your game. Only alpha-numerical characters are allowed.</td></tr>

                    <tr><td width='40%'><b>Password</b>:</td><td><input type='password' name='password' value='' /></td></tr>
                    <tr><td colspan='2'>Type in your desired password. Only alpha-numerical characters are allowed.</td></tr>

                    <tr><td width='40%'><b>Verify Password</b>:</td><td><input type='password' name='password_confirm' value='' /></td></tr>
                    <tr><td colspan='2'>Please re-type your password.</td></tr>

                    <tr><td width='40%'><b>Email</b>:</td><td><input type='text' name='email' value='".$email."' /></td></tr>
                    <tr><td colspan='2'>Enter your email address. Only alpha-numerical characters are allowed.</td></tr>

                    <tr><td width='40%'><b>Verify Email</b>:</td><td><input type='text' name='email_confirm' value='' /></td></tr>
                    <tr><td colspan='2'>Please re-type your email address.</td></tr>

                    <tr><td colspan='2' align='center'><input type='submit' name='register' value='Register!'></td></tr>
                </table>
            </form>";
        return $register;
    }

}

?>
