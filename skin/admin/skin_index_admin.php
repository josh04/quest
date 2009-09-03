<?php
/**
 * Skinning for admin section
 *
 * @author grego
 * @package skin_admin
 */
class skin_index_admin extends _skin_admin {
    
   /**
    * index page wrapper
    *
    * @param string $boxes_html boxes to display
    * @param string $message any messages or warnings to precede
    * @return string html
    */
    public function index_wrapper($boxes_html, $notes, $message='') {
        $index_wrap = "
        <h2>Administration</h2>
        ".$message."
        <div class='explanation' style='margin:12px 0px;'>".$this->lang->admin_home."</div>
        <div class='dbox'><strong>Administrator notes:</strong><br />
            <div style='margin:8px 20px;font-family:\"Courier New\";'>".$notes."</div>
            Edit them <a href='index.php?section=admin&amp;page=panel'>here</a>
        </div>
        <table class='admin-home-table'><tr>
        ".$boxes_html."
        </tr></table>";
        return $index_wrap;
    }

   /**
    * boxes for admin panel
    *
    * @param string $title title of box
    * @param string $content box message
    * @return string html
    */
    public function admin_box($title, $content) {
        $box_html = "<td><strong>".$title."</strong>".$content."</td>";
        return $box_html;
    }

   /**
    * Tiny joining html for going between admin boxes
    *
    * @return string html
    */
    public function admin_box_join() {
        $admin_box_join = "</tr>\n<tr>";
        return $admin_box_join;
    }

   /**
    * Does the box have a link?
    *
    * @param string $page which page to link to?
    * @param string $title The link title
    * @return string html
    */
    public function admin_box_link($page, $title) {
        $admin_box_link = "<a href='index.php?section=admin&amp;page=".$page."'>" . $title . "</a>";
        return $admin_box_link;
    }

   /**
    * Are we telling them off for using magic_quotes_gpc?
    *
    * @return string html
    */
    public function magic_quotes_enabled() {
        $magic_quotes_enabled = "<div class='error'><h4>Warning: Quest has detected that you have magic_quotes_gpc enabled in your php.ini.</h4>
            <p>Is is possible to run Quest with magic_quotes_gpc enabled, however it does not aid security and is detrimental to performance.</p>
            <p>Quest is not designed to run with magic_quotes_gpc enabled and doing so may produce odd results in some cases.</p>
            </div>";
        return $magic_quotes_enabled;
    }

}
?>
