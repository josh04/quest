<?php
// login.php : Login functions.
// We need to login! Or out. this handles both.


if ($player->is_member == 1) {
  unset($player);
  unset($display->player);
  session_unset();
	session_destroy();
	setcookie("hash", NULL, mktime() - 36000000, "/");
	$errormsg = "<span style='color:red'>You have logged out.</span>";
	$display->index($errormsg);
	exit;
}

        if( $_GET['action'] == 'login' ) {
            if ($_POST['username'] == "") {
                $errormsg = "<span style='color:red'>Please enter a username.</span>";
                $display->index($errormsg);
            } elseif ($_POST['password'] == "") {
                $errormsg = "<span style='color:red'>Please enter your password.</span>";
                $display->index($errormsg);
            } else {
                $query = $db->execute("SELECT `id`, `username`, `password`
                              FROM `players`
                              WHERE `username`=? AND `password`=?",
                              array($_POST['username'], sha1($_POST['password'])));
                if ($query->recordcount() == 0) {
                    $errormsg = "<span style='color:red'>Incorrect Username/Password.";
                    $display->index($errormsg);
                } else {
                    $dbplayer = $query->fetchrow();
                    $query = $db->execute("update `players` set `last_active`=? where `id`=?", array(time(), $dbplayer['id']));
                    $hash = sha1($dbplayer['id'] . $dbplayer['password'] . $config['secret_key']);
                    $_SESSION['userid'] = $dbplayer['id'];
                    $_SESSION['hash'] = $hash;
                    setcookie("cookie_hash", $hash, mktime()+86400);
                    header("Location: index.php");
                    exit;
                }
            }
        }
} elseif ($_GET['action'] == 'register') {

$query = $db->execute("SELECT id FROM players 
                       WHERE username=?", 
                       array($_POST['username']));

if (!$_POST['username']) {
  $display->simple_page("You need to fill in your username.","","bred");
  exit;
} elseif (strlen($_POST['username']) < 3) {
  $display->simple_page("Your username must be longer than 3 characters.","","bred");
  exit;
} elseif (!preg_match("/^[-_a-zA-Z0-9]+$/", $_POST['username'])) {
  $display->simple_page("Your username may contain only alphanumerical characters.","","bred");
  exit;
} elseif ($query->recordcount() > 0) {
  $display->simple_page("That username has already been used.","","bred");
  exit;
}


if (!$_POST['password']) {
  $display->simple_page("You need to fill in your password.","","bred");
  exit;
} elseif ($_POST['password'] != $_POST['password2']) {
  $display->simple_page("You didn't type in both passwords correctly.","","bred");
  exit;
} elseif (strlen($_POST['password']) < 3) {
  $display->simple_page("Your password must be longer than 3 characters.","","bred");
  exit;
} elseif (!preg_match("/^[-_a-zA-Z0-9]+$/", $_POST['password'])) {
  $display->simple_page("Your password may contain only alphanumerical characters.","","bred");
  exit;
}

//Check email
if (!$_POST['email']) {
  $display->simple_page("You need to fill in your email.","","bred"); 
  exit;
} elseif ($_POST['email'] != $_POST['email2']) {
  $display->simple_page("You didn't type in both email address correctly.","","bred");
  exit;
} elseif (strlen($_POST['email']) < 3) { 
  $display->simple_page("Your email must be longer than 3 characters.","","bred");
  exit;
} elseif (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email'])) {
  $display->simple_page("Your email format is wrong.","","bred");
  exit;
} else {
  $query = $db->execute("select `id` from `players` where `email`=?", array($_POST['email']));
}

if ($query->recordcount() > 0) {
  $display->simple_page("That email has already been used. Please create only one account, creating more than one account will cause all your accounts to be deleted.","","bred");
  exit;
}

$insert['username'] = $_POST['username'];
$insert['password'] = sha1($_POST['password']);
$insert['email'] = $_POST['email'];
$insert['registered'] = time();
$insert['last_active'] = time();
$insert['ip'] = $_SERVER['REMOTE_ADDR'];
$insert['verified'] = 1;
//$insert['confirm_code'] = $confirm_code;
$query = $db->autoexecute('players', $insert, 'INSERT');
if (!$query) {
  $display->simple_page("Error registering new user.","","bred");
  exit;
}
$newmemberid = $db->Insert_Id();

$body = "<strong>Welcome to CHERUB Quest!</strong>
        <p>CHERUB Quest is a online browser game for fans of 
        the CHERUB series by Robert Muchamore, but isn't 
        affiliated so don't contact him with problems. 
        If you have any problems, for that matter, look at the 
        Help Page or use the Ticket System to contact an Admin 
        (TeknoTom, JamieHD, Commander of One, Josh0-4 and Grego)</p>

        <p>But above all, have fun!</p>";

$newmail = $db->execute("INSERT INTO mail(`to`,`from`,`subject`,`body`,`time`) VALUES (?, '1', 'Welcome to CHERUB Quest!', ?, time() )",array($newmemberid, $body) );

$display->simple_page("Congratulations! You have successfully registered. 
      You may login to the game now. Enjoy your stay on campus.","","bred");
} else {
  $display->register();
}
?>