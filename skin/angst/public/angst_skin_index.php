<?php
/**
 * skin_default.class.php
 *
 * Contains static skin elements related to the index page.
 * @package skin_public
 * @author josh04
 */

class angst_skin_index extends angst_skin_common {

    public $javascript = "
    $(function() {
        $('#index-main').find('.angst-reply-span').click(function() {
            if ($(this).parent().find('.reply-table').find('p').length == 0) {
                id = $(this).parent().find('.angst-id').val();
                $(this).parent().find('.reply-table').slideToggle();
                $(this).parent().find('.results').load('/angst/index.php?page=index&action=ajax_replies&id='+id);
            } else {
                $(this).parent().find('.reply-table').slideToggle();
            }
        });

        $('#index-main').find('.angst-reply-form').submit(function() {

            $('#error').empty();
            var cleanTxt = $('<div/>').text($(this).find('.angst-reply').val()).html();
            var cleanId = $(this).find('.angst-id').val();
            var dataString = $(this).serialize();
            var replyForm = this;
            $.post('/angst/index.php?page=index&action=ajax_reply', dataString, function (data, textStatus) {
                    $('#error').html(data);
                    if (data.length == 0) {
                        $(replyForm).parent().find('.results').append('<p style=\'display: none;\'><span>'+cleanTxt+'</span></p><p class=\'reply-author\' style=\'display: none;\'> - <a class=\'added\' href=\'index.php?page=profile&amp;id='+userid+'\'>'+username+'</a> (just now)</p>').html();
                        $(replyForm).find('.angst-reply').val('');
                        $(replyForm).parent().find('.results').find(':hidden').fadeIn('slow');
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
    * returns the main player-logged-in screen
    *
    * @param player $player player object
    * @param string $online_list names of online players
    * @param string $mail any mail?
    * @return string html
    */
    public function index_player($player, $angst, $online_list, $mail, $login_box, $profile_box, $message = "") {
        $index_player = "
            <div class='index-form-margin'>
  <b class='wt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b><div class='index-form'>
                <h2>Got Angst?</h2>
                <form action='index.php?page=index&amp;action=angst' method='post'>
                    <table><tbody><tr><td style='width:99%;padding:5px;'>

<textarea cols='60' rows='1'
          onkeyup='resizeTextarea(this)'
          onmouseup='resizeTextarea(this)' id='angst' name='angst'></textarea></td>
                    <td style='padding:5px;'><input type='submit' value='Angst' name='submit' id='angst-button' /></td>
<td style='padding:5px;'><input type='submit' name='submit' value='Glee' id='glee-button' /></td></tr></tbody></table>
                </form><div id='error'>".$message."</div>
</div>
  <b class='wb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b>
            </div>
            
            <h3>Welcome, ".$player->username."</h3>
            <script type='text/javascript' language='JavaScript'>var username='".$player->username."'; var userid='".$player->id."'; var time</script>
            <div style='clear:both'></div>
            <div style='float:right'>
                ".$login_box."
                ".$profile_box."
                ".$online_list."
                ".$mail."
            </div>

<div class='index-margin'>
  <b class='wt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>

<div id='index-main'>".$angst."</div>

  <b class='wb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b></div>

            <div style='clear:both'></div>
            
            ";
        return $index_player;
    }

   /**
    * mail box
    *
    * @param string $mail ur mails
    * @return string html
    */
    public function mail_box($mail) {
        $mail_box = "<div class=\"success\">
                    <h4>Recent mail (<a href=\"index.php?page=mail\">more</a>)</h4>
                        ".$mail."
                </div>";
        return $mail_box;
    }

   /**
    * profile box
    *
    * @param int $id user id
    * @param string $registered date you joined
    * @return string html
    */
    public function profile_box($id, $registered) {
        $profile_box = "<div class=\"success\">
                    <h4>Your Profile (<a href='index.php?page=profile&amp;id=".$id."'>more</a>)</h4>
                        Registered: ".$registered."<br />
                </div>";
        return $profile_box;
    }

   /**
    * online peoples
    *
    * @param string $list list of names
    * @return string html
    */
    public function online_list($list) {
        $online_list = "<div class=\"success\">
                    <h4>Users online (<a href='index.php?page=members'>more</a>)</h4>".$list."
                </div>";
        return $online_list;
    }

   /**
    * log in box
    *
    * @param string $username name of the user
    * @return string html
    */
    public function login_box($username) {
        $login_box = "<div class='success'>
                    <form method='post' action='index.php?page=login'>
                        <strong>Log in</strong><br />
                        <label for='login-username'>Username:</label><br />
                        <input type='text' id='login-username' name='username' value='".$username."' /><br />
                        <label for='login-password'>Password:</label><br />
                        <input type='password' id='login-password' name='password' /><br />
                        <input name='login' type='submit' value='Login' /><br />
                    </form>
                    <a href='index.php?page=login&amp;action=register'>Click here to register!</a>
                </div>";
        return $login_box;
    }

   /**
    * returns the member name link
    *
    * @param integer $id user id
    * @param string $username username
    * @return string html
    */
    public function member_online_link($id, $username) {
        $member_online_link = "<a href='index.php?page=profile&amp;id=".$id."'>".$username."</a>";
        return $member_online_link;

    }

   /**
    * returns a news entry
    *
    * @param integer $id user id
    * @param string $username username
    * @param string $message news message
    * @return string html
    */
    public function news_entry($id, $username, $message) {
        $news_entry = "<div>".$message."<br /> - <a href='index.php?page=profile&amp;id=".$id."'>".$username."</a></div>";
      return $news_entry;
    }

   /**
    * returns a log entry
    *
    * @param string $message the content of the log entry
    * @param integer $status the status of the log entry (read or unread)
    * @return string html
    */
    public function log_entry($message, $status) {
        $log_entry = "<div style='border:1px solid #CCC;margin:4px;padding:4px;".($status==0?"background-color:#FCC;":"")."'>".$message."</div>";
        return $log_entry;
    }

   /**
    * returns a mail message
    *
    * @param array $message the details of the message
    * @return string html
    */
    public function mail_entry($message) {
        $mail_entry = "<em><a href=\"?page=mail&amp;action=read&amp;id=".$message['id']."\">" . $message['subject'] . "</a></em><br />From: ".$message['username'];
        $mail_entry = $this->log_entry($mail_entry, $message['status']);
        return $mail_entry;
    }

   /**
    * returns the details of your current quest
    *
    * @param array $quest what you're doing
    * @return string html
    */
    public function current_quest($quest) {
        return "<div class='quest-select'>
        <a style='float:right;' href='index.php?page=quest'>See your progress</a>
        <h3>".$quest['title']."</h3>
        Author: ".$quest['author']."<br />
        <p>".$quest['description']."</p></div>";
    }

   /**
    * returns the registration form
    *
    * @param string $username html-friendly username
    * @param string $email html-friendly email
    * @param string $register_error registration error
    * @return string html
    */
    public function register($username, $email, $register_error) {
        $register .= "
            ".$register_error."
            <form method='POST' action='index.php?page=login&amp;action=register_submit'>
                <table style='width:100%'>
                    <tr><td width='40%'><label for='form-username'>Username:</label></td><td><input type='text' id='form-username' name='username' value='".$username."' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Enter the username that you will use to login to your game. Only alpha-numerical characters are allowed.</td></tr>

                    <tr><td><label for='form-password'>Password:</label></td><td><input type='password' id='form-password' name='password' value='' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Type in your desired password. Only alpha-numerical characters are allowed.</td></tr>

                    <tr><td><label for='form-password-confirm'>Verify Password:</label></td><td><input type='password' id='form-password-confirm' name='password_confirm' value='' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Please re-type your password.</td></tr>

                    <tr><td><label for='form-email'>Email:</label></td><td><input type='text' id='form-email' name='email' value='".$email."' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Enter your email address. Only alpha-numerical characters are allowed.</td></tr>

                    <tr><td><label for='form-email-confirm'>Verify Email:</label></td><td><input type='text' id='form-email-confirm' name='email_confirm' value='' /></td></tr>
                    <tr><td colspan='2' class='form-explanation'>Please re-type your email address.</td></tr>

                    <tr><td colspan='2'><input type='submit' name='register' value='Register!'></td></tr>
                </table>
            </form>";
        return $register;
    }

   /**
    * Not what?
    *
    * @return string html
    */
    public function player_not_approved() {
        $player_not_approved = "<div class='success'><p>The site's administrators have opted to approve all new accounts.</p>
                                <p>Yours has not been approved yet.</p></div>";
        return $player_not_approved;
    }

   /**
    * angst!
    *
    * @param array $angst moody moody moody
    * @return string html
    */
    public function angst($angst, $replies, $type, $colour_code, $reply_count) {
        $angst = "<div>
  <b class='".$colour_code."t'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>

<div class='".$type."'>
    <span class='angst-id-span' style='float:left;'>#".$angst['id']."</span>

    <span class='angst-reply-span' id='angst-reply-span-".$angst['id']."'>
        <span>Reply</span> (".$reply_count.")</span>
    <p>".$angst['angst']."</p>
    <div>".$replies."</div>
</div>

  <b class='".$colour_code."b'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b>
</b></div><div style='text-align:right;margin:0;padding:0;'>".date("jS M, h:i A", $angst['time'])."</div>

";
        return $angst;
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

   /**
    * Player details menu
    *
    * @param code_player $player Player object
    * @param array $player_extra extra player rpg details
    * @return string html
    */
    public static function player_details_menu(&$player, $player_extra) {
        $player_details_menu = "<div class='left-section'>
                    <strong>Username:</strong> <a href='index.php?page=profile&amp;id=".$player->id."'>".$player->username."</a>
                    <ul>
                       <li><strong>Level:</strong> ".$player_extra['level']."</li>
                       <li><strong>EXP:</strong> ".$player_extra['exp']." (".$player_extra['exp_diff']." to go)</li>
                       <li><strong>Health:</strong> ".$player_extra['hp']."/".$player_extra['hp_max']."</li>
                       <li><strong>Energy:</strong> ".$player_extra['energy']."/".$player_extra['energy_max']."</li>
                       <li><strong>Money:</strong> &#163;".$player->gold."</li>
                    </ul>
                </div>";
        return $player_details_menu;
    }

}

?>
