 <?php
  //function read_topic($topic, $reply)
  $topic = $a[0];
  $reply = $a[1];

			while($topic1 = $topic->fetchrow()) {
			

    $author = new player;
    $author->db =& $this->db;
    $var = $author->get_player_by_id($topic1['author']);

				$echotopic .= "<tr class='row'>
				<td width='30%'>".$author->username."</td>
				<td width='50%'>".verifyBBCode($topic1['content'])."</td>
				<td width='20%'>".date("F j, Y, g:i a", $topic1['date'])."</td>
				</tr>";
				$title = $topic1['title'];
    }
			while($topic2 = $reply->fetchrow()) {
			
    $author = new player;
    $author->db =& $this->db;
    $var = $author->get_player_by_id($topic2['author']);

			
				$echotopic1 .= "<tr class='row'>
				<td width='30%'>".$author->username."</td>
				<td width='50%'>".verifyBBCode($topic2['content'])."</td>
				<td width='20%'>".date("F j, Y, g:i a", $topic2['time'])."</td>
				</tr>";
  }
		$this->html = 
		
		          "<a href='index.php?page=forums'>Forum Home</a> | <a href='index.php?page=forums&action=reply&topic_id=".$_REQUEST['topicid']."'>Reply to Topic</a> <br />
".$title."<br />
		
		$topic_id
		<table width='100%' border='0'>
		<tr>
		<th width='30%'><b>Author</b></td>
		<th width='50%'><b>Content</b></td>
		<th width='20%'><b>Date</b></td>
		</tr>
		".$echotopic."
		".$echotopic1."
		</table>
		<br />Other stuff here";
    $this->final_output();

?>

