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
    private function menu_admin_switch() {

        if ($_GET['action'] == 'add') {
            $menu_admin_switch = $this->add();
            return $menu_admin_switch;
        }

        if ($_GET['action'] == 'modify') {
            $menu_admin_switch = $this->modify();
            return $menu_admin_switch;
        }

        if ($_GET['action'] == 'reorder') {
            $menu_admin_switch = $this->reorder();
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
    private function show_menu($message = "") {
        $menu_post = array();
        if ($_GET['action'] == 'add') {
            $menu_post['label'] = htmlentities($_POST['label'], ENT_COMPAT, 'utf-8');
            $menu_post['section'] = htmlentities($_POST['section'], ENT_COMPAT, 'utf-8');
            $menu_post['page'] = htmlentities($_POST['page'], ENT_COMPAT, 'utf-8');
            $menu_post['extra'] = htmlentities($_POST['extra'], ENT_COMPAT, 'utf-8');
            $menu_post['category'] = htmlentities($_POST['category'], ENT_COMPAT, 'utf-8');

            if ($_POST['function']) {
                $menu_post['function'] = "checked='checked'";
            }

            if ($_POST['enabled']) {
                $menu_post['enabled'] = "checked='checked'";
            }

            if (!$menu_post['label'] && !$menu_post['section'] && !$menu_post['page']) {
                $menu_post['enabled'] = "checked='checked'";
            }
        }

        $menu_query = $this->db->execute("SELECT * FROM `menu` ORDER BY `order` ASC");

        $category_count = 0;

        while($menu_entry = $menu_query->fetchrow()) {

            if ($menu_entry['function']) {
                $menu_entry['function'] = "checked='checked'";
            } else {
                $menu_entry['function'] = '';
            }

            if ($menu_entry['enabled']) {
                $menu_entry['enabled'] = "checked='checked'";
            } else {
                $menu_entry['enabled'] = '';
            }

            $menu_categories[$menu_entry['category']] .= $this->skin->make_menu_entry($menu_entry);

            $menu_entry_count[$menu_entry['category']]++;

            if (!$category_id[$menu_entry['category']]) {
                $category_count++;
                $category_id[$menu_entry['category']] = $category_count;
            }
            $reorder_items_category[$menu_entry['category']] .= $this->skin->reorder_item($menu_entry['id'], $menu_entry['label'], $menu_entry['order'], $category_id[$menu_entry['category']]);
            $reorder_javascript_category[$menu_entry['category']] .= $this->skin->reorder_javascript($menu_entry['id'], $category_id[$menu_entry['category']]);
        }
        $category_id = 0;

        foreach($menu_categories as $category_name => $category_html) {
            $category_id++;
            $menu_html .= $this->skin->menu_category($category_name, $category_html);
            $height = $menu_entry_count[$category_name] * 30 + 50;
            $reorder_items .= $this->skin->reorder_category($category_id, $category_name, $reorder_items_category[$category_name], $height);
            $reorder_javascript .= $this->skin->reorder_javascript_category($category_id, $reorder_javascript_category[$category_name]);
            $reorder_categories_objects .= $this->skin->reorder_categories_object($category_id);
        }

        $show_menu = $this->skin->menu_wrap($menu_html, $menu_post, $message);
        $reorder_menu = $this->skin->reorder_menu($reorder_items, $reorder_javascript, $reorder_categories_objects);
        return $show_menu.$reorder_menu;
    }

   /**
    * are we changing one?
    *
    * @return string html
    */
    private function modify() {
        $id = intval($_POST['id']);

        if (!$id) {
            $modify = $this->show_menu($this->skin->lang_error->no_menu_entry_selected);
            return $modify;
        }

         if (!$_POST['label']) {
            $modify = $this->show_menu($this->skin->error_box($this->skin->lang_error->no_label));
            return $modify;
        }

        if (!$_POST['section']) {
            $modify = $this->show_menu($this->skin->error_box($this->skin->lang_error->no_section));
            return $modify;
        }

        if (!$_POST['page']) {
            $modify = $this->show_menu($this->skin->error_box($this->skin->lang_error->no_page));
            return $modify;
        }

        if ($_POST['function']) {
            $function = 1;
        }

        if ($_POST['enabled']) {
            $enabled = 1;
        }

        require_once("code/common/code_menu.php");
        $code_menu = new code_menu($this->db, $this->player, $this->section, $this->page, $this->pages);
        $code_menu->modify_menu_entry($id, $_POST['label'], $_POST['category'], $_POST['section'], $_POST['page'], $_POST['extra'], $function, $enabled);
        $modify = $this->show_menu($this->skin->success_box($this->skin->lang_error->menu_entry_modified));
        return $modify;
    }

   /**
    * A new menu thingy!
    *
    * @return string html
    */
    private function add() {
        if (!$_POST['label']) {
            $add = $this->show_menu($this->skin->error_box($this->skin->lang_error->no_label));
            return $add;
        }

        if (!$_POST['section']) {
            $add = $this->show_menu($this->skin->error_box($this->skin->lang_error->no_section));
            return $add;
        }

        if (!$_POST['page']) {
            $add = $this->show_menu($this->skin->error_box($this->skin->lang_error->no_page));
            return $add;
        }

        if ($_POST['function']) {
            $function = 1;
        }
        
        if ($_POST['enabled']) {
            $enabled = 1;
        }

        require_once("code/common/code_menu.php");
        $code_menu = new code_menu($this->db, $this->player, $this->section, $this->page, $this->pages);
        $code_menu->add_menu_entry($_POST['label'], $_POST['category'], $_POST['section'], $_POST['page'], $_POST['extra'], $function, $enabled);
        $add = $this->show_menu($this->skin->success_box($this->skin->lang_error->menu_entry_added));
        return $add;
    }

   /**
    * shuffle shuffle shuffle. I should copyright this algorithm, it's nuts
    *
    * @return string html
    */
    private function reorder() {
        $reorder_query = $this->db->execute("SELECT * FROM `menu`");

        while ($menu_item = $reorder_query->fetchrow()) {
            $menu_categories[$menu_item['category']][$menu_item['id']] = $menu_item;
        }

        $category_count = 0;
        foreach ($_POST['menu_id'] as $id) {

            foreach ($menu_categories as $category) {
                if ($category[$id]) {
                    if (!$category_counted[$category[$id]['category']]) {
                        $category_counted[$category[$id]['category']] = 1;
                        $category_offset[$category[$id]['category']] = $category_count;
                        $category_count++;
                    }
                    $menu_orders[$category[$id]['category']]++;
                    $order = $menu_orders[$category[$id]['category']] + $category_offset[$category[$id]['category']];
                    $menu_queries[] = array($id, $order, $id);
                }
            }
            
        }
        $this->db->execute("UPDATE `menu` SET `id`=?, `order`=? WHERE `id`=? ", $menu_queries);

        print $this->db->ErrorMsg();
        $reorder = $this->show_menu($this->skin->success_box($this->skin->lang_error->menu_reordered));
        return $reorder;

    }
}
?>