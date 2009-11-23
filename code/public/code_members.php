<?php
/**
 * member list code
 *
 * @author josh04
 * @package code_public
 */
class code_members extends code_common {

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
            $member_list_query = $this->db->execute("SELECT `p`.`id`, `p`.`username`, `r`.`level`
                FROM `players` AS `p`
                LEFT JOIN `rpg` AS `r` ON `r`.`player_id`=`p`.`id`
                WHERE `p`.`rank`='Admin' ORDER BY `r`.`level` DESC
                LIMIT 0,?",
                array(intval($limit)));
        } else {
            $member_list_query = $this->db->execute("SELECT `p`.`id`, `p`.`username`, `r`.`level`
                FROM `players` AS `p`
                LEFT JOIN `rpg` AS `r` ON `r`.`player_id`=`p`.`id`
                ORDER BY `r`.`level` DESC
                LIMIT ?,?",
                array(intval($begin), intval($limit)));
        }

        while($member = $member_list_query->fetchrow()) {
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
