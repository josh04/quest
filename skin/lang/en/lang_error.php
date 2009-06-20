<?php
/**
 * error codes
 *
 * @author josh04
 * @package skin_lang
 */
class lang_error {

    // common
    public $page_not_exist = "The page you have tried to access does not exist.";

    // db
    public $failed_to_connect = "Failed to connect to database.";
    public $no_database_username = "You did not enter a username.";
    public $no_database_name = "You did not enter a database name.";
    public $no_database_password = "You did not enter a password.";
    public $passwords_do_not_match = "The entered passwords do not match.";
    public $no_database_server = "You did not enter a database server.";
    public $database_server_error = "The database could not be contacted.";
    public $database_create_error = "There was an error creating a database table. Ensure your database user has the permissions to create tables.";
    public $upgrade_db_no_object = "Your installation of ezrpg is not suitable to be upgraded.";

    // battle
    public $player_not_found = "Player not found.";
    public $no_player_selected = "No player selected.";
    public $no_players_found = "No players found.";
    public $cannot_attack_self = "You cannot attack yourself.";
    public $player_no_energy = "You do not have enough energy to attack";
    public $player_currently_incapacitated = "You are currently incapacitated.";
    public $enemy_currently_incapacitated = "Your opponent is currently incapacitated.";

    // login
    public $password_wrong = "The password entered is incorrect.";
    public $password_updated = "Password successfully updated";
    public $profile_updated = "Profile successfully updated";
}
?>
