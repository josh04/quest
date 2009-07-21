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
    * @param string $quest current quest html
    * @return string html
    */
    public function index_player($player, $stats, $news, $online_list, $quest) {
        $index_player = "
                <h2>Welcome, ".$player->username."</h2>
            ".$quest."
            ".$stats."

            <h4>Users online</h4>
            <div class=\"success\" style=\"margin: 8px;\">".$online_list."</div>
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

}

?>
