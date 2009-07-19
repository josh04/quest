<?php
/**
 * Skinning for admin section
 *
 * @author grego
 * @package skin_public
 */
class skin_index extends _skin_admin {
    
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
        <div class='explanation' style='margin:12px 0px;'>".$this->lang_error->admin_home."</div>
        <div class='dbox'><strong>Administrator notes:</strong><br />
            <div style='margin:8px 20px;font-family:\"Courier New\";'>".$notes."</div>
            Edit them <a href='index.php?section=admin&amp;page=panel'>here</a>
        </div>
        <table class='admin-home-table'><tr>
        ".$boxes_html."
        </tr></table>
        ";
        return $index_wrap;
    }

   /**
    * boxes for admin panel
    *
    * @param array $box contents of box
    * @param boolean $newline whether to append a newline
    * @return string html
    */
    public function admin_box($box, $newline=false) {
        if($box[0]!='') $box[1] = "<a href='index.php?section=admin&amp;page=".$box[0]."'>" . $box[1] . "</a>";
        $box_html = "<td><strong>".$box[1]."</strong>".$box[2]."</td>";
        if($newline) $box_html .= "</tr>\n<tr>";
        return $box_html;
    }

}
?>
