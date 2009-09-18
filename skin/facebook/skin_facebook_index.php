<?php
/**
 * facebook skin page. FBML?
 *
 * @author josh04
 * @package skin_facebook
 */
class skin_facebook_index extends _skin_facebook {

    public function facebook_display($page) {
        $facebook_display = "<fb:wall>
            ".$page."</fb:wall>
            <fb:comments xid='angst_comment' canpost='true' candelete='false'>";
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

   <fb:wallpost uid='0' t='".$angst['time']."' ifcantsee='".$type."'>".$angst['angst']."
    <div>".$replies."</div></fb:wallpost>
    

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
                     <p class='reply-author'> - <a href='index.php?page=profile&amp;id=".$reply['player_id']."'>".$reply['username']."</a> (".date("jS M, h:i A", $reply['time']).")</p>
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
            <div class='results'><img src='images/indeterminate.gif' alt='Working' style='margin:0 auto 0 auto;padding:5px;display:block' /></div>".$form."</div>";
        return $reply_form;
    }

   /**
    * the form for replying
    *
    * @param int $id angst id
    * @return string html
    */
    public function reply_form($id) {
        $reply_form = " <form action='index.php?page=index&amp;action=angst-reply' class='angst-reply-form' method='post'>
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
        $reply_form = " <form action='index.php?page=index&amp;action=angst-reply' class='angst-reply-form' method='post'>
            <input type='hidden' class='angst-id' name='angst-id' value='".$id."' />
            <div class='error'>Guests cannot reply to angst.</div>
    </form>";
        return $reply_form;
    }

}
?>
