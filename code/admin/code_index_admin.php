<?php
/**
 * Administrator index page
 *
 * @author grego
 * @package code_admin
 */
class code_index_admin extends _code_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_index_admin");

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
            array( 'portal', 'Portal', 'Control the portal' ),
            array( 'messages', 'Messages', 'Edit specific in-game messages to your choosing.')
        );
        
        $i = 0;
        foreach($boxes as $box) {
            $i++;
            if ($box[0]!='') {
                $box[1] = $this->skin->admin_box_link($box[0], $box[1]);

            }
            $boxes_html .= $this->skin->admin_box($box[1], $box[2]);
            if ($i%2==0) {
                $boxes_html .= $this->skin->admin_box_join();
            }
        }

        if (get_magic_quotes_gpc()) {
            $warning = $this->skin->magic_quotes_enabled();
        }
        
        $index = $this->skin->index_wrapper($boxes_html, $this->bbparse($this->settings['admin_notes'],true), $warning);

        return $index;
    }

}
?>
