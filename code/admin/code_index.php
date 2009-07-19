<?php
/**
 * Administrator index page
 *
 * @author grego
 * @package code_admin
 */
class code_index extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_index");

        $code_index = $this->index_wrapper();

        parent::construct($code_index);
    }

   /**
    * generate main admin index page
    *
    * @return string html
    */
    public function index_wrapper() {
        $boxes = array(
            array( 'panel', 'Admin panel', 'Your administration panel is your home - design it to your tastes' ),
            array( 'ticket', 'Tickets', 'Read, organise and respond to tickets' ),
            array( 'blueprints', 'Item Blueprints', 'Add, remove and organise items' ),
            array( 'quest', 'Quests', 'Add, remove and organise quests' ),
            array( 'portal', 'Portal', 'Control the portal' )
        );
        $i = 0;
        foreach($boxes as $box) {
            $i++;
            $boxes_html .= $this->skin->admin_box($box, ($i%2==0));
        }
        $index = $this->skin->index_wrapper($boxes_html, $this->settings['admin_notes']);
        return $index;
    }

}
?>
