<?php
/**
 * the core_player interface, for player module replacements.
 *
 * @author josh04
 */
interface basic_player {

   /*
    * constructor function. sets up the database and settings object
    *
    */
    public function __construct($settings = array(), $config = array());

   /*
    * initiation function
    * starts session, includes cookie code, clones hooks object
    * flies the flags for hooks
    */
    public function load_core($common);

   /*
    * makes the main player object, checks session is valid
    *
    */
    public function make_player();

   /*
    * given username or id, finds player.
    *
    */
    public function get_player($identity);

   /*
    * we've malformed the player, this should save our horror
    *
    */
    public function update_player();

}
?>
