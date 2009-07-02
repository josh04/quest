 <?php

  //forum_topics($topic)
  $topic = $a[0];

			while($topic1 = $topic->fetchrow()) {

$author = new player;
$author->db =& $this->db;
$var = $author->get_player_by_id($topic1['author']);

							$echotopic .= "<tr class='row'>
				<td width='30%'>".$author->username."</td>
				<td width='50%'><a href=\"index.php?page=forums&action=topic&topicid=".$topic1['id']."\">".$topic1['title']."</a></td>
				<td width='20%'>".date("F j, Y, g:i a", $topic1['date'])."</td>
				</tr>";
  }
		$this->html = "
		
		<a href='index.php?page=forums'>Forum Home</a> | <a href='index.php?page=forums&action=newtopic'>New Topic</a> 
		<br />
		
		Welcome to the forums<br />
		
		<table width='100%' border='0'>
		<tr>
		<th width='30%'><b>Author</b></td>
		<th width='50%'><b>Content</b></td>
		<th width='20%'><b>Date</b></td>
		</tr>
		".$echotopic."
		</table>
		<br /><!--Other stuff here-->";
  $this->final_output();


?>

