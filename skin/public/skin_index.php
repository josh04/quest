<?php
/**
 * skin_default.class.php
 *
 * Contains static skin elements related to the index page.
 * @package skin_public
 * @author josh04
 */

class skin_index extends skin_common {

   /**
    * returns the main player-logged-in screen
    *
    * @param player $player player object
    * @param string $online_list names of online players
    * @param string $mail any mail?
    * @return string html
    */
    public function index_player($player, $angst, $online_list, $mail, $message = "") {
        $index_player = "
            <div class='index-form-margin'>
  <b class='wt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b><div class='index-form'>
                <h2>Got Angst?</h2>
                <form action='index.php?page=index&amp;action=angst' method='post'>
                    <table><tbody><tr><td style='width:99%;padding:5px;'>

<textarea cols='60' rows='1'
          onkeyup='resizeTextarea(this)'
          onmouseup='resizeTextarea(this)' id='angst' name='angst'></textarea></td>
                    <td style='padding:5px;'><input type='submit' value='Angst' name='submit' id='angst-button' /></td>
<td style='padding:5px;'><input type='submit' name='submit' value='Glee' id='glee-button' /></td></tr></tbody></table>
                </form>".$message."
</div>
  <b class='wb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b>
            </div>
            
            <h3>Welcome, ".$player->username.":</h3>
            
            <div style='clear:both'></div>
            <div style='float:right'>
                <div class=\"success\">
                    <h4>Your Profile</h4>
                        Registered: ".$player->registered_date."<br />
                        Last active: ".date("jS M, h:i A",$player->last_active)."
                </div>
                <div class=\"success\">
                    <h4>Users online</h4>".$online_list."
                </div>
                <div class=\"success\">
                    <h4>Recent mail (<a href=\"index.php?page=mail\">more</a>)</h4>
                        ".$mail."
                </div>
            </div>

<div class='index-margin'>
  <b class='wt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>

<div class='index-main'>".$angst."</div>

  <b class='wb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b></div>

            <div style='clear:both'></div>
            
            ";
        return $index_player;
    }

   /**
    * returns the main guest screen
    *
    * @param string $username last entered username
    * @param string $loginerror login error message
    * @return string html
    */
    public function index_guest($username, $login_message, $welcome_text) {
        $index_guest .= "
                <div class='login' style='float:right;border:1px solid #CCC;padding:8px;margin:4px;width:220px;'>
                    <form method='post' action='index.php?page=login'>
                        <strong>Log in</strong><br />
                        <label for='login-username'>Username:</label><br />
                        <input type='text' id='login-username' name='username' value='".$username."' style='background:url(images/icons/user.png) no-repeat 2px 2px;background-repeat:no-repeat;padding-left:18px;' /><br />
                        <label for='login-password'>Password:</label><br />
                        <input type='password' id='login-password' name='password' style='background:url(images/icons/key.png) no-repeat 2px 2px;padding-left:18px;' /><br />
                        <input name='login' type='submit' value='Login' /><br />
                    </form>
                    ".($login_message?"<div class='error'>".$login_message."</div>":"")."<br />
                    <a href='index.php?page=login&amp;action=register'>Click here to register!</a>
                </div>
            <div>
                ".$welcome_text."
                <br style='clear:both;' />
            </div>";
        return $index_guest;
    }

   /**
    * returns the member name link
    *
    * @param integer $id user id
    * @param string $username username
    * @return string html
    */
    public function member_online_link($id, $username) {
        $member_online_link = "<a href='index.php?page=profile&amp;id=".$id."'>".$username."</a>";
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
    * returns a log entry
    *
    * @param string $message the content of the log entry
    * @param integer $status the status of the log entry (read or unread)
    * @return string html
    */
    public function log_entry($message, $status) {
        $log_entry = "<div style='border:1px solid #CCC;margin:4px;padding:4px;".($status==0?"background-color:#FCC;":"")."'>".$message."</div>";
        return $log_entry;
    }

   /**
    * returns a mail message
    *
    * @param array $message the details of the message
    * @return string html
    */
    public function mail_entry($message) {
        $mail_entry = "<em><a href=\"?page=mail&amp;action=read&amp;id=".$message['id']."\">" . $message['subject'] . "</a></em><br />From: ".$message['username'];
        $mail_entry = $this->log_entry($mail_entry, $message['status']);
        return $mail_entry;
    }

   /**
    * returns the details of your current quest
    *
    * @param array $quest what you're doing
    * @return string html
    */
    public function current_quest($quest) {
        return "<div class='quest-select'>
        <a style='float:right;' href='index.php?page=quest'>See your progress</a>
        <h3>".$quest['title']."</h3>
        Author: ".$quest['author']."<br />
        <p>".$quest['description']."</p></div>";
    }

   /**
    * returns the registration form
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
                <table style='width:100%'>
                    <tr><td width='40%'><label for='form-username'>Username:</label></td><td><input type='text' id='form-username' name='username' value='".$username."' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Enter the username that you will use to login to your game. Only alpha-numerical characters are allowed.</td></tr>

                    <tr><td><label for='form-password'>Password:</label></td><td><input type='password' id='form-password' name='password' value='' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Type in your desired password. Only alpha-numerical characters are allowed.</td></tr>

                    <tr><td><label for='form-password-confirm'>Verify Password:</label></td><td><input type='password' id='form-password-confirm' name='password_confirm' value='' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Please re-type your password.</td></tr>

                    <tr><td><label for='form-email'>Email:</label></td><td><input type='text' id='form-email' name='email' value='".$email."' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Enter your email address. Only alpha-numerical characters are allowed.</td></tr>

                    <tr><td><label for='form-email-confirm'>Verify Email:</label></td><td><input type='text' id='form-email-confirm' name='email_confirm' value='' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Please re-type your email address.</td></tr>

                    <tr><td colspan='2'><input type='submit' name='register' value='Register!'></td></tr>
                </table>
            </form>";
        return $register;
    }

   /**
    * Not what?
    *
    * @return string html
    */
    public function player_not_approved() {
        $player_not_approved = "<div class='success'><p>The site's administrators have opted to approve all new accounts.</p>
                                <p>Yours has not been approved yet.</p></div>";
        return $player_not_approved;
    }

   /**
    * angst!
    *
    * @param array $angst moody moody moody
    * @return string html
    */
    public function angst($angst) {
        $angst = "<div>
  <b class='rt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>

<div class='angst'>".$angst['angst']."</div>

  <b class='rb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b>
</b></div><div style='text-align:right;margin:0;padding:0;'>".date("jS M, h:i A", $angst['time'])."</div>

";
        return $angst;
    }

   /**
    * glee!
    *
    * @param array $angst well, not really
    * @return string html
    */
    public function glee($angst) {
        $glee = "<div>
  <b class='gt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>

<div class='glee'>".$angst['angst']."</div>


  <b class='gb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b>
</b></div><div style='text-align:right;margin:0;padding:0;'>".date("jS M, h:i A", $angst['time'])."</div>

";
        return $glee;
    }

   /**
    * Player details menu
    *
    * @param code_player $player Player object
    * @param array $player_extra extra player rpg details
    * @return string html
    */
    public static function player_details_menu(&$player, $player_extra) {
        $player_details_menu = "<div class='left-section'>
                    <strong>Username:</strong> <a href='index.php?page=profile&amp;id=".$player->id."'>".$player->username."</a>
                    <ul>
                       <li><strong>Level:</strong> ".$player_extra['level']."</li>
                       <li><strong>EXP:</strong> ".$player_extra['exp']." (".$player_extra['exp_diff']." to go)</li>
                       <li><strong>Health:</strong> ".$player_extra['hp']."/".$player_extra['hp_max']."</li>
                       <li><strong>Energy:</strong> ".$player_extra['energy']."/".$player_extra['energy_max']."</li>
                       <li><strong>Money:</strong> &#163;".$player->gold."</li>
                    </ul>
                </div>";
        return $player_details_menu;
    }

}

?>
