<?php
/**
 * members list html
 *
 * @author josh04
 * @package skin_public
 */
class angst_skin_members extends angst_skin_common {
   
   /**
    * makes a single member row
    *
    * @param array $member member object
    * @param string $admin the admin edit profile link
    * @return string html
    */
    public function member_row($member, $admin) {

        $member_row = "<tr class='row".$num."'>
                    <td><a href='index.php?section=public&amp;page=profile&amp;id=" .$member['id']."'>
                    ".$member['username']."</a></td>
                    <td><a href='index.php?section=public&amp;page=mail&amp;action=compose&amp;to=".$member['username']."'>
                    Mail</a>
                        ".$admin."</td></tr>";

        return $member_row;
    }

   /**
    * the link for admins to edit this profile
    *
    * @param int $id player id
    * @return string html
    */
    public function admin_link($id) {
        $admin_link = " | <a href='index.php?section=admin&amp;page=profile_edit&amp;id=".$id."'>Edit</a>";
        return $admin_link;
    }

   /**
    * pieces together the members list
    *
    * @param integer $begin start record
    * @param integer $next next record
    * @param integer $previous previous record
    * @param integer $limit number of rows shown
    * @param string $member_rows rows themselves
    * @return string html
    */
    public function members_list($begin, $next, $previous, $limit, $member_rows) {
        $members_list = "
                        <h2>Members</h2>
                        <p><a href='index.php?section=public&amp;page=members&amp;begin=".$previous."&amp;limit=".$limit."'>&laquo; Previous Page</a> | <a href='index.php?section=public&amp;page=members&amp;begin=".$next."&limit=".$limit."'>Next Page &raquo;</a></p>

                        <p>Show <a href='index.php?section=public&amp;page=members&amp;begin=".$begin."&amp;limit=5'>5</a> | <a href='index.php?section=public&amp;page=members&amp;begin=".$begin."&amp;limit=10'>10</a>  | <a href='index.php?section=public&amp;page=members&amp;begin=".$begin."&amp;limit=20'>20</a> | <a href='index.php?section=public&amp;page=members&amp;begin=".$begin."&amp;limit=50'>50</a> | <a href='index.php?section=public&amp;page=members&amp;begin=".$begin."&amp;limit=100'>100</a> members per page
                        </p>

                        <table style='width:100%;margin: 12px 0px; border:1px solid #DDD; padding: 4px;' cellspacing='0' cellpadding='4'>
                        <tr style='background-color:#EEE;'>
                        <th>Username</td>
                        <th>Actions</td>
                        </tr>
                        ".$member_rows."
                        </table>
                        <p><a href='index.php?section=public&amp;page=members&amp;begin=".$previous."&amp;limit=".$limit."'>&laquo; Previous Page</a> | <a href='index.php?section=public&amp;page=members&amp;begin=".$next."&limit=".$limit."'>Next Page &raquo;</a></p>
                        ";
        return $members_list;
    }

}
?>
