<?php
/**
 * Interface for adding/removing/moving menu items.
 *
 * @author grego
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
        switch($_GET['action']) {
            case "edit":
                $menu_admin_switch = $this->edit();
            case "remove":
                $menu_admin_switch = $this->remove();
                break;
            case "up":
            case "down":
                $menu_admin_switch = $this->move();
                break;
            case "category_up":
            case "category_down":
                $menu_admin_switch = $this->category_move();
                break;
            default:
                $menu_admin_switch = $this->show_menu();
        }
        
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
            $category_name = htmlentities($category_name, ENT_QUOTES, 'utf-8');
            $menu_html .= $this->skin->menu_category($category_name, $category_html);
        }

        if (isset($_GET['move_success']) && $message=="") {
            $message = $this->skin->success_box($this->skin->lang_error->menu_moved);
        }

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
            $item_query = $this->db->execute("SELECT * FROM `menu` WHERE `id`=?",array($id));
            if($item_query->numrows()==1) {
                $item  = $item_query->fetchrow();
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
            $item_delete_query = $this->db->execute("DELETE FROM `menu` WHERE `id`=?",array($id));
            
            if($item_delete_query) {
                $remove = $this->show_menu($this->skin->success_box($this->skin->lang_error->menu_deleted));
            } else {
                $remove = $this->show_menu($this->skin->error_box($this->skin->lang_error->menu_delete_no));
            }
        } else {
            $item_query  = $this->db->execute("SELECT * FROM `menu` WHERE `id`=?",array($id));
            if ($item_query->numrows()==1) {
                $item = $item_query->fetchrow();
                $remove = $this->skin->remove_confirm($item);
                } else {
                    $remove = $this->show_menu($this->skin->error_box($this->skin->lang_error->menu_not_found));
                }
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

        $item_query = $this->db->execute("SELECT * FROM `menu`");
        while($item = $item_query->fetchrow()) {
            $menu[($item['category'])][($item['order'])] = $item;
            if($item['id']==$id) $me = $item;
        }

        $pos = $me['order'];
        if ( $_GET['action'] == "up" ) {
            $down = $menu[($me['category'])][($me['order']-1)];
            $up = $me;
            $message = $this->skin->lang_error->menu_move_up_no;
        } else {
            $up = $menu[($me['category'])][($me['order']+1)];
            $down = $me;
            $message = $this->skin->lang_error->menu_move_down_no;
        }

        // If these both exist, we're in the money!
        if($down && $up) {
            $down['order']++;
            $up['order']--;
            $this->db->AutoExecute("menu", $down, "UPDATE", "id=".$down['id']);
            $this->db->AutoExecute("menu", $up, "UPDATE", "id=".$up['id']);
            header("location:?section=admin&page=menu&move_success");
        } else {
            $message = $this->skin->error_box($message);
        }

        $move = $this->show_menu($message);
        return $move;
    }

    protected function category_move() {
        $move_category_name = htmlentities($_GET['category'], ENT_QUOTES,'utf-8');

        $reorder_query = $this->db->execute("SELECT * FROM `menu` ORDER BY `order` ASC");

        while ($menu_item = $reorder_query->fetchrow()) {
            $menu_categories[$menu_item['category']][$menu_item['id']] = $menu_item;
        }

        if (!is_array($menu_categories[$move_category_name])) {
            $category_move = $this->show_menu($this->skin->error_box($this->skin->lang_error->category_not_found));
            return $category_move;
        }
        
        foreach ($menu_categories as $category_name => $category) {

            if ($category_name == $move_category_name) {

                if ($_GET['action'] == 'category_down') {
                    $do_next = 1;
                    continue;
                }
                if ($_GET['action'] == 'category_up' && $not_first) {
                    $do_done = 1;
                    foreach ($category as $menu_entry) {
                         $menu_queries[] = array($menu_entry['id'], $menu_entry['order'] - 1, $menu_entry['id'], $menu_entry['category']);
                    }

                    foreach ($category_previous as $menu_entry_previous) {
                         $menu_queries[] = array($menu_entry_previous['id'], $menu_entry_previous['order'] + 1, $menu_entry_previous['id'], $menu_entry_previous['category']);
                    }
                }
            }
            $not_first = 1;
            if ($do_next) {
                $do_done = 1;
                $do_next = 0;

                foreach ($category as $menu_entry) {
                     $menu_queries[] = array($menu_entry['id'], $menu_entry['order'] - 1, $menu_entry['id'], $menu_entry['category']);
                }

                foreach ($menu_categories[$move_category_name] as $menu_entry_previous) {
                     $menu_queries[] = array($menu_entry_previous['id'], $menu_entry_previous['order'] + 1, $menu_entry_previous['id'], $menu_entry_previous['category']);
                }
            }
           
            $category_previous = $category;
        }
        
        if(!$do_done) {
            if ($_GET['action'] == 'category_down') {
                $category_move = $this->show_menu($this->skin->error_box($this->skin->lang_error->category_move_down_no));
                return $category_move;
            }

            if ($_GET['action'] == 'category_up') {
                $category_move = $this->show_menu($this->skin->error_box($this->skin->lang_error->category_move_up_no));
                return $category_move;
            }
        }

        $this->db->execute("UPDATE `menu` SET `id`=?, `order`=? WHERE `id`=? ", $menu_queries);

        $reorder = $this->show_menu($this->skin->success_box($this->skin->lang_error->menu_reordered));
        return $reorder;
    }

}
?>