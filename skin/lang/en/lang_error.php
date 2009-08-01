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
    public $no_password = "You did not enter a password.";
    public $passwords_do_not_match = "The entered passwords do not match.";
    public $access_denied = "You do not have permission to view this page";

    // db
    public $failed_to_connect = "Failed to connect to database.";
    public $no_database_username = "You did not enter a username.";
    public $no_database_name = "You did not enter a database name.";
    public $no_database_server = "You did not enter a database server.";
    public $database_server_error = "The database could not be contacted.";
    public $database_create_error = "There was an error creating a database table. Ensure your database user has the permissions to create tables.";
    public $upgrade_db_no_object = "Your installation of ezrpg is not suitable to be upgraded.";
    public $db_query_failed = "There was a problem accessing the database";

    // battle
    public $player_not_found = "Player not found.";
    public $no_player_selected = "No player selected.";
    public $cannot_attack_self = "You cannot attack yourself.";
    public $player_no_energy = "You do not have enough energy to attack";
    public $player_currently_incapacitated = "You are currently incapacitated.";
    public $enemy_currently_incapacitated = "Your opponent is currently incapacitated.";
    public $you_won = "You won!";
    public $you_lost = "You lost!";
    public $you_drew = "You drew.";
    public $levelled_up = "You gained a level!";
    public $battle_drawn = "Battle drawn.";
    public $enemy_malformed = "Enemy not properly formed.";
    public $no_players_found = "No players matched your query.";

    // login
    public $password_wrong = "The password entered is incorrect.";
    public $password_updated = "Password successfully updated.";
    public $profile_updated = "Profile sucessfully updated.";
    public $logged_out = "You have been logged out.";
    public $please_enter_password = "Please enter your password.";
    public $please_enter_username = "Please enter a username.";

    // register
    public $username_not_long_enough = "Your username must be longer than 3 characters.";
    public $username_banned_characters = "Your username may contain only alphanumerical characters.";
    public $username_needed = "You need to enter your username.";
    public $username_conflict = "That username has already been used.";
    public $password_needed = "You need to enter your password.";
    public $password_not_long_enough = "Your password must be longer than 3 characters.";
    public $email_needed = "You need to enter your email address.";
    public $no_email = "You did not enter an email address.";
    public $emails_do_not_match = "The entered emails do not match.";
    public $email_not_long_enough = "Your email must be longer than 3 characters.";
    public $email_wrong_format = "You did not enter a valid email address.";
    public $email_conflict = "That email has already been used. Please create only one account, creating more than one account will cause all your accounts to be deleted.";
    public $registered = "You have registered. Please Login to continue.";
    public $error_registering = "Error registering new player. ";
    public $avatar_wrong_format = "You did not ever a valid url for your avatar.";

    // ticketing
    public $ticket_not_exist = "The ticket you tried to access does not exist.";
    public $no_tickets = "No tickets have been issued";
    public $ticket_status_updated = "Ticket status updated";

    // blueprints
    public $no_blueprints = "There are currently no blueprints.";
    public $no_blueprint_selected = "You did not select a blueprint.";
    public $no_blueprint_name = "You did not enter a name for your blueprint.";
    public $no_blueprint_description = "You did not enter a description for your blueprint.";
    public $blueprint_removed = "The selected blueprint has been removed.";
    public $blueprint_remove_failed = "No blueprint was selected to delete.";
    public $blueprint_added = "Blueprint added.";
    public $blueprint_modify = "Blueprint modified.";
    public $add = "Add";
    public $modify = "Modify";
    public $remove = "Remove";

    // mail
    public $messages_deleted = "Messages deleted.";
    public $mail_sent = "Mail sent.";
    public $fill_in_fields = "You must fill in all fields.";

    // items
    public $item_not_found = "The selected item does not exist.";
    public $item_equipped = "Item Equipped.";
    public $item_unequipped = "Item Unequipped";
    public $no_items = "You do not have any items in your inventory.";
    public $equip = "Equip";
    public $unequip = "Unequip";
    public $no_items_in_store = "There are currently no items in the store.";
    public $no_item_selected = "No item selected";
    public $cannot_afford = "You cannot afford that item.";

    // stats
    public $no_stat_points = "You do not currently have any stat points to spend.";
    public $not_a_stat = "That is not a stat.";

    // menu
    public $no_menu_entry_selected = "You did not select a menu entry.";
    public $no_label = "You did not enter a label.";
    public $no_section = "You did not enter a section.";
    public $no_page = "You did not enter a page.";
    public $menu_entry_added = "Menu entry added.";
    public $menu_entry_modified = "Menu entry modified.";
    public $menu_reordered = "Menu reordered.";
    public $no_menu_entries = "Warning: There are currently no menu entries.";
    public $menu_not_found = "The menu entry requested couldn't be found.";
    public $menu_deleted = "The menu entry was successfully deleted.";
    public $menu_delete_no = "The menu entry could not be deleted.";
    public $menu_moved = "The menu entry has been moved.";
    public $menu_move_no = "The menu entry could not be moved.";

    // quests
    public $no_quests = "There are currently no quests installed.";
    public $quest_not_found = "The quest you were looking for does not exist.";
    public $confirm_delete_quest = "Are you sure you want to delete this quest?";
    public $quest_install_warning = "The developers of Quest are not responsible for the quests you install. Check quests carefully before installing them.";
    public $quest_install_success = "The quest was successfully installed.";
    public $quest_install_no = "The quest could not be installed.";
    public $quest_delete_success = "The quest was successfully deleted.";
    public $quest_delete_no = "The quest could not be deleted.";

    // admin
    public $changes_saved = "Changes saved.";
    public $changes_saved_no = "Changes could not be saved.";
    public $setting_update_yes = "The setting has been updated.";
    public $setting_update_no = "The setting could not be updated.";
    public $admin_home = "Welcome to the administration panel of Quest. You can select an area to administrate below.";
    public $admin_portal_home = "The portal allows players to easily travel between areas of the game. You can configure it below.";
    public $admin_link_syntax = "Put each entry on a new line, add an asterisk (*) at the start for bold, link to pages with pipe syntax (bank|Bank),
        force new lines with a space ( ) and specify boxes with hypens (--)";

    // bank
    public $collect_interest_now = "You may collect your interest now.";
    public $collect_interest_tomorrow = "You can collect your interest again tomorrow.";
    public $not_enough_cash_to_deposit = "You do not have that much cash to deposit.";
    public $not_enough_cash_to_withdraw = "You do not have that much cash to withdraw.";

    // hospital
    public $full_health = "You are already at full health.";
    public $beyond_maximum = "That is beyond your maximum health.";
    public $not_enough_to_heal = "You don't have enough money to heal that much.";

    // work
    public $no_energy_to_work = "You have no energy left. You must rest a while before working.";

    // laptop
    public $no_laptop_message = "You currently do not have any laptop messages.";

    // friends
    public $cant_self_friend = "You can be friends with yourself.";
    public $already_friends = "You are already friends with ";
    public $now_friends = "You are now friends with ";
    public $request_sent = "You have sent a friend request to ";
    public $awaiting_friend_response = "You have already sent a friend request.";
}
?>
