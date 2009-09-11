<?php
/**
 * Description of skin_profile
 *
 * @author josh04
 * @package skin_public
 */
class angst_skin_profile extends angst_skin_common {

   public $gender_array =   array(array("Undisclosed","_gray"),
                            array("Female","_female"),
                            array("Male",""));

  /**
   * makes the profile page
   *
   * @param player $profile array of profile links
   * @param string $friendlist the pre-formatted friend list
   * @param string $message any errors that have been drawn
   * @return string html
   */
   function make_profile($profile, $friendlist, $interaction, $message='') {
        $make_profile = "
            ".$message."

            <img src='".$profile->avatar."' alt='[user avatar]' class='avatar' style='margin:8px;float:left;' />
            <h1 style='margin-bottom:8px;'><img src='images/icons/status_".$profile->is_online.".png' style='padding:0px 4px;' alt='' />".$profile->username."</h1>

            <span style='font-size:10px;'>Joined ".$profile->registered."</span>
            <table><tr><td style='width:50%;'><div class='glee'><h3>".$profile->glee_count."</h3></div></td><td style='width:50%;'><div class='angst'><h3>".$profile->angst_count."</h3></div></td></tr>

            <tr><td rowspan='2'>
            <h3>Contact</h3>
            ".$profile->im_links."

            </td><td>
            <h3>Description</h3>
            ".$profile->description."
            </td></tr>

            <tr><td class='profile-interact'>
            <h3>Interact</h3>
            ".$interaction."
            </td></tr>

            <tr><td colspan='2'>
            <h3>Friends (".$profile->friendcount.")</h3>
            <div class='friendwrapper'>
            ".$friendlist."
            </div>
            </td></tr>
            </table>";
         return $make_profile;
   }

  /**
   * add an interaction link
   *
   * @param string $link where to go to
   * @param string $caption the text of the link
   * @return string html
   */
   function add_mail_link($username) {
       $add_interact_link = "<a href='index.php?page=mail&amp;action=compose&amp;to=".$usename."'>Mail</a>";
       return $add_interact_link;
   }

  /**
   * add an interaction link
   *
   * @param string $link where to go to
   * @param string $caption the text of the link
   * @return string html
   */
   function add_edit_link() {
       $add_interact_link = "<a href='index.php?page=profile_edit'>Edit your profile</a>";
       return $add_interact_link;
   }

  /**
   * add an interaction link
   *
   * @param string $link where to go to
   * @param string $caption the text of the link
   * @return string html
   */
   function add_friend_link($id) {
       $add_interact_link = "<a href='index.php?page=profile&amp;id=".$id."&amp;friends=add'>Add as Friend</a>";
       return $add_interact_link;
   }

  /**
   * add a link to battle 'em
   * 
   * @param integer $id the id of the player we're fighting
   * @return string html
   */
   function add_battle_link($id) {
       $add_interact_link = "<form action='index.php?page=battle&amp;action=fight' name='profileBattleLink' method='post'>
           <input type='hidden' name='id' value='".$id."' />
           <a href='#' onclick='document.profileBattleLink.submit();'>Battle</a></form>";
       return $add_interact_link;
   }

   /**
    * adds a new field to the stats
    * 
    * @param string $field the field name
    * @param string $value the field value
    * @return string html
    */
    function extra_link($field, $value) {
        $extra_link = "<label>".$field.":</label> ".$value;
        return $extra_link;
    }

   /**
    * generates html for avatar
    *
    * @param string $avatar_url url of avatar
    * @return string html
    */
    function player_avatar($avatar_url = "images/avatar.png") {
        $player_avatar = "<img src='".$avatar_url."' alt='User Avatar'><br />";
        return $player_avatar;
    }


   /**
    * makes the edit profile page
    *
    * @param player $profile profile to display
    * @param string $gender_list gender html
    * @param string $show_email show email?
    * @param string $section if this is the admin use of the form.
    * @param string $message error message?
    * @return string html
    */
    function edit_profile($profile, $gender_list, $show_email, $section, $message="") {
        $edit_profile = "
                            <h2>Edit profile</h2>
                            ".$message."
                                <a href='#' style='color:transparent;' onClick='showHide(\"showhide-1\");'><div class='edit-profile-header'><img id='showhide-1-icon' src='images/dropdown_open.png' style='float:right;' alt='&laquo;' />Edit Profile</div></a>
                                <div class='edit-profile-body' id='showhide-1'><form action='index.php?".$section."page=profile_edit&amp;action=update_profile' method='POST'>
                                <input type='hidden' name='id' value='".$profile->id."' />
                                <table>
                                    <tr><td style='width:50%;'><label style='margin:0;'>Username</label></td>
                                    <td style='width:50%;'><strong>".$profile->username."</strong></td></tr>

                                    <tr><td><label for='edit-email'>Email</label></td>
                                    <td><input type='text' id='edit-email' name='email' value='".$profile->email."' /></td></tr>

                                    <tr><td><label for='edit-show-email' style='margin:0;'>Show email</label></td>
                                    <td><input type='checkbox' id='edit-show-email' name='show_email' ".$show_email." /></td></tr>

                                    <tr><td><label for='edit-description'>Description</label></td>
                                    <td><input type='text' id='edit-description' name='description' value='".$profile->description."'/></td></tr>

                                    <tr><td><label for='edit-avatar'>Avatar URL</label></td>
                                    <td><input type='text' id='edit-avatar' name='avatar' value='".$profile->avatar."'/></td></tr>

                                    <tr><td><label for='edit-gender'>Gender</label></td>
                                    <td><select id='edit-gender' name='gender'>".$gender_list."</select></td></tr>

                                    <tr><td><label for='edit-msn'>MSN address</label></td>
                                    <td><input type='text' id='edit-msn' name='msn' value='".$profile->msn."'/></td></tr>

                                    <tr><td><label for='edit-aim'>AIM address</label></td>
                                    <td><input type='text' id='edit-aim' name='aim' value='".$profile->aim."'/></td></tr>

                                    <tr><td><label for='edit-skype'>Skype ID</label></td>
                                    <td><input type='text' name='skype' value='".$profile->skype."'/></td></tr>

                                    <tr><td colspan='2'><input type='submit' name='submit' value='Submit' /></td></tr>
                                </table>

                                </form></div>

                                ";

        return $edit_profile;
    }

   /**
    * Edit avatar code
    *
    * @param string $avatar_options the various options, specified in the admin panel
    * @return string html
    */
    public function edit_avatar($avatar_options) {
        $edit_avatar = "<a href='#' style='color:transparent;' onClick='showHide(\"showhide-2\");return false;'><div class='edit-profile-header'><span style='float:right;'>&laquo;</span>Change Avatar</div></a>
            <div class='edit-profile-body' id='showhide-2' style='display:none;'><form action='index.php?page=profile_edit&amp;action=update_avatar' method='POST' enctype='multipart/form-data'>
            <table>
                " . $avatar_options . "
                <tr><td colspan='2'><input type='submit' name='submit' value='Submit'/></td></tr>
            </table></form>
            </div>";
        return $edit_avatar;
    }

   /**
    * Edit avatar by URL
    *
    * @return string html
    */
    public function edit_avatar_url() {
        $edit_avatar_url = "
                <tr><td style='width:50%;'><label for='edit-avatar-url'>
                    <input type='radio' id='avatar_type_url' name='avatar_type' value='url' />
                    Enter a URL</label>
                </td>
                <td style='width:50%;'><input type='text' onfocus='document.getElementById(\"avatar_type_url\").checked=true;' id='edit-avatar-url' name='avatar_url' value='".$_POST['avatar_url']."' /></td></tr>
        ";
        return $edit_avatar_url;
    }

   /**
    * Use a gravatar
    *
    * @param string $email the player's email address
    * @return string html
    */
    public function edit_avatar_gravatar($email) {
        $edit_avatar_gravatar = "
                <tr><td style='width:50%;'><label for='edit-avatar-gravatar'>
                    <input type='radio' id='avatar_type_gravatar' name='avatar_type' value='gravatar' />
                    Use your gravatar</label>
                </td>
                <td style='width:50%;'><input type='hidden' id='edit-avatar-gravatar' name='avatar_gravatar' value='http://www.gravatar.com/avatar/".md5($email)."' />
                <img src='http://www.gravatar.com/avatar/".md5($email)."?s=40' alt='Your gravatar' style='height:40px;' /></td></tr>
        ";
        return $edit_avatar_gravatar;
    }

   /**
    * Upload your avatar
    *
    * @return string html
    */
    public function edit_avatar_upload() {
        $edit_avatar_upload = "
                <tr><td style='width:50%;'><label for='edit-avatar-upload'>
                    <input type='hidden' name='MAX_FILE_SIZE' value='100000' />
                    <input type='radio' id='avatar_type_upload' name='avatar_type' value='upload' />
                    Upload an avatar</label>
                </td>
                <td style='width:50%;'><input type='file' onfocus='document.getElementById(\"avatar_type_upload\").checked=true;' id='edit-avatar-upload' name='avatar_upload' /></td></tr>
        ";
        return $edit_avatar_upload;
    }

   /**
    * Use an avatar from the library
    *
    * @param string $library the library options
    * @return string html
    */
    public function edit_avatar_library($library) {
        $edit_avatar_library = "
                <tr><td style='width:50%;'><label for='edit-avatar-library'>
                    <input type='radio' id='avatar_type_library' name='avatar_type' value='library' />
                    Choose from the library</label>
                </td>
                <td style='width:50%;'><img src='' alt='' id='avatar_preview_library' style='display:block;height:40px;' /></td></tr>
                <tr><td colspan='2'><select onfocus='document.getElementById(\"avatar_type_library\").checked=true;' onchange='document.getElementById(\"avatar_preview_library\").src=\"images/library/\"+this.value;' id='edit-avatar-library' name='avatar_library' style='width:100%;'>
                <option disabled='disabled' selected='selected'>Select an avatar:</option>".$library."</select></td></tr>
        ";
        return $edit_avatar_library;
    }

   /**
    * Edit password code, seperated.
    *
    * @return string html
    */
    public function edit_password() {
        $edit_password = "<a href='#' style='color:transparent;' onClick='showHide(\"showhide-2\",\"showhide-2-icon\");'><div class='edit-profile-header'><img id='showhide-2-icon' src='images/dropdown_open.png' style='float:right;' alt='&laquo;' />Change Password</div></a>
            <div class='edit-profile-body' id='showhide-2'><form action='index.php?page=profile_edit&amp;action=update_password' method='POST'>
            <table>
                <tr><td style='width:50%;'><label for='edit-p1'>Current password</label></td>
                <td style='width:50%;'><input type='password' id='edit-p1' name='current_password' /></td></tr>

                <tr><td><label for='edit-p2'>New password</label></td>
                <td><input type='password' id='edit-p2' name='new_password' /></td></tr>

                <tr><td><label for='edit-p3'>Confirm new password</label></td>
                <td><input type='password' id='edit-p3' name='confirm_password' /></td></tr>

                <tr><td colspan='2'><input type='submit' name='submit' value='Submit'/></td></tr>
            </table></form>
            </div>";
        return $edit_password;
    }

   /**
    * Edit password code, seperated.
    *
    * @return string html
    */
    public function twitter_auth() {
        $edit_password = "<a href='#' style='color:transparent;' onClick='showHide(\"showhide-4\",\"showhide-4-icon\");'><div class='edit-profile-header'><img id='showhide-4-icon' src='images/dropdown_open.png' style='float:right;' alt='&laquo;' />Twitter Account</div></a>
            <div class='edit-profile-body' id='showhide-2'><form action='index.php?page=profile_edit&amp;action=twitter_auth' method='POST'>
            <table>
                <tr><td style='width:50%;'><label for='edit-p1'>Twitter Username</label></td>
                <td style='width:50%;'><input type='text' id='edit-t1' name='twitter_username' /></td></tr>

                <tr><td><label for='edit-p2'>Twitter Password</label></td>
                <td><input type='password' id='edit-t2' name='twitter_password' /></td></tr>

                <tr><td colspan='2'><input type='submit' name='submit' value='Submit'/></td></tr>
            </table></form>
            </div>";
        return $edit_password;
    }

   /**
    * fer makin people admins
    *
    * @param string $permission_options list of options
    * @param int $id player id
    * @return string html
    */
    public function edit_permissions($permission_options, $id) {
        $edit_permissions = "<a href='#' style='color:transparent;' onClick='showHide(\"showhide-3\",\"showhide-3-icon\");'><div class='edit-profile-header'><img id='showhide-3-icon' src='images/dropdown_open.png' style='float:right;' alt='&laquo;' />Edit Permissions</div></a>
            <div class='edit-profile-body' id='showhide-3'><form action='index.php?section=admin&amp;page=profile_edit&amp;action=update_permissions' method='POST'>
            <input type='hidden' name='id' value='".$id."' />
            <table>
                <tr><td style='width:50%;'><label for='edit-p1'>Permission Level</label></td>
                <td style='width:50%;'><select id='edit-p1' name='rank' />
                    ".$permission_options."
                </td></tr>
                <tr><td colspan='2'><input type='submit' name='submit' value='Submit'/></td></tr>
            </table></form>
            </div>";
        return $edit_permissions;
    }

   /**
    * options!
    *
    * @param string $current_permission
    * @return string html
    */
    public function permissions_list($current_permission) {
        $current_permission = "permission_".$current_permission;
        $permission_Member .= "<option value='Member'";
        $permission_Admin .= "<option value='Admin'";
        $$current_permission .= " selected='selected'";
        $permission_Member .= ">Member</option>";
        $permission_Admin .= ">Admin</option>";
        $permission_list = $permission_Member.$permission_Admin;
        return $permission_list;
    }

   /**
    * Sneakily placed here. I'm such a cheat.
    *
    * @param int $id member id
    * @return string html
    */
    public function edit_password_admin($id) {
        $edit_password = "<a href='#' style='color:transparent;' onClick='showHide(\"showhide-2\",\"showhide-2-icon\");'><div class='edit-profile-header'><img id='showhide-2-icon' src='images/dropdown_open.png' style='float:right;' alt='&laquo;' />Change Password</div></a>
            <div class='edit-profile-body' id='showhide-2'><form action='index.php?section=admin&amp;page=profile_edit&amp;action=update_password' method='POST'>
            <input type='hidden' name='id' value='".$id."' />
            <table>
                <tr><td><label for='edit-p2'>New password</label></td>
                <td><input type='password' id='edit-p2' name='new_password' /></td></tr>

                <tr><td><label for='edit-p3'>Confirm new password</label></td>
                <td><input type='password' id='edit-p3' name='confirm_password' /></td></tr>

                <tr><td colspan='2'><input type='submit' name='submit' value='Submit'/></td></tr>
            </table></form>
            </div>";
        return $edit_password;
    }

   /**
    * list of gender choices
    *
    * @param int $current_gender current gender
    * @return string html
    */
    public function gender_list($current_gender) {
        $current_gender = "gender_".$current_gender;
        $gender_0 .= "<option value='0'";
        $gender_1 .= "<option value='1'";
        $gender_2 .=" <option value='2'";
        $$current_gender .= " selected='selected'";
        $gender_0 .= ">Undisclosed</option>";
        $gender_1 .= ">Female</option>";
        $gender_2 .= ">Male</option>";
        $gender_list = $gender_0.$gender_2.$gender_1;
        return $gender_list;
    }

   /**
    * displays gender
    *
    * (TODO) need a proper place for images. in skin, i guess?
    *
    * @param int $gender gender
    * @return string html
    */
    public function gender($gender) {
        $gender = "<img src=\"../icons/user".$this->gender_array[$gender][1].".png\" alt=\"".$this->gender_array[$gender][0]."\" title=\"".$this->gender_array[$gender][0]."\" />";
        return $gender;
    }

   /**
    * an friend entry in a friend list
    *
    * @param array $friend friend to listify
    * @return string html
    */
    public function friend_entry($friend) {
        $friend_entry .= "<div class='friendentry'>
                    <img src='".$friend['avatar']."' alt='[user avatar]' class='avatar' /><br />
                    <a href='index.php?page=profile&amp;id=3'>".$friend['username']."</a><br />
                </div>";
        return $friend_entry;
    }

   /**
    * Donate to charity?
    *
    * @param int $amount amount donated
    * @param string $name name of donatee
    * @return string html
    */
    public function donated($amount, $name) {
        $donated = "<div class='success'>You donated £".$amount." to ".$name.".</div>";
        return $donated;
    }

   /**
    * Admin approval form
    *
    * @param array $user player details
    * @param string $message anything wrong?
    * @return string html
    */
    public function approve_user($player, $message='') {
        $approve_user = "<form action='' method='post'>
            <div style='border:1px solid #CCC;padding:8px;'><h3>".$player['username']."</h3>
            <strong>Registered: </strong>".$player['registered']."<br />
            <strong>Email address: </strong>".$player['email']."
            <p><input type='submit' name='approve' value='Approve user' /></p>
            </div></form>";
        return $approve_user;
    }

}
?>
