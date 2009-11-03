<?php
/**
 * skin_default.class.php
 *
 * Contains static skin elements related to the index page.
 * @package skin_public
 * @author josh04
 */

class skin_angst_index extends angst_skin_common {

    public $javascript = "
    var in_action = 0;
    $(function() {
        $('#index-main').find('.angst-reply-span').click(function() {
            if (in_action == 0) {
                in_action = 1;
                if ($(this).parent().find('.reply-table').find('p').length == 0) {
                    id = $(this).parent().find('.angst-id').val();
                    $(this).parent().find('.reply-table').slideToggle();
                    var img = $(this).parent().find('img');
                    var temp = $(this).parent().find('.results');
                    $.get('index.php?page=single&action=ajax_replies&id='+id+'&start=0', '', function(data) { img.hide(); if (data.length > 1) {temp.parent().show();} temp.append(data); in_action = 0;});
                } else {
                    $(this).parent().find('.reply-table').slideToggle();
                    in_action = 0;
                }
            }
        });

        $('#index-main').find('.angst-reply-more').click(function() {
            if (in_action == 0) {
                in_action = 1;
                if ($(this).parent().find('.reply-table').find('p').length == 0) {
                    id = $(this).parent().find('.angst-id').val();
                    start = $(this).parent().find('.results').find('input:last').val();
                    var angstreplyspan = $(this);
                    var temp = $(this).parent().find('.results');
                    $.get('index.php?page=single&action=ajax_replies&id='+id+'&start='+start, '', function(data) {
    if (data.length < 1 ) {
        angstreplyspan.html('There are currently no more comments. Click here to retry.'); temp.find('.just-posted').hide();
    };

    if ($('.just-posted').length > 0) {
    temp.find('.just-posted:last').before('<div style=\'display:none\'>'+data+'</div>'); temp.find('div:last').prev().slideDown();
    } else {
    temp.append('<div style=\'display:none\'>'+data+'</div>'); temp.find('div:last').slideDown();
    }

    replycounter = temp.find('input:last').prev().val();
    if (replycounter != 1 ) { angstreplyspan.html('There are currently no more comments. Click here to retry.'); temp.find('.just-posted').hide(); $(this).parent().find('.results').find('input:last').prev().val('1'); }; in_action = 0;});
                } else {
                    in_action = 0;
                }
            }
        });

        $('#index-main').find('.angst-reply-form').submit(function() {
            if (in_action == 0) {
                in_action = 1;
                var temp = $(this).parent().find('.results');
                $('#error').empty();
                var cleanTxt = $('<div/>').text($(this).find('.angst-reply').val()).html();
                var cleanId = $(this).find('.angst-id').val();
                var dataString = $(this).serialize();
                var replyForm = this;
                $.post('index.php?page=single&action=ajax_reply', dataString, function (data, textStatus) {
                        $('#error').html(data);
                        if (data.length == 0) {
                            temp.parent().show();
                            var replystring = '<div class=\'just-posted\'><p style=\'display: none;\'><span>'+cleanTxt+'</span></p><p class=\'reply-author\' style=\'display: none;\'> - <a class=\'added\' href=\'index.php?section=public&amp;page=profile&amp;id='+userid+'\'>'+username+'</a> (just now)</p></div>';
                            replystring.replace(/([^>]?)\\n/g, '$1<br />\\n');
                            $(replyForm).parent().find('.results').append(replystring).html();
                            $(replyForm).find('.angst-reply').val('');
                            $(replyForm).parent().find('.results').find('.just-posted:last').find('p').fadeIn('slow');
                        }
                        in_action = 0;
                    }, 'html' );

            }
            return false;

        });

        $('#index-main').find('a.angst-id-span').click(function() {
            splitStringArr = $(this).attr('id').split('-');
            id = splitStringArr[2];
            if (splitStringArr[3] == '1') {
                reply_count = splitStringArr[3]+' reply';
            } else {
                reply_count = splitStringArr[3]+' replies';
            }
            $(this).fadeOut('slow');
            $.get('index.php?section=angst&page=bookmark&action=ajax_bookmark&id='+id, '', function(data) {
                if ($('#new-bookmarks').find('a').length < 1) {
                    $('#new-bookmarks').append('<h4>Just Added</h4>');
                }
                bookmark_string = '<span><a href=\"index.php?section=angst&page=single&id='+id+'\" class=\"bookmark-unread\"> #'+id+'</a> ('+reply_count+') <a class=\"bookmark-remove\" id=\"angst-bookmark-delete-'+id+'\" href=\"index.php?section=angst&amp;page=bookmark&amp;action=index_remove&amp;id='+id+'\">(remove)</a></span><br />';
                $('#error').append(data);
                $('#new-bookmarks').parent().find('.error').hide();
                $('#new-bookmarks').append(bookmark_string);

        $('.leftside').find('a.bookmark-remove').click(function() {
            splitStringArr = $(this).attr('id').split('-');
            id = splitStringArr[3];
            $(this).parent().fadeOut('slow');
            $.get('index.php?section=angst&page=bookmark&action=ajax_remove&id='+id, '', function(data) {
                $('#error').append(data);
             });
            return false;
        });

             });
            return false;
        });

        $('.leftside').find('a.bookmark-remove').click(function() {
            splitStringArr = $(this).attr('id').split('-');
            id = splitStringArr[3];
            $(this).parent().fadeOut('slow');
            $.get('index.php?section=angst&page=bookmark&action=ajax_remove&id='+id, '', function(data) {
                $('#error').append(data);
             });
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
    public function angst_index($angst, $boxes, $id = 0, $username = 'Guest', $angst_text = "", $message = "") {
        $index_player = "
            <div class='index-form-margin'>
  <b class='tt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b><div class='index-form'>
                <h3>Got Angst?</h3>
                <form action='index.php?page=index&amp;action=angst' method='post'>
                    <table><tbody><tr><td style='width:99%;padding:5px;'>

<textarea cols='60' rows='1'
          onkeyup='resizeTextarea(this)'
          onmouseup='resizeTextarea(this)' id='angst' name='angst'>".$angst_text."</textarea></td>
                    <td style='padding:5px;'><input type='submit' value='Angst' name='submit' id='angst-button' /></td>
<td style='padding:5px;'><input type='submit' name='submit' value='Glee' id='glee-button' /></td></tr></tbody></table>
                </form><div id='error'>".$message."</div>
</div>
  <b class='tb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b>
            </div>
            
            <h2>Welcome, ".$username."</h2>
            <script type='text/javascript' language='JavaScript'>var username='".$username."'; var userid='".$id."'; var time</script>
            <div style='clear:both'></div>
            <div style='float:right'>
                ".$boxes."
            </div>

                ".$angst."

            <div style='clear:both'></div>
            
            ";
        return $index_player;
    }

   /**
    * Draws the angst board
    *
    * @param string $formatted_angst the angst html
    * @param string $paginate the paginator
    * @return string html
    */
    public function angst_board($formatted_angst, $paginate) {
        $angst_board = "<div id='index-main'>".$formatted_angst.$paginate."

            </div>";
        return $angst_board;
    }

   /**
    * mail box
    *
    * @param string $mail ur mails
    * @return string html
    */
    public function mail_box($mail) {
        $mail_box = "  <b class='tt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b><div class=\"leftside\">
                    <h4>Recent mail (<a href=\"index.php?section=public&amp;page=mail\">more</a>)</h4>
                        ".$mail."
                </div>  <b class='tb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b>";
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
        $profile_box = "  <b class='tt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b><div class=\"leftside\">
                    <h4>Your Profile (<a href='index.php?section=public&amp;page=profile&amp;id=".$id."'>more</a>)</h4>
                        Registered: ".$registered."<br />
                </div>  <b class='tb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b>";
        return $profile_box;
    }

   /**
    * online peoples
    *
    * @param string $list list of names
    * @return string html
    */
    public function online_list($list) {
        $online_list = "  <b class='tt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b><div class=\"leftside\">
                    <h4>Users online (<a href='index.php?section=public&amp;page=members'>more</a>)</h4>".$list."
                </div>  <b class='tb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b>";
        return $online_list;
    }

   /**
    * log in box
    *
    * @param string $username name of the user
    * @return string html
    */
    public function login_box($username) {
        $login_box = "  <b class='tt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b><div class='leftside'>
                    <form method='post' action='index.php?section=public&amp;page=login'>
                        <strong>Log in</strong><br />
                        <label for='login-username'>Username:</label><br />
                        <input type='text' id='login-username' name='username' value='".$username."' /><br />
                        <label for='login-password'>Password:</label><br />
                        <input type='password' id='login-password' name='password' /><br />
                        <input name='login' type='submit' value='Login' /><br />
                    </form>
                    <a href='index.php?section=public&amp;page=login&amp;action=register'>Click here to register!</a>
                </div>  <b class='tb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b>";
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
        $member_online_link = "<a href='index.php?section=public&amp;page=profile&amp;id=".$id."'>".$username."</a>";
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
        $news_entry = "<div>".$message."<br /> - <a href='index.php?section=public&amp;page=profile&amp;id=".$id."'>".$username."</a></div>";
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
        $log_entry = "<div ".($status==0 ? "class='error'":"class=''").">".$message."</div>";
        return $log_entry;
    }

   /**
    * returns a mail message
    *
    * @param array $message the details of the message
    * @return string html
    */
    public function mail_entry($message) {
        $mail_entry = "<em><a href=\"?section=public&amp;page=mail&amp;action=read&amp;id=".$message['id']."\">" . $message['subject'] . "</a></em><br />From: ".$message['username'];
        $mail_entry = $this->log_entry($mail_entry, $message['status']);
        return $mail_entry;
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
            <form method='POST' action='index.php?section=public&amp;page=login&amp;action=register_submit'>
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
    public function angst($angst, $replies, $type, $bookmark, $replies_link = "", $unapproved = "") {
        $angst = "<div>
  <b class='".$type."t'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>

<div class='".$type."'>

    <span class='angst-id-span' style='float:left;'><a href='index.php?page=single&amp;id=".$angst['id']."'>#".$angst['id']." ".$unapproved."</a></span>

    ".$replies_link."

    <p style='clear:left;'>".$angst['angst']."</p>
    <div>".$replies."</div>
</div>

  <b class='".$type."b'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b>
</b></div>".$bookmark."<div style='text-align:right;margin-bottom:20px;;padding:0;'>".$angst['date']."</div>

";
        return $angst;
    }

   /**
    * The Replies (30) thing
    *
    * @param int $id angst id
    * @param int $reply_count number of replies
    * @return string html
    */
    public function replies_link($id, $reply_count) {
        $replies_link = "<span class='angst-reply-span' id='angst-reply-span-".$id."'>
            <span>Reply</span> (".$reply_count.")</span>";
        return $replies_link;
    }

   /**
    * Bookmark link
    *
    * @param int $id angst id
    * @return string html
    */
    public function bookmark_link($id, $reply_count) {
        $bookmark_link = "<a class='angst-id-span' id='angst-bookmark-".$id."-".$reply_count."' href='index.php?section=angst&amp;page=bookmark&amp;action=index_redirect&amp;id=".$id."'>Bookmark</a>";
        return $bookmark_link;
    }

   /**
    * reply form
    *
    * @param string $replies reply html
    * @return string html
    */
    public function reply_box($form, $display='none') {
        $reply_form = "
       <div class='reply-table' style='display:".$display.";'>
<img src='images/load.gif' alt='Working' style='margin:0 auto 0 auto;padding:5px;display:block' />
<div class='results-wrap'>
  <b class='wt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>

<div class='results'><h3 class='reply-header'>Replies</h3></div>

  <b class='wb'>
  <b class='wbr'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b></div>
            <span class='angst-reply-more'>More...</span>".$form."</div>";
        return $reply_form;
    }

   /**
    * the form for replying
    *
    * @param int $id angst id
    * @return string html
    */
    public function reply_form($id) {
        $reply_form = " <form action='index.php?page=single&amp;action=angst-reply' class='angst-reply-form' method='post'>
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
        $reply_form = " <form action='index.php?page=single&amp;action=angst-reply' class='angst-reply-form' method='post'>
            <input type='hidden' class='angst-id' name='angst-id' value='".$id."' />
            <div>Guests cannot reply to angst.</div>
    </form>";
        return $reply_form;
    }

   /**
    * makes a single bookmark id
    *
    * @param int $id bookmark id
    * @param int $replies number of replies
    * @param string $class new replies?
    * @return string html
    */
    public function bookmark_id_read($id, $replies) {
        $bookmark_id = " <span><a class='bookmark-read' href='index.php?section=angst&amp;page=single&amp;id=".$id."'>#".$id."</a> (".$replies." repl".(($replies == 1) ? 'y':'ies').")  <a class='bookmark-remove' id='angst-bookmark-delete-".$id."' href='index.php?section=angst&amp;page=bookmark&amp;action=index_remove&amp;id=".$id."'>(remove)</a><br /></span>";
        return $bookmark_id;
    }

   /**
    * bookmark id when unread
    *
    * @param int $id angst id
    * @param int $unread number of new replies
    * @return string html
    */
    public function bookmark_id_unread($id, $unread) {
        $bookmark_id_unread = " <span><a class='bookmark-unread' href='index.php?section=angst&amp;page=single&amp;id=".$id."'>#".$id."</a> (".$unread." new)  <a class='bookmark-remove' id='angst-bookmark-delete-".$id."' href='index.php?section=angst&amp;page=bookmark&amp;action=index_remove&amp;id=".$id."'>(remove)</a><br /></span>";
        return $bookmark_id_unread;
    }

   /**
    * bookmark id when unapproved
    *
    * @param int $id angst id
    * @return string html
    */
    public function bookmark_id_unapproved($id) {
        $bookmark_id_unapproved = " <span><a class='bookmark-unapproved' href='index.php?section=angst&amp;page=single&amp;id=".$id."'>#".$id."</a> (unapproved) <a class='bookmark-remove' id='angst-bookmark-delete-".$id."' href='index.php?section=angst&amp;page=bookmark&amp;action=index_remove&amp;id=".$id."'>(remove)</a><br /></span>";
        return $bookmark_id_unapproved;
    }

   /**
    * box for the ids
    *
    * @param string $unread ids with new replies
    * @param string $read ids with not new replies
    * @param string $unapproved ids the mods don't like
    * @return string html
    */
    public function bookmark_box($unread, $read, $unapproved) {
        $bookmark_box = "  <b class='tt'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b><div class='leftside'>
               ".$unread.$read.$unapproved."<div id='new-bookmarks'></div>
            </div>  <b class='tb'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b></b>";
        return $bookmark_box;
    }

   /**
    * unread bookmarks
    *
    * @param string $unread unread ids
    * @return string html
    */
    public function bookmark_unread($unread) {
        $bookmark_unread = "<div><h4>New replies</h4>".$unread."</div>";
        return $bookmark_unread;
    }

   /**
    * read bookmarks
    *
    * @param string $read read ids
    * @return string html
    */
    public function bookmark_read($read) {
        $bookmark_read = "<div><h4>No New Replies</h4>".$read."</div>";
        return $bookmark_read;
    }

   /**
    * unapproved bookmarks
    *
    * @param string $unapproved unapproved ids
    * @return string html
    */
    public function bookmark_unapproved($unapproved) {
        $bookmark_unapproved = "<div><h4>Unapproved</h4>".$unapproved."</div>";
        return $bookmark_unapproved;
    }

   /**
    * success message
    *
    * @param int $id angst id
    * @return string html
    */
    public function bookmarked($id) {
        $bookmarked = "You have bookmarked angst #".$id;
        return $bookmarked;
    }

   /**
    * success message
    *
    * @param int $id angst id
    * @return string html
    */
    public function unbookmarked($id) {
        $bookmarked = "You are no longer bookmarking angst #".$id;
        return $bookmarked;
    }

}

?>
