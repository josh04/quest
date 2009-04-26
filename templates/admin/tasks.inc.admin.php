<?php
  //function admin_tasks($tasks)
  $tasks = $a[0];
  
    foreach($tasks as $task){ // Sort the tasks into two.
      ($task['urgent']=='1') ? $urgent = "<img src='images/urgent_icon.png' />" : $urgent = "";
      if ($task['completed']) {
          $numcomplete++;
          $complete .= "<tr><td>" . $urgent . 
          "<a class='nochange' href='javascript:show_notes(\"det" . $task['id'] . "\")'>
           " . $task['name'] . "</a></td>
          <td>". $task['username'] ."</td>
          <td>". $task['completed_byname'] ."</td>
          </tr>
          <tr><td colspan='3'>
          <div style='display:none;' id='det" . $task['id'] . "'>
          <div class='bblue' id='tex" . $task['id'] . "'>" . nl2br($task['msg']) . "</div>
          </div></td></tr>";
        } else {
          $numincomplete++;
          $incomplete .= "<tr><td>" . $urgent . 
          "<a class='nochange' href='javascript:show_notes(\"det" . $task['id'] . "\")'>
          " . $task['name'] . "</a></td>
          <td>". $task['username'] ."</td>
          <td><input type='checkbox' name='done" . $task['id'] . "' /></td>
          </tr>
          <tr><td colspan='3'>
          <div style='display:none;' id='det" . $task['id'] . "'>
          <textarea class='bred' name='tex" . $task['id'] . "' id='tex" . $task['id'] . "' rows='4' cols='50'>" . $task['msg'] . "</textarea>
          <br /><input type='checkbox' name='update" . $task['id'] . "' /> Update?
          </div></td></tr>";
        }
      }
$this->html .= "
<h1>Task List</h1>

<p>Add <a class='nochange' href='javascript:show_notes(\"newtask\")'>new task</a>:<br />
<div id='newtask' style='display:none;'>
<form action='admin.php?page=tasks' method='post'>
<fieldset>

<table>
<tr><td>Task Name</td><td>:</td>
<td><input type='text' name='newname' maxlength='65' /></td></tr>
<tr><td>Task Notes</td><td>:</td>
<td><textarea name='newnotes' rows='4' cols='25'></textarea></td></tr>
<tr><td>Urgent?</td><td>:</td>
<td><input type='checkbox' name='newurgent' /></td></tr>
<tr><td><input type='submit' name='newsubmit' value='Add Task' /></td></tr>
</table>

</fieldset>
</form>
</div></p>


<p>There are ".$numincomplete." tasks that need completing:</p>

<form action='admin.php?page=tasks' method='post'>
<table>
<tr><th style='width:90%;'>Task</th>
<th style='width:10%;'>Added By</th>
<th></th></tr>

".$incomplete."

</table>
<input type='submit' value='Submit Changes' />
</form>


<p>There are ".$numcomplete." tasks that have been completed:</p>


<table>
<tr>
<th style='width:85%;'>Task</th>
<th style='width:15%;'>Added By</th>
<th style='width:15%;'>Completed</th>
</tr>

".$complete."

</table>

";
      $this->final_output();

?>
