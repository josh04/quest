<?php
  //function guest_help()
$this->html .= "
<h2>Contents</h2>
<ul style=\"list-style-image: url(http://www.gregoland.uni.cc/content/images/icons/user_comment.png);\">
<li><a href=\"#what\">What is CHERUB Quest?</a></li>
<li><a href=\"#how\">How do I register?</a></li>
<li><a href=\"#login\">I've got an account, how do I login?</a></li>
<li><a href=\"#work\">How does the game work?</a></li>
</ul>

<h2>Help Files</h2>

<br />
<strong><a name=\"what\" style=\"color:black\">What is CHERUB Quest?</a></strong>
<div class=\"bblue\">CHERUB Quest is a browser-based game set around the CHERUB books by Robert Muchamore. In the game, you act as an agent on campus. You can train to improve your skills and eventually may get to go on missions. You can find out more from the following links: <a href=\"http://www.cherubcampus.com/\">The CHERUB Series</a>, <a href=\"http://cherubseries.proboards34.com/\">CHERUB Forums</a>, <a href=\"http://www.robertmuchamore.com/\">Robert Muchamore</a>, <a href=\"http://chiki.info/\">CHERUB Wiki</a>.</div>

<br />
<strong><a name=\"how\" style=\"color:black\">How do I register?</a></strong>
<div class=\"bgreen\">You can register for CHERUB Quest <a href=\"index.php?page=login\">here</a>. By supplying an email address, username and password you can play the game. You may need to verify your account through your email address so it is <i>essential</i> that you use a real email address.</div>

<br />
<strong><a name=\"login\" style=\"color:black\">I've got an account, how do I login?</a></strong>
<div class=\"byellow\">By going to the <a href=\"index.php\">home page</a>, you can login to your account. Enter your username and password into the boxes on the right of that page and click \"Login\".</div>

<br />
<strong><a name=\"work\" style=\"color:black\">How does the game work?</a></strong>
<div class=\"bpink\">Within the game is a help system with comprehensive details on how to play the game as well as technical information on how the system behind the game works. Included is a walkthrough and a list of Frequently Asked Questions. Like this one, but with more information ^_^</div>
";
    $this->final_output();

?>
