<?php
/**
 * Description of skin_profile
 *
 * @author josh04
 * @package skin_public
 */
class skin_profile extends skin_common {
   
  /**
   * makes the profile page
   *
   * @param player $profile array of profile links
   * @return string html
   */
   function make_profile($profile) {
        $make_profile = "
            <fieldset>
            <legend><b>".$profile->username."</b>'s Profile</legend><br />
            <div>

            <!-- LHS -->
            <strong>Basic Info</strong>

            Username: ".$profile->username."<br />
            <a title='User is ".$profile->is_online."'><img src='images/icons/status_".$profile->is_online.".png' /></a><br />
            <i>".$profile->description."<i><br />

            ".$profile->avatar."<br />

            Gender: ".$profile->gender."<br />
            Registered: ".$profile->registered."<br />
            In-Game Age: ".$profile->age." days<br />

            <strong>Game Info</strong><br />
            Level: ".$profile->level."<br />
            Status: ".$profile->colour."<br />
            Wins: ".$profile->kills."<br />
            Losses: ".$profile->deaths."<br />

            <strong>Contact</strong><br />

            <strong>You can contact ".$profile->username." in the following ways:</strong><br />
            <i><a href='index.php?page=mail&amp;action=compose&amp;to=".$profile->username."' class='faq'>CHERUB Internal Mail</a></i><br />
            ".$profile->im_links."

            <strong>Friends</strong><br />
            <ul>
            ".$profile->friends."<br />
            </ul>
            ".$profile->edit."<br />
            <form action='index.php?page=profile&amp;id=".$profile->id."' method='post'>
            <input type='text' name='donate' />
            <input type='submit' accesskey='S' value='Donate to ".$profile->username."' />
            </form>
            </div>
            </fieldset> ";
         return $make_profile;
   }

   /**
    * generates html for instant messenger link
    * 
    * @return string html
    */
    function im_link($im, $address) {
        $im_link = "<i>".$im."</i>: ".$address."<br />";
        return $im_link;
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
    * @param string $message error message?
    * @return string html
    */
    function edit_profile_page($profile, $gender_list, $show_email, $message="") {
        $edit_profile_page = "
                            <fieldset>
                                <legend>Edit Profile</legend>
                                <div><form action='index.php?page=profile_edit&amp;action=update_profile' method='POST'>

                                    Username: <strong>".$profile->username."</strong>

                                    Email: <input type='text' name='email' value='".$profile->email."' />

                                    Show Email: <input type='checkbox' name='show_email' ".$show_email." />

                                    Description: <input type='text' name='description' value='".$profile->description."'/>

                                    Avatar URL: <input type='text' name='avatar' value='".$profile->avatar."'/>

                                    Gender: <select name='gender'>".$gender_list."</select>

                                    MSN Address: <input type='text' name='msn' value='".$profile->msn."'/>

                                    AIM Address: <input type='text' name='aim' value='".$profile->aim."'/>

                                    Skype ID: <input type='text' name='skype' value='".$profile->skype."'/>
                                    <input type='submit' name='submit' value='Submit' />

                                </form></div>
                            </fieldset>


                            <fieldset>
                                <legend>Change Password</legend>
                                <div><form action='index.php?page=profile_edit&amp;action=update_password' method='POST'>

                                    Current password:
                                    <input type='password' name='current_password' />

                                    New password: <input type='password' name='new_password' />

                                    Confirm New Password: <input type='password' name='confirm_password' />
                                    </div>
                                    <input type='submit' name='submit' value='Submit'/>
                                </form></div>
                            </fieldset> ";
        return $edit_profile_page;
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
        $gender_0 .= ">Unknown</option>";
        $gender_1 .= ">Female</option>";
        $gender_2 .= ">Male</option>";
        $gender_list = $gender_0.$gender_2.$gender_1;
        return $gender_list;
    }
}
?>
