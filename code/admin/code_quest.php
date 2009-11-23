<?php
/**
 * Organise/install and delete quests
 *
 * @author grego
 * @package code_admin
 */
class code_quest extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_quest");

        $code_quest = $this->quest_page();

        return $code_quest;
    }

   /**
    * generate main admin quest page
    *
    * @return string html
    */
    public function quest_page() {
        if(isset($_GET['url'])) {
            $message = $this->install_quest($_GET['url']);
        }

        if(isset($_GET['delete'])) {
            $message = $this->delete_quest($_GET['delete']);
        }

        if(isset($_GET['code'])) {
            $message = $this->update_code($_GET['code']);
        }

        $quest_query = $this->db->execute("SELECT * FROM `quests`");
        
        while ($quest = $quest_query->fetchrow()) {
            $quest['description_short'] = substr($quest['description'],0,80);
            if (strlen($quest['description']) > 80) {
                $quest['description_short'] .= "...";
            }
            $quest_html .= $this->skin->quest_row($quest);
        }
        if (!$quest_html) {
            $message = $this->skin->error_box($this->lang->no_quests);
        }
        return $this->skin->quest_wrapper($quest_html, $this->settings['quests_code'], $message);
    }

   /**
    * install a new quest
    *
    * @param string $url location of quest
    * @return string html
    */
    public function install_quest($url) {
        $handle = @fopen($url,'r');
        if (!$handle) {
            return $this->skin->error_box($this->lang->quest_not_found);
        }

        // First, let's stick it in the database
        $xml = simplexml_load_file($url);

        if (!(isset($xml->title) && isset($xml->body) && isset($xml->author))) {
            return $this->skin->error_box($this->lang->quest_install_no);
        }

        $quest_query = $this->db->execute("INSERT INTO `quests` (`title`,`description`,`author`) VALUES (?,?,?)",array($xml->title,$xml->body,$xml->author));

        if (!$quest_query) {
            return $this->skin->error_box($this->lang->quest_install_no);
        }

        // Then we'll copy it into our files
        $insert_id = $this->db->GetOne("SELECT `id` FROM `quests` ORDER BY `id` DESC");
        
        if(!copy($url, "quests/quest-".md5($this->settings['quests_code'].$insert_id).".xml")) {
            return $this->skin->error_box($this->lang->quest_install_no);
        }

        return $this->skin->success_box($this->lang->quest_install_success);
    }

   /**
    * delete a quest
    *
    * @param string $id id of quest
    * @return string html
    */
    public function delete_quest($id, $keep_file=false) {

        // Remove it from the database
        $delete_query = $this->db->execute("DELETE FROM `quests` WHERE `id`=?", array($id));

        if (!$delete_query) {
            return $this->skin->error_box($this->lang->quest_delete_no);
        }

        // Then rip it out
        if ($keep_file == false) {
            if (!@unlink("quests/quest-".md5($this->settings['quests_code'].$id).".xml")) {
                return $this->skin->error_box($this->lang->quest_delete_no);
            }
        }

        return $this->skin->success_box($this->lang->quest_delete_success);
    }

   /**
    * update the quests security code
    *
    * @param string $id id of quest
    * @return string html
    */
    public function update_code($newcode) {
        // Update all the current quests with the new code
        $quest_query = $this->db->execute("SELECT `id` FROM `quests`");

        while ($quest = $quest_query->fetchrow()) {
                $quest['old'] = "quests/quest-".md5($this->settings['quests_code'].$quest['id']).".xml";
                $quest['new'] = "quests/quest-".md5($newcode.$quest['id']).".xml";
                copy($quest['old'],$quest['new']);
                unlink($quest['old']);
        }

        $attempt = $this->setting->set('quests_code',$_GET['code']);

        if ($attempt) {
            $update_code = $this->skin->success_box($this->lang->setting_update_yes);
        } else {
            $update_code = $this->skin->error_box($this->lang->setting_update_no);
        }
        return $update_code;
    }

}
?>
