<?php
/**
 * Automatic admin page skin
 *
 * @author grego
 * @package skin_public
 */
class skin_general extends _skin_admin {
    
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
        <form action='' method='post'><table>
            <tr><td style='width:20%;'></td><td></td></tr>
".$html."
            <tr><td></td><td><input type='submit' value='Save changes' /></td></tr>
        </table></form>";
        return $index_wrap;
    }

   /**
    * add a textarea field
    *
    * @param array $f field details
    * @return string html
    */
    public function add_field($f) {
        // A few special cases to deal with first...
        if($f['type']=='caption') return "<tr><td></td><td>".$f['value']."</td></tr>";
        if($f['type']=='text') $g = "<input type='".$f['type']."' id='form-".$f['name']."' name='form-".$f['name']."' style='width:95%;' value='".$f['value']."' />";
        if($f['type']=='checkbox') $g = "<input type='checkbox' id='form-".$f['name']."' name='form-".$f['name']."' ".($f['value']?" checked='checked'":"")." />";

        // ...then the default
        if(!isset($g)) $g = "<textarea id='form-".$f['name']."' name='form-".$f['name']."' rows='5' style='width:95%;'>".$f['value']."</textarea>";
        return "
            <tr><td><label for='form-".$f['name']."'".($f['type']=='checkbox'?" style='margin:0;'":"").">".($f['caption']?$f['caption']:$f['name'])."</label></td>
            <td>".$g."</td></tr>";
    }

}
?>
