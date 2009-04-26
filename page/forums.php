<?
switch($_GET['action']) {
	case "topic":
	
	$topic = $db->execute("SELECT * FROM forum_topics WHERE id=?", array($_REQUEST['topicid']));
($topic) ? "" : print "Could not retrive topics.";


	$reply = $db->execute("SELECT * FROM forum_replies WHERE topic_id=?", array($_REQUEST['topicid']));
($reply) ? "" : print "Could not retrive topic.";



if ($topic->recordcount() > 0)
{
  $display->read_topic($topic, $reply);
}
else
{
	$display->simple_page("Topic does not exist.","","bred");
}
	break;
	
	case "newtopic":
		
if ($_POST['posttopic']) {
		if (!$_POST['content']) {
		$display->simple_page("Box can not be left blank!","","bred");
	}
	else {
		$content = htmlentities($_POST['content'],ENT_COMPAT,'UTF-8');
		$title = ($_POST['title'] == "") ? "No Title" : htmlentities($_POST['title'],ENT_COMPAT,'UTF-8');

		$sent = $db->execute("INSERT INTO forum_topics (`content`, `title`, `author`, `date`) 
                          VALUES (?,?,?,?)", 
                          array($content,
                                $title,
                                $player->id,
                                time()));
		$display->simple_page(($sent) ? "The thread has been posted" : "The thread was not posted.");
	}
}	
{
	  $display->new_topic();
}

	  break;
	
		case "reply":
		
if ($_POST['replytopic']) {

		if (!$_POST['content']) {
		$display->simple_page("Box can not be left blank!","","bred");
	}
	else if ($_REQUEST['topic_id'] == 0) {
	
		$display->simple_page("That topic does not exist.","","bred");
}

	else {
		$content = htmlentities($_REQUEST['content'],ENT_COMPAT,'UTF-8');
		$topic_id =$_REQUEST['topic_id'];

		$sent = $db->execute("INSERT INTO forum_replies (`content`, `topic_id`, `author`, `time`) 
                          VALUES (?,?,?,?)", 
                          array($content,
                                $topic_id,
                                $player->id,
                                time()));
		$display->simple_page(($sent) ? "The thread has been posted" : "The thread was not posted.");
	}
}	
{
	  $display->reply_topic();
}

	  break;
	
	default:
$topic = $db->execute("SELECT * FROM forum_topics ORDER BY date DESC");
($topic) ? "" : print "Could not retrieve topics.";



if ($topic->recordcount() > 0)
{
  $display->forum_topics($topic);
}
else
{
	$display->simple_page("No topics.");
}
	break;
}

function verifyBBCode($data) {
 
    $data = str_replace("\n", '\newline\\', $data); 
 
    # Which BBCode is accepted here
	$bbcodedata = array(
	
	 'bold' => array(
	  'start' => array('[b]', '\[b\](.*)', '<b>\\1'),
	  'end' => array('[/b]', '\[\/b\]', '</b>'),
	 ),
	 
	 'underline' => array(
	  'start' => array('[u]', '\[u\](.*)', '<u>\\1'),
	  'end' => array('[/u]', '\[\/u\]', '</u>'),
	 ),
	 
	 'italic' => array(
	  'start' => array('[i]', '\[i\](.*)', '<i>\\1'),
	  'end' => array('[/i]', '\[\/i\]', '</i>'),
	 ),
	 
	 'image' => array(
	  'start' => array('[img]', '\[img\](http:\/\/|ftp:\/\/)(.*)(.jpg|.jpeg|.bmp|.gif|.png)', '<img src=\'\\1\\2\\3\' />'),
	  'end' => array('[/img]', '\[\/img\]', ''), 
	 ),
	 
	 'url1' => array(
	  'start' => array('[url]', '\[url\](http:\/\/|ftp:\/\/)(.*)', '<a href=\'\\1\\2\'>\\1\\2'),
	  'end' => array('[/url]', '\[\/url\]', '</a>'),
	 ),
	 
	 'url2' => array(
	  'start' => array('[url]', '\[url=(http:\/\/|ftp:\/\/)(.*)\](.*)', '<a href=\'\\1\\2\'>\\3'), 
	  'end' => array('[/url]', '\[\/url\]', '</a>'),
	 ),
	 
	 'code' => array(
	  'start' => array('[code]', '\[code\](.*)', 'CODE : <br /><div id="code">\\1'),
	  'end' => array('[/code]', '\[\/code\]', '</div>'),
	 ),
	 
	);
	
	foreach( $bbcodedata as $k => $v )
	 {
	   $data = preg_replace("/".$v['start'][1].$v['end'][1]."/i", $v['start'][2].$v['end'][2], $data);
	 }
	 
	$data = str_replace('\newline\\', '<br />', $data); 
	 
	 return $data;
 }
?>