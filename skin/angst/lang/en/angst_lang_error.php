<?php
/**
 * error codes
 *
 * @author josh04
 * @package skin_lang
 */
class angst_lang_error {

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

    // login
    public $password_wrong = "The password entered is incorrect.";
    public $password_updated = "Password successfully updated.";
    public $profile_updated = "Profile sucessfully updated.";
    public $logged_out = "You have been logged out.";
    public $please_enter_password = "Please enter your password.";
    public $please_enter_username = "Please enter a username.";
    public $registration_disabled = "Registration is currently disabled.";

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
    public $multiple_registrations = "You may not register more than one account each 30 minutes.";
    public $player_already_approved = "This player has already been approved.";
    public $player_approved = "Player successfully approved.";

    // ticketing
    public $ticket_not_exist = "The ticket you tried to access does not exist.";
    public $no_tickets = "No tickets have been issued";
    public $ticket_status_updated = "Ticket status updated";
    public $ticket_send_too_soon = "You can only send one ticket every two minutes.";
    public $no_ticket_entered = "You did not enter a message.";
    public $ticket_submitted = "Your support ticket was submitted.";
    public $too_many_tickets = "You have too many open tickets. Please wait until some are resolved before submitting another.";

    // mail
    public $messages_deleted = "Messages deleted.";
    public $mail_sent = "Mail sent.";
    public $fill_in_fields = "You must fill in all fields.";
    public $mail_send_too_soon = "You can only send one mail every two minutes.";
    public $marked_as_read = "Messages marked as read";
    public $marked_as_unread = "Messages marked as unread";
    public $no_messages_selected = "You did not select any messages";
    public $error_getting_mail = "Error retrieving your mail.";
    public $no_mail = "You do not currently have any mail.";

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
    public $menu_move_down_no = "That menu entry cannot be moved down.";
    public $menu_move_up_no = "That menu entry cannot be moved up.";
    public $category_not_found = "That category cannot be moved as it does not exist.";
    public $category_move_down_no = "That menu entry cannot be moved down.";
    public $category_move_up_no = "That menu entry cannot be moved up.";

    // admin
    public $changes_saved = "Changes saved.";
    public $changes_saved_no = "Changes could not be saved.";
    public $setting_update_yes = "The setting has been updated.";
    public $setting_update_no = "The setting could not be updated.";
    public $admin_home = "Welcome to the administration panel of Quest. You can select an area to administrate below.";
    public $admin_portal_home = "The portal allows players to easily travel between areas of the game. You can configure it below.";
    public $admin_link_syntax = "Put each entry on a new line, add an asterisk (*) at the start for bold, link to pages with pipe syntax (bank|Bank),
        force new lines with a space ( ) and specify boxes with hypens (--)";
    public $couldnt_find_message = "The message you submitted could not be found.";
    public $message_saved = "Message saved.";

    // friends
    public $cant_self_friend = "You can't be friends with yourself.";
    public $already_friends = "You are already friends with ";
    public $now_friends = "You are now friends with ";
    public $request_sent = "You have sent a friend request to ";
    public $awaiting_friend_response = "You have already sent a friend request.";

    // edit_profile
    public $not_a_rank = "That is not a valid rank.";
    public $permissions_updated = "Permissions sucessfully updated.";

    // cron
    public $no_cron_selected = "You did not select a cron to edit.";
    public $cron_updated = "You have successfully updated the cron timer.";

    // help
    public $help_not_found = "The help topic could not be found.";

    // message overrides
    public $override_deleted = "The message has been restored to it's original content.";
    public $no_such_override = "Message id not found.";

    // pages
    public $no_page_selected = "You did not select a page.";
    public $mod_updated = "The mod for that page has been updated.";

    // angst
    public $angst_too_short = "All messages must be longer than three characters.";
    public $cannot_reply = "Guests cannot reply to angst.";
    public $reply_posted = "You posted a reply.";
    public $angst_reply_timer = "You cannot post more than once every minute.";
    public $twitter_not_verified = "Twitter verification failed.";
    public $twitter_succeeded = "Twitter verification succeeded.";
    public $twitter_already_verified = "You have already connected your account with this twitter username.";
    public $twitter_no_new = "No new tweets.";
    public $twitter_recounted = "Twitter posts recounted.";
    public $no_angst_id = "You did not enter an angst id.";
    public $player_not_found = "That user was not found.";
    public $angst_not_approved = "Approval failed.";
    public $angst_not_yet_approved = "Unfortunately, the angst you selected has not yet been approved by a moderator.";
}
?>
