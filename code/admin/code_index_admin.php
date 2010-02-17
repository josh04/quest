<?php
/**
 * Administrator index page
 *
 * @author grego
 * @package code_admin
 */
class code_index_admin extends code_common_admin {
   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_index_admin");

        $code_index = $this->index_wrapper();

        return $code_index;
    }

   /**
    * generate main admin index page
    *
    * @return string html
    */
    public function index_wrapper() {
        $boxes = array(
            array( 'panel', 'General administration', 'Your administration panel is your home - design it to your tastes' ),
            array( 'index', 'Configuration', array(
                array( 'cron', 'Scheduled tasks' ),
                array( 'pages', 'Page controls' ),
                array( 'help', 'Help files' ),
            ) ),
            array( 'index', 'Gameplay', array(
                array( 'blueprints', 'Item blueprints' ),
                array( 'quest', 'Quests' ),
                array( 'portal', 'Portal' ),
            ) ),
            array( 'ticket', 'Tickets', 'Read, organise and respond to tickets' ),
            array( 'messages', 'Messages', 'Edit specific in-game messages to your choosing.'),
            array( 'mods', 'Mods', 'Configuration pages for loaded mods')
        );
        
        $i = 0;
        foreach($boxes as $box) {
            $i++;
            if ($box[0]!='') {
                $box[1] = $this->skin->admin_box_link($box[0], $box[1]);
            }

            if(is_array($box[2])) {
                $temp = '';
                foreach($box[2] as $item) {
                    $temp .= $this->skin->admin_inner_link($item[0], $item[1]);
                }
                $box[2] = $temp;
            }

            $boxes_html .= $this->skin->admin_box($box[1], $box[2]);

            if ($i%2==0) {
                $boxes_html .= $this->skin->admin_box_join();
            }
        }

        if (get_magic_quotes_gpc()) {
            $warning = $this->skin->magic_quotes_enabled();
        }
        $this->core('bbcode');
        $index = $this->skin->index_wrapper($boxes_html, $this->bbcode->parse($this->settings->get['admin_notes'], true), $warning);

        return $index;
    }

}
?>
