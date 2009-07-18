<?php
/**
 * Skinning for admin portal section
 *
 * @author grego
 * @package skin_public
 */
class skin_portal extends _skin_admin {
    
   /**
    * portal wrapper
    *
    * @param array $quest_html quests in table rows
    * @param string $message any messages or warnings to precede
    * @return string html
    */
    public function portal_wrapper($portal_name, $portal_welcome, $portal_links, $message='') {
        $index_wrap = "
        <h2>Portal control</h2>
        ".$message."
        <div class='explanation' style='margin:12px 0px;'>".$this->lang_error->admin_portal_home."</div>
        <form action='' method='post'><table>
            <tr><td style='width:20%;'><label for='portal_name'>Portal name</label></td>
            <td><input type='text' id='portal_name' name='portal_name' value='".$portal_name."' style='width:95%;' /></td></tr>

            <tr><td><label for='portal_welcome'>Introduction text</label></td>
            <td><textarea id='portal_welcome' name='portal_welcome' rows='5' style='width:95%;'>".$portal_welcome."</textarea></td></tr>

            <tr><td></td><td>".$this->lang_error->admin_portal_syntax."</td></tr>

            <tr><td><label for='portal_links'>Portal links</label></td>
            <td><textarea id='portal_links' name='portal_links' rows='5' style='width:95%;'>".$portal_links."</textarea></td></tr>

            <tr><td></td><td><input type='submit' value='Save changes' /></td></tr>
        </table></form>";
        return $index_wrap;
    }

}
?>
