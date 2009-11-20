<?php
/**
 * Description of skin_angst_admin
 *
 * @author josh04
 * @package code_public
 */
class skin_angst_approval extends _skin_angst_mod {

    public $javascript = "
        $(function() {
            $('.angst-reply-span').click(function() {
                    id = $(this).parent().find('.angst-id').val();
                    temp = $(this).parent().parent();
                    $.get('index.php?section=angst_mod&page=angst_approval&action=ajax_approve&id='+id+'&start=0', '', function(data) { temp.fadeTo('slow', 0, function() {temp.slideUp();}); $('#error-box').html(data); });
                    return false;
            });
        });";

   /**
    * angst!
    *
    * @param array $angst moody moody moody
    * @return string html
    */
    public function angst($angst, $type) {
        $angst = "

<div>
  <b class='".$type."t'>
  <b class='r1'></b>
  <b class='r2'></b>
  <b class='r3'></b>
  <b class='r4'></b>
  <b class='r5'></b></b>

<div class='".$type."'>
    <span class='angst-id-span' style='float:left;'>#".$angst['id']."</span>

    <span class='angst-reply-span' id='angst-reply-span-".$angst['id']."'><a class='temp' href='index.php?section=angst_mod&amp;page=angst_approval&amp;id=".$angst['id']."'>Approve</a></span>
    <p>".$angst['angst']."</p>
    <input class='angst-id' name='angst-id' value='".$angst['id']."' />
</div>

  <b class='".$type."b'>
  <b class='r5'></b>
  <b class='r4'></b>
  <b class='r3'></b>
  <b class='r2'></b>
  <b class='r1'></b>
</b><div style='text-align:right;margin:0;padding:0;'>".$angst['date']."</div></div>

";
        return $angst;
    }

   /**
    * error message wrapping
    *
    * @param string $angst the angst itself
    * @param string $message the error message
    * @return string html
    */
    public function unapproved_angst_board($angst, $message = "") {
        $unapproved_angst_board = "<div id='error-box'>".$message."</div><div style='clear:both;'></div>".$angst;
        return $unapproved_angst_board;
    }
    
   /**
    * the 'well done' message
    *
    * @param int $id an angst id
    * @return string html
    */
    public function approved($id) {
        $approved = "<div class='success'>You have successfully approved <a href='index.php?page=single&amp;id=".$id."'>angst #".$id."</a></div>";
        return $approved;
    }
}
?>
