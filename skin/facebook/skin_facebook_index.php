<?php
/**
 * facebook skin page. FBML?
 *
 * @author josh04
 * @package skin_facebook
 */
class skin_facebook_index extends _skin_facebook {

   /**
    * main facebook page
    *
    * @param <type> $page
    * @param <type> $username
    * @param <type> $id
    * @param <type> $message
    * @return <type>
    */
    public function facebook_display($page, $username, $id, $message="") {
        $facebook_display = "
<div class='index-form'>
                <form name='angst_form' action='index.php?section=facebook&amp;page=index&amp;action=angst' method='post'>
                    <table><tbody><tr><td style='width:99%;padding:5px;'>

<textarea cols='20' rows='1'  onkeyup='resizeTextarea(this)'
          onmouseup='resizeTextarea(this)'  id='angst' name='angst'></textarea></td>
                    <td style='padding:5px;'><input type='submit' value='Angst' name='submit' id='angst-button' /></td>
<td style='padding:5px;'><input type='submit' name='submit' value='Glee' id='glee-button' /></td></tr></tbody></table>
                </form><div id='error'>".$message."</div>
</div>

            <h3>Welcome, ".$username."</h3>
            <script>
function resizeTextarea(t) {
    if ( !t.initialRows ) {
        t.initialRows = t.getRows();
    }
    a = t.getValue().split('\\n');
    b=0;
    for (x=0; x < a.length; x++) {
        if (a[x].length >= t.getCols()) {
            b+= Math.floor(a[x].length / t.getCols());
        }
    }

    b += a.length;

    if (b > t.getRows() || b < t.getRows()) {
        t.setRows((b < t.initialRows ? t.initialRows : b));
    }
}

</script>

            <div style='clear:both'></div>

            <fb:wall>
            ".$page."</fb:wall>";
        return $facebook_display;
    }

   /**
    * angst!
    *
    * @param array $angst moody moody moody
    * @return string html
    */
    public function angst($angst, $replies, $type) {
        $angst = "
<div class='".$type."'>
   <fb:wallpost uid='0' t='".$angst['time']."' ifcantsee='".$type."'>".$angst['angst']."
    <div>".$replies."</div></fb:wallpost></div>
    

";
        return $angst;
    }

   /**
    * if we want to set a name
    *
    * @param string $username poster name
    * @return string html
    */
    public function cantsee ($username) {
        $cantsee = "ifcantsee='".$username."'";
        return $cantsee;
    }

   /**
    * a single reply
    *
    * @param array $reply reply data
    * @return string html
    */
    public function reply($reply) {
        $reply = "<p><span>".$reply['reply']."</span></p>
                     <p class='reply-author'> - <a href='index.php?section=public&amp;page=profile&amp;id=".$reply['player_id']."'>".$reply['username']."</a> (".date("jS M, h:i A", $reply['time']).")</p>
            ";
            return $reply;
    }

   /**
    * reply form
    *
    * @param string $replies reply html
    * @return string html
    */
    public function reply_box($form, $replies ="", $display='none') {
        $reply_form = "
       <div class='reply-table' style='display:".$display.";'>
            <div class='results'><img src='http://www.atomicfridge.net:12340/angst/images/indeterminate.gif' alt='Working' style='margin:0 auto 0 auto;padding:5px;display:block' /></div>".$form."</div>";
        return $reply_form;
    }

   /**
    * the form for replying
    *
    * @param int $id angst id
    * @return string html
    */
    public function reply_form($id) {
        $reply_form = " <form action='index.php?section=public&amp;page=index&amp;action=angst-reply' class='angst-reply-form' method='post'>
        <table><tbody>
            <tr><td style='width:99%;'>
            <input type='hidden' class='angst-id' name='angst-id' value='".$id."' />
            <textarea cols='60' rows='1' class='angst-reply' name='angst-reply'></textarea></td>
            <td><input type='submit' value='Reply' class='angst-reply-button' /></td>
        </tr></tbody></table>
    </form>";
        return $reply_form;
    }


   /**
    * the form for replying
    *
    * @param int $id angst id
    * @return string html
    */
    public function reply_form_guest($id) {
        $reply_form = " <form action='index.php?section=public&amp;page=index&amp;action=angst-reply' class='angst-reply-form' method='post'>
            <input type='hidden' class='angst-id' name='angst-id' value='".$id."' />
            <div class='error'>Guests cannot reply to angst.</div>
    </form>";
        return $reply_form;
    }

    public function angst_with_facebook_form() {
        $angst = "<script>
                function postToWall() {
                    var d = document.getElementById('offer-to-post-on-wall');
                    Facebook.showFeedDialog( 160578989739, '', '', '', function() {document.setLocation('http://apps.facebook.com/postyourangst/');}, '', '' );
                    Animation(d).to('height', '0px').to('opacity', 0).hide().go();
                }
            </script>

<div id='offer-to-post-on-wall' class='success'>Your angst has been posted. <a onclick='postToWall()'>Click here if you want to tell your friends you're using <em>Post Your Angst!</em></a></div>";
        return $angst;
    }

    public function redirect_after_angst() {
        $redirect = "<fb:redirect url=\"http://apps.facebook.com/postyourangst/index.php?section=facebook&amp;page=index&amp;action=show_wall_offer\" />";
        return $redirect;
    }

}
?>
