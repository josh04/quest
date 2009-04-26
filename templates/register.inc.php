<?php
  //function register_page()
$this->html .= "
<form method='POST' action='index.php?page=login&amp;action=register'>
<table width='100%'>
<tr><td width='40%'><b>Username</b>:</td><td><input type='text' name='username' value='".$_POST['username']."' /></td></tr>
<tr><td colspan='2'>Enter the username that you will use to login to your game. Only alpha-numerical characters are allowed.</td></tr>

<tr><td width='40%'><b>Password</b>:</td><td><input type='password' name='password' value='".$_POST['password']."' /></td></tr>
<tr><td colspan='2'>Type in your desired password. Only alpha-numerical characters are allowed.</td></tr>

<tr><td width='40%'><b>Verify Password</b>:</td><td><input type='password' name='password2' value='".$_POST['password2']."' /></td></tr>
<tr><td colspan='2'>Please re-type your password.</td></tr>

<tr><td width='40%'><b>Email</b>:</td><td><input type='text' name='email' value='".$_POST['email']."' /></td></tr>
<tr><td colspan='2'>Enter your email address. Only alpha-numerical characters are allowed.</td></tr>

<tr><td width='40%'><b>Verify Email</b>:</td><td><input type='text' name='email2' value='".$_POST['email2']."' /></td></tr>
<tr><td colspan='2'>Please re-type your email address.</td></tr>

<tr><td colspan='2' align='center'><input type='submit' name='register' value='Register!'></td></tr>
</table>
</form>";
    $this->final_output();

?>
