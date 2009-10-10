<?php
/**
 * Description of skin_angst_single
 *
 * @author josh04
 * @package skin_public
 */
class skin_angst_single extends angst_skin_common {

    public $javascript = "
    $(function() {
        $('#angst-single').find('.angst-reply-more').click(function() {
            if ($(this).parent().find('.reply-table').find('p').length == 0) {
                id = $(this).parent().find('.angst-id').val();
                start = $('#results').find('input:last').val();
                var angstreplyspan = $(this);
                var temp = $('#results');
                $.get('index.php?page=single&action=ajax_replies&id='+id+'&start='+start, '', function(data) {
if (data.length < 1 ) {
    angstreplyspan.html('There are currently no more comments. Click here to retry.');
    temp.find('.just-posted').hide();
} else {
    temp.parent().slideDown();
}
if ($('.just-posted').length > 0) {
    temp.find('.just-posted:last').before('<div style=\'display:none\'>'+data+'</div>'); temp.find('div:last').prev().slideDown();
} else {
    temp.append('<div style=\'display:none\'>'+data+'</div>'); temp.find('div:last').slideDown();
}
replycounter = temp.find('input:last').prev().val();
if (replycounter != 1 ) { 
    angstreplyspan.html('There are currently no more comments. Click here to retry.');
    temp.find('.just-posted').hide();
    temp.find('input:last').prev().val('1');
};
                });
            }
        });

        $('#reply-form').submit(function() {

            $('#error').empty();
            var cleanTxt = $('<div/>').text($(this).find('.angst-reply').val()).html();
            var cleanId = $(this).find('.angst-id').val();
            var dataString = $(this).serialize();
            var replyForm = this;
            $.post('index.php?page=single&action=ajax_reply', dataString, function (data, textStatus) {
                    $('#error').html(data);
                    if (data.length == 0) {
                        var replystring = '<div class=\'just-posted\'><p style=\'display: none;\'><span>'+cleanTxt+'</span></p><p class=\'reply-author\' style=\'display: none;\'> - <a class=\'added\' href=\'index.php?section=public&amp;page=profile&amp;id='+userid+'\'>'+username+'</a> (just now)</p></div>';
                        replystring.replace(/([^>]?)\\n/g, '$1<br />\\n');
                        $('#results').parent().slideDown();
                        $('#results').append(replystring).html();
                        $(replyForm).find('.angst-reply').val('');
                        $('#results').find('.just-posted:last').find('p').fadeIn('slow');
                    }
                }, 'html' );


            return false;
        });

        $('textarea.angst-reply').autoResize({
            // On resize:
            onResize : function() {
            $(this).css({opacity:0.8});
            },
            // After resize:
            animateCallback : function() {
            $(this).css({opacity:1});
            },
            // Quite slow animation:
            animateDuration : 300,
            // More extra space:
            extraSpace : 15
        });
    });";

   /**
    * A single angst goes here
    *
    * @param array $angst_db angst db object
    * @param string $replies the replies
    * @param string $reply_form the reply form
    * @return string html
    */
    public function angst_single($angst_db, $replies, $reply_form, $type, $reply_count, $script, $message) {
        $angst_single = $message.$script."

<div id='angst-single'>
  <b class='wt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>

<div class='".$type."-inv'>
    <span class='angst-id-span' style='float:left;'>#".$angst_db['id']."</span>

    <span class='angst-reply-span' id='angst-reply-span-".$angst_db['id']."'>
        ".$reply_count." repl".(($reply_count == 1) ? 'y':'ies')."</span>
    <p>".$angst_db['angst']."</p>
    <div><div class='reply-table'>

".$replies."

<span class='angst-reply-more'>More...</span>".$reply_form."</div>
</div>
</div>

  <b class='wb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b>
</div>

<div style='text-align:right;margin:0;padding:0;'>".$angst_db['date']."</div>



";
        return $angst_single;
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
    * the form for replying
    *
    * @param int $id angst id
    * @return string html
    */
    public function reply_form($id) {
        $reply_form = " <form action='index.php?page=single&amp;action=angst-reply&amp;id=".$id."' class='angst-reply-form' id='reply-form' method='post'>
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
        $reply_form = " <form action='' class='angst-reply-form' method='post' id='reply-form'>
            <input type='hidden' class='angst-id' name='angst-id' value='".$id."' />
            <div class='error'>Guests cannot reply to angst.</div>
    </form>";
        return $reply_form;
    }

   /**
    * yardsticks for replies
    *
    * @param int $start where to start from next
    * @param $reply_counter the number of replies sent - set to six if it was the last five.
    * @return string html
    */
    public function start_input($start, $reply_counter) {
        $start_input = "<input type='hidden' name='start' value='".$reply_counter."' />
            <input type='hidden' name='start' value='".$start."' />";
        return $start_input;
    }

   /**
    * javascript, notes down the user id to use
    *
    * @param int $player_id user's id
    * @param string $username users name
    * @return string html
    */
    public function user_id_script($player_id, $username) {
        $user_id_script = "<script type='text/javascript' language='JavaScript'>var username='".$username."'; var userid='".$id."';</script>";
        return $user_id_script;
    }

   /**
    * Show replies section?
    *
    * @param string $replies_html the replies themselves
    * @param string $type angst or glee
    * @param string $display to show the box or not?
    * @return string html
    */
    public function replies($replies_html, $type, $display = 'none') {
        $replies = "  <div style='display:".$display."'>
<b class='".$type."t'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>
            <div class='".$type."' id='results'><h3 class='reply-header'>Replies</h3>".$replies_html."</div>
  <b class='".$type."b'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b></div>";
        return $replies;
    }
}
?>
