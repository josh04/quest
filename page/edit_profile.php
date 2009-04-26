<?php
// edit_profile.php : User can change their own profile.
// Change profile OR password.


switch($_GET['action']){
  case "profile":
    $showemail = (isset($_POST['showemail'])) ? 1 : 0;
    $profile = $db->execute("UPDATE players
                           SET email=?, 
                               description=?, 
                               show_email=?, 
                               gender=?, 
                               msn=?, 
                               aim=?, 
                               skype=?, 
                               avatar=? 
                           WHERE `id`=?", 
                           array($_REQUEST['email'], 
                                 $_REQUEST['description'], 
                                 $showemail,
                                 $_REQUEST['gender'], 
                                 $_REQUEST['msn'], 
                                 $_REQUEST['aim'], 
                                 $_REQUEST['skype'], 
                                 $_REQUEST['avatar'], 
                                 $player->id ) );
    ($profile) ? "" : $display->simple_page("There was a problem updating your profile.","","bred");
    $display->simple_page("Your profile has been edited.","","byellow");
    break;
  case "password":
    if ($_POST['new'] != $_POST['confirm']) {
      $display->simple_page("The two passwords did not match.", "", "bred");
    } elseif (sha1($_POST['current']) != $player->password) {
      $display->simple_page("Incorrect Password.", "", "bred");      
    } else {
      $password = $db->execute("UPDATE players
                                SET password=?
                                WHERE id=?",
                                array(sha1($_POST['new']), $player->id));
      ($password) ? "" : $display->simple_page("Error updating password.","","bred");;
      session_unset();
      session_destroy();
      $display->simple_page("Password Updated.","","byellow");
    }
    break;
  case "disp":
    $profile = $db->execute("UPDATE players
                           SET `skin`=? 
                           WHERE `id`=?", 
                           array($_REQUEST['skin'],
                                 $player->id ) );

    header("location:index.php?page=editprofile&action=display");
    break;
  case "display":
    $display->simple_page("Your display options have been changed.","","byellow");
    break;
  default:
    $display->edit_profile();
}

?>
