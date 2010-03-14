<?php
/**
 * members hook
 * 
 * fishes the friends out of the db
 *
 * @package code_hooks
 * @author josh04
 */

   /**
    * sets up the table join
    *
    * @return boolean success
    */
    function members_pre(&$common) {
        $common->join[] = 'rpg';
    }

   /**
    * sets up the table join
    *
    * @return boolean success
    */
    function members_row(&$common) {
        return $common->skin->table_row($common->member['level']);
    }

   /**
    * sets up the table join
    *
    * @return boolean success
    */
    function members_column(&$common) {
        return $common->skin->table_row('Level');
    }

?>