<?php
/**
 * Automatic administation page generation
 * (TODO) Implement this for portal + more
 *
 * @author grego
 * @package code_admin
 */
class code_general extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_general");

        $this->data = $this->get_data();
        if(empty($this->data)) $code = $this->skin->error_page($this->skin->lang_error->page_not_exist);
        else $code = $this->general_wrapper();

        parent::construct($code);
    }

   /**
    * generate portal admin page
    *
    * @return string html
    */
    public function general_wrapper() {

        if(count($_POST)) {
        foreach($this->data['fields'] as $f) {
            if($f['type']=="checkbox") $_POST['form-'.($f['name'])] = ($_POST['form-'.($f['name'])]=="on"?1:0);
            if($f['type']=="caption") continue;
            $a[] = $f['name'];
            $b[] = stripslashes($_POST['form-'.($f['name'])]);
        }
        $t = $this->setting_update($a, $b);

        if($t) $message = $this->skin->success_box($this->skin->lang_error->changes_saved);
        else $message = $this->skin->error_box($this->skin->lang_error->changes_saved_no);
        }

        foreach($this->data['fields'] as $f) {
            if(!$f['value']) $f['value'] = $this->settings[($f['name'])];
            $html .= $this->skin->add_field($f);
        }

        $index = $this->skin->general_wrapper($this->data, $html, $message);
        return $index;
    }

   /**
    * get settings to be used in page
    *
    * @return string html
    */
    public function get_data() {
        $data = array();

        // Portal
        if($_GET['page']=="portal") $data = array('title'=>'Portal','description'=>$this->skin->lang_error->admin_portal_home,
            'fields' => array(
                array('name'=>'portal_name','caption'=>'Portal name','type'=>'text'),
                array('name'=>'portal_welcome','caption'=>'Introduction message'),
                array('type'=>'caption', 'value'=>$this->skin->lang_error->admin_link_syntax),
                array('name'=>'portal_links','caption'=>'Portal links'),
            ),
        );

        // Admin panel
        if($_GET['page']=="panel") $data = array('title'=>'Admin panel','description'=>'',
            'fields' => array(
                array('name'=>'admin_notes','caption'=>'Notes for administrators'),
            ),
        );

        return $data;
    }

}
?>
