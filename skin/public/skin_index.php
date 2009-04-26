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
            <ul>
                <li class='first'>Welcome ".$player->username."</li>
                <li>".$player->description."</li>
                <li>Email: ".$player->email."</li>
                <li>Registered: ".$player->date."</li>
                <li>Character Age: ".$player->age." days</li>
                <li>Wins/Losses: ".$player->kills."/".$player->deaths."</li>
            </ul>
            ".$stats."

            <ul>
                <li>Level: ".$player->level."</li>
                <li>EXP: ".$player->exp."/".$player->exp_max." (".$player->exp_percent."%)</li>
                <li>HP: ".$player->hp."/".$player->hp_max."</li>
                <li>Energy: ".$player->energy."/".$player->energy_max."</li>
                <li>Tokens: ".$player->gold."</li>
            </ul>
            <ul>
                <li>Strength: ".$player->strength."</li>
                <li>Vitality: ".$player->vitality."</li>
                <li>Agility:".$player->agility."</li>
            </ul>

            <h4>Users Online:</h4>
            <div class='bblue'>".$online_list."</div>
            <h4>Latest News:</h4>
            ".$news;
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
            <div>
                Let the adventure begin!<br />
                Welcome to CHERUBQuest!<br />
                Login now to play, or <a href='index.php?page=login&amp;action=register'>Register</a> to join the game and become an agent!<br />
                <br />
                CHERUBQuest is the first CHERUB persistent online browser game, or PBBG. Join now to begin the adventure!<br />
                <br />You can go around campus, earning tokens and stat points, hiring weapons and armour, and challenging other agents to fights in the Combat Training Centre. <br /><br />All queries and suggestions, as well as pointing out any mistakes should be directed towards the admin staff either through CHERUB Forums or through e-mail at admin@cherubquest.uni.cc
                <br />Train against your friends and in the process earn tokens off them, or lose them if you are defeated. Buy weapons and armour from the equipment store and use it to arm yourself against your friends!
                <br />Good luck agent, and good luck becoming the best on campus!
                <div class='login'>
                    <form method='post' action='index.php?page=login'>
                        <strong>Login:</strong><br />
                        Username:<br />
                        <input type='text' name='username' value='".$username."' style='background-image:url(images/icons/user.png);background-repeat:no-repeat;padding-left:16px;' /><br />
                        Password:<br />
                        <input type='password' name='password' style='background-image:url(images/icons/key.png);background-repeat:no-repeat;padding-left:16px;' /><br />
                        <input name='login' type='submit' value='Login' /><br />
                    </form>
                    ".$login_message."<br />
                    <a href='index.php?page=login&amp;action=register'>Click here to register!</a>
                </div>
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
        $stats_link = "You have ".$stat_points." stat points to spend.
	       <a href='index.php?page=stats'>Click Here</a> to spend them.";
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
