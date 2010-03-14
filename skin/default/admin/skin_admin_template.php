<?php
/**
 * Automatic admin page skin
 *
 * @author grego
 * @package skin_admin
 */
class skin_admin_template extends skin_common_admin {
    
   /**
    * general wrapper
    *
    * @param array $data everything you could ever want to know
    * @param string $html all the fields, formatted
    * @param string $message any messages or warnings to precede
    * @return string html
    */
    public function general_wrapper($data, $html, $message='') {
        $index_wrap = "
        <h2>".$data['title']."</h2>
        ".$message."
        ".($data['description']?"<div class='explanation' style='margin:12px 0px;'>".$data['description']."</div>":"")."
        <form action='index.php?section=admin&amp;page=".$data['page']."' method='post'><table>
            <tr><td></td><td></td></tr>
".$html."
            <tr><td></td><td><input type='submit' value='Save changes' /></td></tr>
        </table></form>";
        return $index_wrap;
    }

   /**
    * add a field
    *
    * @param array $f field details
    * @param string $lang language
    * @return string html
    */
    public function add_field($f, $lang='') {
        // A few special cases to deal with first...
        if($f['type']=='caption') return "<tr><td></td><td>".$f['value']."</td></tr>";
        if($f['type']=='text') $g = "<input type='".$f['type']."' id='form-".$f['name']."' name='form-".$f['name']."' style='width:95%;' value='".$f['value']."' />";
        if($f['type']=='checkbox') $g = "<input type='checkbox' id='form-".$f['name']."' name='form-".$f['name']."' ".($f['value']?" checked='checked'":"")." />";
        if($f['type']=='radio') $g = $this->add_field_radio($f);

        // ...then the default
        if(!isset($g)) $g = "<textarea id='form-".$f['name']."' name='form-".$f['name']."' rows='5' style='width:95%;'>".$f['value']."</textarea>";
        return "
            <tr><td><label for='form-".$f['name']."'".($f['type']=='checkbox'?" style='margin:0;'":"").">".($f['caption']?$f['caption']:$f['name'])."</label>".$lang."</td>
            <td>".$g."</td></tr>";
    }

   /**
    * add a set of radio buttons
    *
    * @param array $f field details
    * @return string html
    */
    public function add_field_radio($f, $lang='') {
        foreach($f['options'] as $a) {
            $contents .= "
                <input type='radio' name='form-".$f['name']."' id='form-".$f['name']."-".$a['value']."' value='".$a['value']."' ".($f['value']==$a['value']?" checked='checked'":"")."/>
                <label for='form-".$f['name']."-".$a['value']."' style='display:inline;'>".$a['caption']."</label><br />\n";
        }
        return "" . $contents . "";
    }

}
?>
