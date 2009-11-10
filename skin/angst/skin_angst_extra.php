<?php
/**
 * Description of skin_angst_extra
 *
 * @author josh04
 * @package skin_angst
 */
class skin_angst_extra extends angst_skin_common {

   /**
    * message when you like something
    *
    * @param int $id angst id
    * @return string html as always
    */
    public function liked($id) {
        $liked = "<div class='success'>You liked <a href='index.php?section=angst&amp;page=single&amp;id=".$id."'>angst #".$id."</a>.</div>";
        return $liked;
    }

   /**
    * message when you dislike something
    *
    * @param int $id angst id
    * @return string html as always
    */
    public function disliked($id) {
        $liked = "<div class='success'>You disliked <a href='index.php?section=angst&amp;page=single&amp;id=".$id."'>angst #".$id."</a>.</div>";
        return $liked;
    }


   /**
    * bookmakred message
    *
    * @param int $id angst id
    * @return string html
    */
    public function bookmarked($id) {
        $bookmarked = "<div class='success'>You have bookmarked angst #".$id."</div>";
        return $bookmarked;
    }

   /**
    * unbookmarked message
    *
    * @param int $id angst id
    * @return string html
    */
    public function unbookmarked($id) {
        $bookmarked = "<div class='success'>You are no longer bookmarking angst #".$id."</div>";
        return $bookmarked;
    }

}
?>
