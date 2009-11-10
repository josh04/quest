<?php
/**
 * mainly ajax-y style things, I think.
 *
 * @author josh04
 * @package code_angst
 */
class code_angst_bookmark extends _code_angst {

    public $player_class = "code_player_profile";
    public $override_skin = "angst"; // This is a little bit of a hack; technically the extra skin_common should be in _skin_angst.php

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_angst_extra");
        $id = intval($_GET['id']);
        $this->ajax_mode = true;

        if ($id) {
            switch ($_GET['action']) {
                case "ajax_bookmark":
                    $bookmark = $this->bookmark($id);
                    if ($bookmark) {
                        return $this->skin->bookmarked($id);
                    } else {
                        return $this->skin->error_box($this->lang->bookmark_failed);
                    }
                    exit;
                case "ajax_remove":
                    $bookmark = $this->bookmark_remove($id);
                    if ($bookmark) {
                        return $this->skin->unbookmarked($id);
                    } else {
                        return $this->skin->error_box($this->lang->remove_bookmark_failed);
                    }
                    exit;
                case 'index_redirect':
                    $bookmark = $this->bookmark($id);
                    if ($bookmark) {
                        header("Location: index.php?section=angst&page=index&action=bookmarked&id=".$id);
                    } else {
                        header("Location: index.php?section=angst&page=index&action=not_bookmarked&id=".$id);
                    }
                    exit;
                case 'single_redirect':
                    $bookmark = $this->bookmark($id);
                    if ($bookmark) {
                        header("Location: index.php?section=angst&page=single&action=bookmarked&id=".$id);
                    } else {
                        header("Location: index.php?section=angst&page=single&action=not_bookmarked&id=".$id);
                    }
                    exit;
                case 'index_remove':
                    $bookmark = $this->bookmark_remove($id);
                    if ($bookmark) {
                        header("Location: index.php?section=angst&page=index&action=unbookmarked&id=".$id);
                    } else {
                        header("Location: index.php?section=angst&page=index&action=not_unbookmarked&id=".$id);
                    }
                    exit;
                case 'single_remove':
                    $bookmark = $this->bookmark_remove($id);
                    if ($bookmark) {
                        header("Location: index.php?section=angst&page=index&action=unbookmarked&id=".$id);
                    } else {
                        header("Location: index.php?section=angst&page=index&action=not_unbookmarked&id=".$id);
                    }
                    exit;
            }
        }


        header("Location: index.php");
    }

   /**
    * adds a particular angst to your bookmarks
    *
    * @param int $id angst id to bookmark
    * @return bool angst exists
    */
    public function bookmark($id) {
        $id = intval($id);
        $check_query = $this->db->execute("SELECT `id`, `approved` FROM `angst` WHERE `id`=?", array($id));
        
        if ($check = $check_query->fetchrow()) {
        
            if (is_array($this->player->bookmarks)) {
                if (!isset($this->player->bookmarks[$id])) {
                    $this->player->bookmarks[$id] = 0;
                }
            } else {
                $this->player->bookmarks = array($id => 0);
            }

            $bookmarks_array = json_decode($_COOKIE['bookmarks'], true);
            $bookmarks_array[$id] = 0;
            setcookie('bookmarks', json_encode($bookmarks_array), time()+60*60*24*300);

            $this->player->update_player(true);
            return true;
            
        } else {
            return false;
        }
    }

   /**
    * removes a bookmark
    *
    * @param int $id angst id
    * @return bool success
    */
    public function bookmark_remove($id) {
        $id = intval($id);
        if (is_array($this->player->bookmarks) && isset($this->player->bookmarks[$id])) {
            unset($this->player->bookmarks[$id]);
        } else {
            return false;
        }

        $this->player->update_player(true);
        return true;
    }

   /**
    * prints the bookmarks box down the side. should be a hook.
    *
    * @return string html
    */
    public function code_index_bookmarks_box() {

        $bookmark_html_unread = "";
        $bookmark_html_read = "";
        $bookmark_html_unapproved = "";
        $bookmark_ids = array();
        if (is_array($this->player->bookmarks)) {
            foreach ($this->player->bookmarks as $bookmark_id => $bookmark_last_read_replies) {
                $bookmark_id = intval($bookmark_id); // can never be too sure
                $bookmark_ids[] = $bookmark_id;
            }
        }

        $id_range = implode(",", $bookmark_ids);
        $reply_count_query = $this->db->execute("SELECT `angst_id`, COUNT(*) AS `c` FROM `angst_replies` WHERE `angst_id` IN (".$id_range.") GROUP BY `angst_id`");
        $bookmarks = json_decode($_COOKIE['bookmarks'], true);

        while ($reply_count = $reply_count_query->fetchrow()) {
            $reply_counts[$reply_count['angst_id']] = $reply_count['c'];
        }

        $approved_check_query = $this->db->execute("SELECT `id`, `approved` FROM `angst` WHERE `id` IN (".$id_range.")");
        
        while ($angst = $approved_check_query->fetchrow()) {
            $approved_array[$angst['id']] = $angst['approved'];
        }

        if (is_array($this->player->bookmarks)) {
            foreach ($this->player->bookmarks as $id => $read_count) {
                if (!isset($reply_counts[$id])) {
                    $reply_counts[$id] = 0;
                }
                if (!$approved_array[$id]) {
                    $bookmark_html_unapproved .= $this->skin->bookmark_id_unapproved($id);
                } else if ($reply_counts[$id] > $read_count && $reply_counts[$id] > $bookmarks[$id]) {
                    $diff = $reply_counts[$id] - max(array($read_count, $bookmarks[$id]));
                    $bookmark_html_unread .= $this->skin->bookmark_id_unread($id, $diff);
                } else {
                    $bookmark_html_read .= $this->skin->bookmark_id_read($id, $reply_counts[$id]);
                }
            }
        }
        
        if (empty($bookmark_html_unread) && empty($bookmark_html_read) && empty($bookmark_html_unapproved)) {
            $unread = $this->skin->error_box($this->skin->lang->no_bookmarks);
        }

        if ($bookmark_html_read) {
            $read = $this->skin->bookmark_read($bookmark_html_read);
        }
        
        if ($bookmark_html_unread) {
            $unread = $this->skin->bookmark_unread($bookmark_html_unread);
        }
        
        if ($bookmark_html_unapproved) {
            $unapproved = $this->skin->bookmark_unapproved($bookmark_html_unapproved);
        }

        $code_index_bookmark_box = $this->skin->bookmark_box($unread, $read, $unapproved);
        return $code_index_bookmark_box;
    }

}
?>
