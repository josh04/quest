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
}
?>
