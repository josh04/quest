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

        parent::construct($code_quest);
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

        $questq = $this->db->execute("SELECT * FROM `quests`");
        while($quest = $questq->fetchrow()) $quest_html .= $this->skin->quest_row($quest);
        return $this->skin->quest_wrapper($quest_html, $message);
    }

   /**
    * install a new quest
    *
    * @param string $url location of quest
    * @return string html
    */
    public function install_quest($url) {
        $handle = @fopen($url,'r');
        if(!$handle) return "<div class='error'>".$this->skin->lang_error->quest_found_no."</div>";

        // First, let's stick it in the database
        $xml = simplexml_load_file($url);
        if(!(isset($xml->title) && isset($xml->body) && isset($xml->author))) return "<div class='error'>".$this->skin->lang_error->quest_install_no."</div>";
        $d = $this->db->execute("INSERT INTO `quests` (`title`,`description`,`author`) VALUES (?,?,?)",array($xml->title,$xml->body,$xml->author));
        if(!$d) return "<div class='error'>".$this->skin->lang_error->quest_install_no."</div>";

        // Then we'll copy it into our files
        $insert_id = $this->db->GetOne("SELECT `id` FROM `quests` ORDER BY `id` DESC");
        if(!copy($url, "quests/quest-".$insert_id.".xml")) return "<div class='error'>".$this->skin->lang_error->quest_install_no."</div>";

        return "<div class='success'>".$this->skin->lang_error->quest_install_success."</div>";
    }

   /**
    * delete a quest
    *
    * @param string $id id of quest
    * @return string html
    */
    public function delete_quest($id, $keep_file=false) {

        // Remove it from the database
        $d = $this->db->execute("DELETE FROM `quests` WHERE `id`=?",array($id));
        if(!$d) return "<div class='error'>".$this->skin->lang_error->quest_delete_no."</div>";

        // Then rip it out
        if($keep_file==false) {
        if(!@unlink("quests/quest-".$id.".xml")) return "<div class='error'>".$this->skin->lang_error->quest_delete_no."</div>";
        }

        return "<div class='success'>".$this->skin->lang_error->quest_delete_success."</div>";
    }

}
?>
