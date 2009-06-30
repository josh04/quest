<?php
/**
 * members list html
 *
 * @author josh04
 * @package skin_public
 */
class skin_members extends skin_common {
   
   /**
    * makes a single member row
    *
    * @param array $member member object
    * @return string html
    */
    public function member_row($member) {

        $member_row = "<tr class='row".$num."'>
                    <td><a href='index.php?page=profile&amp;id=" .$member['id']."'>
                    ".$member['username']."</a></td><td>".$member['level']."</td>
                    <td><a href='index.php?page=mail&amp;action=compose&amp;to=".$member['username']."'>
                    Mail</a> | <form method='POST' name='memberBattle".$member['id']."' action='index.php?page=battle&amp;action=fight' style='display:inline;'>
                        <input type='hidden' name='id' value='".$member['id']."' />
                        <a href='#' onclick='document.memberBattle".$member['id'].".submit();return false;'>Battle</a>
                    </form></td></tr>";

        return $member_row;
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
                        <p><a href='index.php?page=members&amp;begin=".$previous."&amp;limit=".$limit."'>&laquo; Previous Page</a> | <a href='index.php?page=members&amp;begin=".$next."&limit=".$limit."'>Next Page &raquo;</a></p>

                        <p>Show <a href='index.php?page=members&amp;begin=".$begin."&amp;limit=5'>5</a> | <a href='index.php?page=members&amp;begin=".$begin."&amp;limit=10'>10</a>  | <a href='index.php?page=members&amp;begin=".$begin."&amp;limit=20'>20</a> | <a href='index.php?page=members&amp;begin=".$begin."&amp;limit=50'>50</a> | <a href='index.php?page=members&amp;begin=".$begin."&amp;limit=100'>100</a> members per page
                        </p>

                        <table style='width:100%;margin: 12px 0px; border:1px solid #DDD; padding: 4px;' cellspacing='0' cellpadding='4'>
                        <tr style='background-color:#EEE;'>
                        <th>Username</td>
                        <th>Level</td>
                        <th>Actions</td>
                        </tr>
                        ".$member_rows."
                        </table>
                        <p><a href='index.php?page=members&amp;begin=".$previous."&amp;limit=".$limit."'>&laquo; Previous Page</a> | <a href='index.php?page=members&amp;begin=".$next."&limit=".$limit."'>Next Page &raquo;</a></p>
                        ";
        return $members_list;
    }

}
?>
