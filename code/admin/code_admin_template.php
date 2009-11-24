<?php
/**
 * Automatic administation page generation
 *
 * @author grego
 * @package code_admin
 */
class code_admin_template extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_admin_template");

        $this->data = $this->get_data();
        if (empty($this->data)) {
            $code = $this->skin->error_page($this->lang->page_not_exist);
        } else {
            $code = $this->general_wrapper();
        }

        return $code;
    }

   /**
    * generate portal admin page
    *
    * @return string html
    */
    public function general_wrapper() {

        if(count($_POST)) {
            foreach ($this->data['fields'] as $data_field) {
                if ($data_field['type']=="checkbox") {
                    $_POST['form-'.($data_field['name'])] = ($_POST['form-'.($data_field['name'])]=="on"?1:0);
                }
                if ($data_field['type']=="caption") {
                    continue;
                }
                $field_names[] = $data_field['name'];
                $field_values[] = $_POST['form-'.($data_field['name'])]; // turn magic_quotes_gpc off if you need to strip slashes; it's a POS.
            }
            $settings_update = $this->settings->set($field_names, $field_values);

            if ($settings_update) {
                $message = $this->skin->success_box($this->lang->changes_saved);
            } else {
                $message = $this->skin->error_box($this->lang->changes_saved_no);
            }
        }

        foreach($this->data['fields'] as $data_field) {
            if (!$data_field['value']) {
                $data_field['value'] = $this->settings->get[($data_field['name'])];
            }
            $lang = $this->language($data_field['lang']);
            $html .= $this->skin->add_field($data_field, $lang);
        }

        $this->data['page'] = $this->page;
        $index = $this->skin->general_wrapper($this->data, $html, $message);
        return $index;
    }

   /**
    * get settings to be used in page
    * (TODO) There must be a better place for these phat arrays [GT]
    *
    * @return string html
    */
    public function get_data() {
        $data = array();

        // Portal
        if ($this->page == "portal") {
            $data = array('title'=>'Portal','description'=>$this->lang->admin_portal_home,
                'fields' => array(
                    array('name'=>'portal_name','caption'=>'Portal name','type'=>'text'),
                    array('name'=>'portal_welcome','caption'=>'Introduction message','lang'=>'BBCode'),
                    array('type'=>'caption', 'value'=>$this->lang->admin_link_syntax,'lang'=>'link'),
                    array('name'=>'portal_links','caption'=>'Portal links'),
                ),
            );
        }

        // Admin panel
        if ($this->page == "panel") {
            $data = array('title'=>'Admin panel','description'=>'','fields_width'=>70,
                'fields' => array(
                    array('type'=>'text', 'name'=>'name','caption'=>'Project name'),
                    array('name'=>'admin_notes','caption'=>'Notes for administrators','lang'=>'BBCode'),
                    array('type'=>'checkbox','name'=>'ban_multiple_email','caption'=>'Ban multiple accounts from one email address'),
                    array('type'=>'checkbox','name'=>'register_ip_check','caption'=>'Registration 30 minute cool-off period?'),
                    array('type'=>'radio', 'name'=>'verification_method', 'caption'=>'Verification method',
                        'options' => array(
                            array('value'=>'0', 'caption'=>'Disable registration'),
                            array('value'=>'1', 'caption'=>'Don\'t verify new users'),
                            array('value'=>'2', 'caption'=>'Require email verification'),
                            array('value'=>'3', 'caption'=>'Require administrator approval'),
                        ),
                    ),
                    array('type'=>'caption','value'=>'<h3>Avatars</h3>'),
                    array('type'=>'checkbox','name'=>'avatar_url','caption'=>'Allow players to enter an avatar by URL?'),
                    array('type'=>'checkbox','name'=>'avatar_gravatar','caption'=>'Allow players to use <a href="http://www.gravatar.com/" target="_blank">Gravatars</a>?'),
                    array('type'=>'checkbox','name'=>'avatar_upload','caption'=>'Allow players to upload avatars (in <em>/images/avatars</em>)?'),
                    array('type'=>'checkbox','name'=>'avatar_library','caption'=>'Enable an avatar library (in <em>/images/library</em>)?'),
                ),
            );
        }
        return $data;
    }

   /**
    * get languages that can be used
    *
    * @param mixed $lang language
    * @return string html
    */
    public function language($lang) {
        if (!$lang) {
            return;
        }
        if (is_string($lang)) {
            $lang = array($lang);
        }
        $lang = implode(",", $lang);
        return "(" . $lang . ")";
    }


}
?>
