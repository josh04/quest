<?php

// THIS PART ADDS NEW TASKS

if(isset($_POST['newname'])) {
  $query = $db->execute("INSERT INTO tasks (name, msg, urgent, msg_by, date) VALUES (?,?,?,?,?)", 
                        array($_POST['newname'], $_POST['newnotes'], 
                              intval(isset($_POST['newurgent'])), $player->id, time()));
      $message .= ($query) ? "Task Added.<br />" 
               : "Error adding task.";
}

// THIS PART MAKES ANY NOTE CHANGES ON THE DATABASE AND UPDATES COMPLETED TASKS

$taskquery = $db->execute("SELECT t.*, p.username, l.username AS completed_byname 
                           FROM tasks AS t 
                           LEFT JOIN players AS p ON t.msg_by=p.id 
                           LEFT JOIN players AS l ON t.completed_by=l.id 
                           ORDER BY t.date DESC"); // Query gets the task and the two needed usernames.

if($taskquery){
  while($task = $taskquery->fetchrow()) {
    $noteupdateid = "update" . $task['id'];
    $notetext = "tex" . $task['id'];
    if(isset($_POST[$noteupdateid])) {
      $query = $db->execute("UPDATE tasks SET msg=? WHERE id=?", 
                            array($_POST[$notetext], $task['id']));
      $task['msg'] = $_POST[$notetext]; // Update our copy, so the updated version displays.
      $message .= ($query) ? "Task '".$task['name']."'' updated.<br />" 
               : "Error updating task ".$task['id'].".";
    }
    
    $notecompleteid = "done" . $task['id'];
    if(isset($_POST[$notecompleteid])){
      $query = $db->execute("UPDATE tasks SET completed='1', completed_by=? 
                             WHERE id=?", 
                            array($player->id, $task['id']));
      $task['completed'] = '1'; // Update our copy.
      $task['completed_byname'] = $player->name;
      $message .= ($query) ? "Task '".$task['name']."'' completed.<br />" 
               : "Error completing task ".$task['id'].".";
    }
    $tasks[] = $task; // We add it to our tasks variable at the end so our updates are included.
  }
  
  $display->tasks($tasks);
} else {
  print "Error retrieving tasks.";
}

?>
