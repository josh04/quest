<?php
/**
 * Description of skin_install
 *
 * @author josh04
 * @package skin_install
 */
class skin_install extends skin_common {

   /**
    * intro page
    *
    * @return string html
    */
    public function start() {
        $start = "<div><div><p>Welcome to the Quest installer. To install and configure Quest, click below.</p>

            <p><a href='index.php?page=database'>Start installation</a></p>

            <p>If you have been using ezRPG and want to upgrade to Quest, please click <a href='index.php?page=upgrade_database'>here</a>.</p>";
        return $start;
    }

   /**
    * failtro page
    *
    * @return string html
    */
    public function start_fail() {
        $start_fail = "<div><div><p>Welcome to the Quest installer. To begin, click continue.</p>

            <p><strong><img src='images/icons/error.png' alt='!' style='float:left;' />&nbsp;The installer cannot proceed. Please make sure both config.php and install.lock are writable</strong></p>";
        return $start_fail;
    }

   /**
    * make a db form
    *
    * @param string $db_server server url
    * @param string $db_username db username
    * @param string $db_name db name
    * @param string $message did they fuck up?
    * @return string html
    */
    public function setup_database_form($db_server, $db_username="", $db_name="", $message="") {
        $setup_database_form = "
            <p>This form allows you to configure how Quest accesses your MySQL database. You can later change these details in the <em>config.php</em> file.</p>
            ".$message."
            <form action='index.php?page=database&amp;action=confirm' method='POST'>
            <table>
                <tr><td style='width: 150px;'><label for='install-server'>Server</label></td>
                <td><input type='text' id='install-server' name='db_server' value='".$db_server."' /></td></tr>

                <tr><td colspan='2' class='form-explanation'>If you're unsure of your Server, it's quite likely to be \"localhost\".</td></tr>

                <tr><td><label for='install-username'>Username</label></td>
                <td><input type='text' id='install-username' name='db_username' value='".$db_username."' /></td></tr>

                <tr><td colspan='2' class='form-explanation'>The username used to log into MySQL. You may find it securer to add a seperate MySQL account for Quest.</td></tr>

                <tr><td><label for='install-password'>Password</label></td>
                <td><input type='password' id='install-password' name='db_password' /></td></tr>

                <tr><td><label for='install-password-confirm'>Confirm password</label></td>
                <td><input type='password' id='install-password-confirm' name='db_password_confirm' /></td></tr>

                <tr><td colspan='2' class='form-explanation'>The password associated with your MySQL account.</td></tr>

                <tr><td><label for='install-database'>Database</label></td>
                <td><input type='text' id='install-database' name='db_name' value='".$db_name."' /></td></tr>

                <tr><td colspan='2' class='form-explanation'>The name of database where you want to install Quest to.</td></tr>

                <tr><td colspan='2'><input type='submit' value='Set up database' /></td></tr>
            </table>
            </form>
            ";
        return $setup_database_form;
    }

   /**
    * for making an admin user
    *
    * @param string $message screwed up?
    * @param string $username username!
    * @param string $user_email email!
    * @param string $user_email_confirm email again!
    * @return string html
    */
    public function user_add_form($message="", $username="", $user_email="", $user_email_confirm="") {
        $user_add_form = "
            <p>You can set up your user account below, which will be granted administrator rights.</p>
            ".$message."
            <form action='index.php?page=user&amp;action=confirm' method='POST'>
            <table>
                <tr><td style='width: 150px;'><label for='username'>Username</label></td>
                <td><input type='text' id='username' name='username' value='".$username."' /></td></tr>

                <tr><td colspan='2' class='form-explanation'>The username you want to use.</td></tr>

                <tr><td style='width: 150px;'><label for='password'>Password</label></td>
                <td><input type='password' id='password' name='password' /></td></tr>

                <tr><td style='width: 150px;'><label for='password_confirm'>Confirm password</label></td>
                <td><input type='password' id='password_confirm' name='password_confirm' /></td></tr>

                <tr><td colspan='2' class='form-explanation'>Make sure you choose a secure password!</td></tr>

                <tr><td style='width: 150px;'><label for='email'>Email</label></td>
                <td><input type='text' id='email' name='email' value='".$user_email."' /></td></tr>

                <tr><td style='width: 150px;'><label for='email'>Confirm email</label></td>
                <td><input type='text' id='email_confirm' name='email_confirm' value='".$user_email_confirm."' /></td></tr>

                <tr><td colspan='2' class='form-explanation'>Your email address. No confirmation is necessary, you're not like other users.</td></tr>

                <tr><td colspan='2'><input type='submit' value='Create your account' /></td></tr>
            </table>
            </form>
            ";
        return $user_add_form;
    }

   /**
    * done!
    *
    * @return string html
    */
    public function complete() {
        $complete = "<p>Install complete!</p><p><a href='index.php'>Start playing!</a></p>";
        return $complete;
    }

   /**
    * shows the ezrpg db upgrade page
    *
    * @return string html
    */
    public function upgrade_page($message="") {
        $upgrade_page = $message."<br />Please backup your ezrpg database before proceeding.
            Due to the nature of ezrpg installs, databases vary greatly so it
            is not possible to guarentee the following procedure will work.<br />
            <br />
            <a href='index.php?page=upgrade_database&amp;action=upgrade'>Continue</a>";
        return $upgrade_page;
    }

   /**
    * they did it!
    *
    * @return string html
    */
    public function setup_database_complete() {
        $setup_database_complete = "<p>Database installation was successful</p>
            <p><a href='index.php?page=user'>Click here to continue</a></p>";
        return $setup_database_complete;
    }

}
?>
