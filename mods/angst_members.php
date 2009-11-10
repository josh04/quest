<?php
/**
 * Description of angst_members
 *
 * @author josh04
 * @package code_angst
 */
class angst_members extends code_members {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {

        $this->initiate("skin_members");

        $code_members = $this->members_list();
        
        return $code_members;
    }

   /**
    * constructs list of members.
    *
    * @return string html
    */
    public function members_list() {
        
        $limit = ($_GET['limit']) ? intval($_GET['limit']) : 30; //Use user-selected limit of players to list
        $begin = ($_GET['begin']) ? abs(intval($_GET['begin'])) : $this->player->id - intval($limit / 2); //List players with the current player in the middle of the list

        $total_players = $this->db->getone("SELECT COUNT(`id`) AS `count` FROM `players`");
        $begin = ($begin >= $total_players) ? abs($total_players - $limit) : $begin; //Can't list players don't don't exist yet either

        $begin = ($begin < 0) ? 0 : $begin;

        $lastpage = (($total_players - $limit) < 0) ? 0 : $total_players - $limit; //Get the starting point if the user has browsed to the last page

        $previous = $begin - $limit;
        $next = $begin + $limit;

        if ($_GET['action'] == "staff") {
            $memberlist = $this->db->execute("SELECT `id`, `username`
                FROM `players` WHERE `rank`='Admin' ORDER BY `registered` ASC
                LIMIT 0,?",
                array(intval($limit)));
        } else {
            $memberlist = $this->db->execute("SELECT `id`, `username`
                FROM `players` ORDER BY `registered` ASC
                LIMIT ?,?",
                array(intval($begin), intval($limit)));
        }
        
        while($member = $memberlist->fetchrow()) {

            if ($this->player->rank == "Admin") {
                $admin = $this->skin->admin_link($member['id']);
            }

            $members_list .= $this->skin->member_row($member, $admin);
        }

        $members_list = $this->skin->members_list($begin, $next, $previous, $limit, $members_list);

        return $members_list;

    }

}
?>
