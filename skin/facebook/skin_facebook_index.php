<?php
/**
 * facebook skin page. FBML?
 *
 * @author josh04
 * @package skin_facebook
 */
class skin_facebook_index extends _skin_facebook {

    public function facebook_display($user_id) {
        $facebook_display = "<p>Hello, <fb:name uid=\"".$user_id."\" useyou=\"false\" />!</p>

            <fb:comments xid='angst_comment' canpost='true' candelete='false'>";
        return $facebook_display;
    }

}
?>
