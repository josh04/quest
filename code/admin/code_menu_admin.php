<?php
/**
 * Interface for adding/removing/moving menu items.
 *
 * @author josh04
 * @package code_public
 */
class code_menu_admin extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_menu_admin");

        $code_menu_admin = $this->menu_admin_switch();

        parent::construct($code_menu_admin);
    }

   /**
    * this is pretty apparent
    *
    * @return string html
    */
    protected function menu_admin_switch() {

        if ($_GET['action'] == 'edit') {
            $menu_admin_switch = $this->edit();
            return $menu_admin_switch;
        }

        if ($_GET['action'] == 'remove') {
            $menu_admin_switch = $this->remove();
            return $menu_admin_switch;
        }

        if ($_GET['action'] == 'up' || $_GET['action'] == 'down') {
            $menu_admin_switch = $this->move();
            return $menu_admin_switch;
        }

        $menu_admin_switch = $this->show_menu();
        return $menu_admin_switch;
    }

   /**
    * this is the main monster, displays the huge list of crap to edit.
    *
    * @param string $message error what?
    * @return string html
    */
    protected function show_menu($message = "") {

        $menu_query = $this->db->execute("SELECT * FROM `menu` ORDER BY `order` ASC");

        while($menu_entry = $menu_query->fetchrow()) {
            $menu_categories[$menu_entry['category']] .= $this->skin->make_menu_entry($menu_entry);
        }

        foreach($menu_categories as $category_name => $category_html) {
            $menu_html .= $this->skin->menu_category($category_name, $category_html);
        }

        if(isset($_GET['move_success']) && $message=="") $message = $this->skin->success_box($this->skin->lang_error->menu_moved);

        $show_menu = $this->skin->menu_wrap($menu_html, $menu_post, $message);
        return $show_menu;
    }

   /**
    * edits and adds menu items
    *
    * @return string html
    */
    protected function edit() {

        if(isset($_POST['menu-id'])) {
            $id = $_POST['menu-id'];
            $button_text = $_POST['menu-submit'];

            // Name-value pairs
            $item = array(
                'label' => $_POST['menu-label'],
                'category' => $_POST['menu-category'],
                'section' => $_POST['menu-section'],
                'page' => $_POST['menu-page'],
                'extra' => $_POST['menu-extra'],
                'enabled' => ($_POST['menu-enabled']=="on"?true:false),
                'function' => ($_POST['menu-function']=="on"?true:false),
                'guest' => ($_POST['menu-guest']=="on"?true:false)
            );

            // Error check
            if (!$item['label']) {
                $edit = $this->show_menu($this->skin->error_box($this->skin->lang_error->no_label));
                return $edit;
            }

            if (!$item['section']) {
                $edit = $this->show_menu($this->skin->error_box($this->skin->lang_error->no_section));
                return $edit;
            }

            if (!$item['page']) {
                $edit = $this->show_menu($this->skin->error_box($this->skin->lang_error->no_page));
                return $edit;
            }
            // Get me the Iron Giant!
            require_once("code/common/code_menu.php");
            $code_menu = new code_menu($this->db, $this->player, $this->section, $this->page, $this->pages);

            if($id=="-1") {
                $code_menu->add_menu_entry($item['label'], $item['category'], $item['section'],
                    $item['page'], $item['extra'], $item['function'], $item['enabled'], $item['guest']);
            } else {
                $code_menu->modify_menu_entry($id, $item['label'], $item['category'], $item['section'],
                    $item['page'], $item['extra'], $item['function'], $item['enabled'], $item['guest']);
            }
            header("location:?section=admin&page=menu");
        } else {
            $id = intval($_GET['id']);
            $itemq = $this->db->execute("SELECT * FROM `menu` WHERE `id`=?",array($id));
            if($itemq->numrows()==1) {
                $item  = $itemq->fetchrow();
                $button_text = "Save changes";
            } else {
                $item = array('id'=>'-1');
                $button_text = "Add new item";
            }
        }

        $modify = $this->skin->edit($item, $button_text, $message);
        return $modify;
    }

   /**
    * we're done with this one
    *
    * @return string html
    */
    protected function remove() {
        $id = intval($_GET['id']);

        if(isset($_POST['menu-id'])) {
            $del = $this->db->execute("DELETE FROM `menu` WHERE `id`=?",array($id));
            if($del) {
                $remove = $this->show_menu($this->skin->success_box($this->skin->lang_error->menu_deleted));
            } else {
                $remove = $this->show_menu($this->skin->error_box($this->skin->lang_error->menu_delete_no));
            }
        } else {
            $itemq = $this->db->execute("SELECT * FROM `menu` WHERE `id`=?",array($id));
            if($itemq->numrows()==1) {
                $item = $itemq->fetchrow();
                $remove = $this->skin->remove_confirm($item);
                }
            else $remove = $this->show_menu($this->skin->error_box($this->skin->lang_error->menu_not_found));
        }
        return $remove;
    }

   /**
    * Shuffle these little fellas around
    *
    * @return string html
    */
    protected function move() {
        $id = intval($_GET['id']);

        $itemq = $this->db->execute("SELECT * FROM `menu`");
        while($item = $itemq->fetchrow()) {
            $menu[($item['category'])][($item['order'])] = $item;
            if($item['id']==$id) $me = $item;
        }

        $pos = $me['order'];
        if($_GET['action']=="up") {
            $down = $menu[($me['category'])][($me['order']-1)];
            $up = $me;
        } else {
            $up = $menu[($me['category'])][($me['order']+1)];
            $down = $me;
        }

        // If these both exist, we're in the money!
        if($down && $up) {
            $down['order']++;
            $up['order']--;
            $this->db->AutoExecute("menu", $down, "UPDATE", "id=".$down['id']);
            $this->db->AutoExecute("menu", $up, "UPDATE", "id=".$up['id']);
            header("location:?section=admin&page=menu&move_success");
        } else {
            $message = $this->skin->error_box($this->skin->lang_error->menu_move_no);
        }

        $move = $this->show_menu($message);
        return $move;
    }

}
?>