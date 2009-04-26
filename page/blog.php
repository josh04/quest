<?

if (isset($_POST['mycomment'])) {

$mycomment = htmlentities($_POST['mycomment']);

$starttag=strpos($mycomment,"[r]");
$endtag=strpos($mycomment,"[/r]");

$reply = substr($mycomment,($startag)+3,$endtag);

$replyaq = $db->execute("SELECT * FROM `blog_comments` WHERE `id`='$reply'");
$replya = $replyaq->fetchrow();

if (!($replya['reply']=="0")) $reply="0";

$mycomment = preg_replace('/\[r\](.*?)\[\/r\]/is',"",$mycomment);

$myid = $player->id;

$myarr = array($reply,$myid,$_GET['id'],$mycomment);
$query = $db->execute("INSERT INTO `blog_comments` (reply,author,subject,body) VALUES (?,?,?,?)",$myarr);
}

if (isset($_GET['id'])) {
$blogq = $db->execute("SELECT * FROM blog WHERE `id`='$_GET[id]'");
} else {
$blogq = $db->execute("SELECT * FROM blog ORDER BY id DESC"); }

$display->blog_page($blogq); ?>