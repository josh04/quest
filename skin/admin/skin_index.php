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
    * @param string $message any messages or warnings to precede
    * @return string html
    */
    public function index_wrapper($message='') {
        $index_wrap = "
        <h2>Administration</h2>
        ".$message."
        <div class='explanation' style='margin:12px 0px;'>".$this->lang_error->admin_home."</div>
        <ul>
            <li><a href='index.php?section=admin&page=ticket'>Ticket control</a></li>
            <li style='margin:12px 0px;'><a href='index.php?section=admin&page=blueprints'>Item blueprints</a></li>
            <li><a href='index.php?section=admin&page=quest'>Quest control</a></li>
            <li style='margin:12px 0px;'><a href='index.php?section=admin&page=portal'>Portal control</a></li>
        </ul>";
        return $index_wrap;
    }

}
?>
