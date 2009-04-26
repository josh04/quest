<?php

if(isset($_GET['id'])) $id=$_GET['id']; else $id=1;

$mainbodyq = $db->execute("SELECT * FROM `helpfiles` WHERE `id`=?",array($id));
$mainbody = $mainbodyq->fetchrow();

$mylinksq = $db->execute("SELECT * FROM `helplinks` WHERE `showid`=? ORDER BY `showid` ASC, `order` ASC",array($id));
$links = "";

    while($mylinks = $mylinksq->fetchrow()) {
    $linkpageq = $db->execute("SELECT * FROM `helpfiles` WHERE `id`=?",array($mylinks['linkid']));
    $linkpage = $linkpageq->fetchrow();

    $links .= "<li><a class=\"plain\" href=\"index.php?page=help&id=".$mylinks['linkid']."\">".$mylinks['extra']." ".$linkpage['title']."</a></li>";
    }

if(!$mainbody['body']=="") $pagetext = "<div class=\"b".$mainbody['colour']."\">".$mainbody['body']."</div>"; else $pagetext = "";

$display->html_page("<h2>".$mainbody['title']."</h2>
<ul style=\"list-style-image: url(images/icons/information.png);\">
".$links."
</ul>"
.$pagetext);

$oldhelp = "<div class=\"bblue\">For further help, or to contact an administrator/report a bug please feel free to use our <a href=\"ticket.php\">ticket system</a> or join the forum and you can discuss it there.
<br /><br />For all Frequently Asked Questions, see below. Your question is most probably answered here.</div>

<br /><br /><a href=\"#toolbar\">The Toolbar</a>
<br /><a href=\"#laptop\">Your laptop</a>
<br /><a href=\"#inventory\">Inventory</a>
<br /><a href=\"#tokenbank\">Token Bank</a>
<br /><a href=\"#hospitalwing\">Hospital wing</a>
<br /><a href=\"#combattraining\">Combat Training</a>
<br /><a href=\"#equipmentstore\">Equipment Store</a>
<br /><a href=\"#workfortokens\">Work for Tokens</a><br />
<br /><a href=\"#changeemail\">How do I change my e-mail address?</a>
<br /><a href=\"#getweapons\">How do I get weapons?</a>
<br /><a href=\"#whofought\">How do I find out who fought me?</a>
<br /><a href=\"#levels\">How do I move up a level?</a>
<br /><a href=\"#staff\">How do I become a staff member?</a>
<br /><a href=\"#gangs\">How do I start/join a gang or clan?</a>
<br /><a href=\"#cheater\">How do I get off the cheaters list?</a>
<br /><br /><b>To start off with:</b>
<br /><br /><b>What is the point of this game?</b>
<br />This game is a browser based game, and your role is as a CHERUB agent living on Campus. You can buy weapons and armour from the Equipment Store, store them in the inventory, and use them in the Combat Training Zone against your friends. There will be a ranking table so you can try and beat your friends. If you lose a match, you can go to the Hospital Wing, and you can cash your tokens in at the Token Bank.
<br /><br /><b>I dont have any health, and I dont have enough money to restore it.</b>
<br />Your health will be restored at midnight.

<br /><br /><b><u><a name=\"toolbar\">The Toolbar</u></b>

<br /><br /><b><a name=\"laptop\">The laptop</a></b><br />
The laptop displays all your fight logs, showing who has fought you, and what the outcome was, including any of the final penalties.

<br /><br /><b><a name=\"inventory\">Inventory</a></b><br />
This section displays all of your current weapons and armour. Once an item has been bought, it will appear here. To equip yourself with it, you must first unequip your previous weapon and then equip yourself with your new one.

<br /><br /><b><a name=\"tokenbank\">Token Bank</a></b><br />
This is basically your campus piggy bank. All the tokens you win will appear here, as will everything you earn from working. You can deposit all the tokens from your pocket into the bank, and you can withdraw tokens from the bank to your pocket. You can also claim daily interest from the bank, which will appear on your pocket. You can also donate tokens to your friends by going on their profile and selecting how much you wish to donate to them.

<br /><br /><b><a name=\"hospitalwing\">Hospital Wing</a></b><br />
This is where you should go after losing a fight. You can be healed, and you can select how much, by paying in tokens.

<br /><br /><b><a name=\"combattraining\">Combat Training</a></b><br />
One of the most important parts of the game, this is the (newly refurbished) campus multi combat training centre. You can call up anyone on campus to fight, and battle them. The winner is determined by who has the better level, and also the best weapons and armour.

<br /><br /><b><a name=\"equipmentstore\">Equipment Store</a></b>
<br />There will be a selection of what you can purchase, assuming you have enough tokens. To purchase it just click on the link labled 'buy' on the right hand side of the item under the price.

<br /><br /><b><a name=\"workfortokens\">Work for Tokens</a></b>
<br />If you are in need of urgent tokens, have no fear! Simply go to work in the canteen! It will deduct an energy point off you, and the amount of tokens you will recieve depends on your level.

<br /><br /><b><u><a name=\"howdoi?\">How do I?</a></u></b>

<br /><br /><b><a name=\"changeemail\">I have changed my e-mail or I want to change my password. How can I change it on the game?</a></b><br />
If you click on 'Edit Profile' on the left menu, you can change it, as well as changing your nickname, and a short description about yourself that will be displayed to other users. Remember that if you forget your password, you will need a valid e-mail address to retrieve it, as well as important admin/game information being sent to it, and you dont want to miss out!

<br /><br /><b><a name=\"getweapons\">I want to get armour or weapons, how do I access it?</a></b>
<br />Go to the Equipment store from the left \"Game Menu\" There will be a selection of what you can purchase, assuming you have enough tokens. To purchase it just click on the link labled 'buy' on the right hand side of the item under the price.

<br /><br /><b><a name=\"whofought\">I want to see who has fought me, can I?</a></b><br />
You can indeed. If you have a look at the left hand. Login to your laptop's email program

<br /><br /><b><a name=\"levels\">How do I move up a level?</a></b><br />
This is based on your current EXP, or Experience. To make your EXP larger (and thus levelling up), win fights with other agents. Also, the higher your level, the more tokens you will be able to earn through doing work.

<br /><br /><b><a name=\"staff\">I want to be a staff member. Can I?</a></b><br />
Im sorry, we do not need any more staff at the present time. Any future vancancies will be advertised on the forum.


<br /><br /><b><a name=\"friends\">I want to have a friends list.</a></b><br />
Simply go on the Member List, select the person you want on your friends list, and click 'Invite to be friend'


<br /><br /><b><a name=\"gangs\">I want to join up with my friends on the game, in a group or gang. How do I do it?</a></b><br />
This feature is currently under construction and will be available in the near future.


<br /><br /><b><a name=\"cheater\">I am on the list of cheaters, and I feel that I shouldn't be. Can you take me off?</a></b><br />
You can either make a support ticket, and your query will be dealt with promptly, or directly mail one of the admins, a list of whom is under 'Our staff' in 'Community' section of the menu. If you are there because you have cheated, you will stay there for as long as the staff see necessary.

<br /><br />";

?>