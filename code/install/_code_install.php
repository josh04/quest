<?php
/**
 * let's make magic happen
 *
 * @author josh04
 * @package code_install
 */
class _code_install extends code_common {
    public $blueprints_query = "CREATE TABLE IF NOT EXISTS `blueprints` (
        `id` int(11) NOT NULL auto_increment,
        `name` varchar(255) NOT NULL default '',
        `description` text NOT NULL,
        `type` tinyint(1) NOT NULL default '0',
        `effectiveness` int(11) NOT NULL default '0',
        `price` int(11) NOT NULL default '0',
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $help_query = "CREATE TABLE IF NOT EXISTS `help` (
        `id` int(3) NOT NULL auto_increment,
        `parent` int(3) NOT NULL,
        `title` varchar(65) NOT NULL default '',
        `body` longtext NOT NULL,
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $items_query = "CREATE TABLE IF NOT EXISTS `items` (
        `id` int(11) NOT NULL auto_increment,
        `player_id` int(11) NOT NULL default '0',
        `item_id` int(11) NOT NULL default '0',
        `status` tinyint(1) NOT NULL default '0',
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $mail_query = "CREATE TABLE IF NOT EXISTS `mail` (
        `id` int(11) NOT NULL auto_increment,
        `to` int(11) NOT NULL default '0',
        `from` int(11) NOT NULL default '0',
        `subject` varchar(255) NOT NULL default '',
        `body` text NOT NULL,
        `time` int(11) NOT NULL default '0',
        `status` tinyint(1) NOT NULL default '0',
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $news_query = "CREATE TABLE IF NOT EXISTS `news` (
        `id` int(11) NOT NULL auto_increment,
        `message` text NOT NULL,
        `player_id` int(11) NOT NULL default '0',
        `date` int(10) NOT NULL default '0',
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $players_query = "CREATE TABLE IF NOT EXISTS `players` (
        `id` int(11) NOT NULL auto_increment,
        `username` varchar(255) NOT NULL default '',
        `password` text NOT NULL,
        `email` varchar(255) NOT NULL default '',
        `rank` varchar(255) NOT NULL default 'Member',
        `registered` int(11) NOT NULL default '0',
        `last_active` int(11) NOT NULL default '0',
        `ip` varchar(255) NOT NULL default '',
        `gold` int(11) NOT NULL default '200',
        `show_email` tinyint(3) NOT NULL default '0',
        `skin` int(3) NOT NULL default '2',
        `login_rand` varchar(255) NOT NULL default '',
        `login_salt` varchar(255) NOT NULL default '',
        PRIMARY KEY  (`id`),
        KEY `rank` (`rank`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $skins_query = "CREATE TABLE IF NOT EXISTS `skins` (
        `id` int(3) NOT NULL auto_increment,
        `name` varchar(65) NOT NULL default '',
        `cssfile` varchar(65) NOT NULL default '',
        `image` varchar(65) NOT NULL default '',
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $tickets_query = "CREATE TABLE IF NOT EXISTS `tickets` (
        `id` int(11) NOT NULL auto_increment,
        `player_id` int(11) NOT NULL default '0',
        `message` text NOT NULL,
        `date` int(10) NOT NULL default '0',
        `status` tinyint(3) NOT NULL default '0',
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $log_query = "CREATE TABLE IF NOT EXISTS `user_log` (
        `id` int(11) NOT NULL auto_increment,
        `player_id` int(11) NOT NULL default '0',
        `message` text NOT NULL,
        `status` tinyint(1) NOT NULL default '0',
        `time` int(11) NOT NULL default '0',
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $cron_query = "CREATE TABLE IF NOT EXISTS `cron` (
        `id` int(11) NOT NULL auto_increment,
        `function` varchar(255) NOT NULL default '',
        `last_active` int(11) NOT NULL default '0',
        `period` int(11) NOT NULL default '0',
        `enabled` tinyint(1) NOT NULL default '0',
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8; ";

    public $cron_insert_query = "INSERT INTO `cron` (`id`, `function`, `last_active`, `period`, `enabled`) VALUES
        (1, 'reset_energy', 0, 7200, 1),
        (2, 'recover_health', 0, 7200, 1),
        (3, 'interest', 0, 86400, 1);";

    public $help_insert_query = "INSERT INTO `help` (`id`, `parent`, `title`, `body`) VALUES
          (1, 0, 'Help Home', 'Welcome to the Quest Help Section. You can use the links below to find specific help files about what you want to know. Please make good use of this feature. If your questions are not answered by the help section, please create a ticket and the administrators will get around to helping you as quickly as possible.'),
          (2, 1, 'General Game Queries', 'This section deals with general queries about the game. Ergo, \"stuff which don''t fit in elsewhere good but still kinda useful, y''know?\"'),
          (3, 1, 'FAQs', '<i>Somewhat incredibly</i> we don''t have any FAQs at the moment. None. Because nobody''s asked a single question. As soon as we start getting them, however, we''ll put them up here for y''all to see.'),
          (4, 1, 'What is..?', 'Confused about a certain part of the game? This section explains some key concepts in Quest.'),
          (5, 1, 'Contact Us', 'To get in contact with staff, you''re best off using the mail system to send them a message. You can see the staff members <a href=\"index.php?page=members&amp;action=staff\">here</a>.'),
          (6, 1, 'How do I..?', 'If you want to know how to do something in the game, this is the right section to come to. Use the links in this section to specify your question.'),
          (7, 2, 'Dealing with Errors and Bugs', 'Found a problem? Oh dear. Well this section is designed specifically so that you know what to do. It''s got some basic troubleshooting for common errors as well, just in case what you''re experiencing ain''t that unusual...'),
          (8, 2, 'Walkthrough', 'This section is designed for new users really. It contains a brief guide on how to play the game. Just a nice way to get you started so you know what you''re doing.'),
          (9, 4, 'What is the point of this game?', 'This game is a browser based game, and your role is as a CHERUB agent living on Campus. You can buy weapons and armour from the Equipment Store, store them in the inventory, and use them in the Combat Training Zone against your friends. There will be a ranking table so you can try and beat your friends. If you lose a match, you can go to the Hospital Wing, and you can cash your tokens in at the Token Bank.'),
          (10, 4, 'What is the laptop?', 'The laptop displays all your fight logs, showing who has fought you, and what the outcome was, including any of the final penalties.'),
          (11, 4, 'What is the inventory?', 'This section displays all of your current weapons and armour. Once an item has been bought, it will appear here. To equip yourself with it, you must first unequip your previous weapon and then equip yourself with your new one.'),
          (12, 4, 'What is the bank?', 'This is basically your campus piggy bank. All the tokens you win will appear here, as will everything you earn from working. You can deposit all the tokens from your pocket into the bank, and you can withdraw tokens from the bank to your pocket. You can also claim daily interest from the bank, which will appear on your pocket. You can also donate tokens to your friends by going on their profile and selecting how much you wish to donate to them.'),
          (13, 4, 'What is the hospital?', 'This is where you should go after losing a fight. You can be healed, and you can select how much, by paying in tokens.'),
          (14, 4, 'What is the combat training?', 'One of the most important parts of the game, this is the (newly refurbished) Campus training area in the Dojo. You can call up anyone on campus to fight, and battle them. The winner is determined by who has the better level, and also the best weapons and armour.'),
          (15, 4, 'What is the store?', 'In the campus store, there is a selection of what you can purchase, assuming you have enough tokens. To purchase it just click on the link labelled ''buy'' on the right hand side of the item under the price.'),
          (16, 4, 'What is the work?', 'If you are in need of urgent tokens, have no fear! Simply go to work in the canteen! It will deduct an energy point off you, and the amount of tokens you will receive depends on your level.'),
          (17, 4, 'What is the speed of light?', 'About 299792458 m/s, but this really isn''t the right place to be learning Physics.'),
          (18, 6, 'How do I change my email and password?', 'Don''t create a new account! I know desperate times call for desperate measures, but it''s really unnecessary. If you click on ''Edit Profile'' on the left menu, you can change it, as well as various other bits of information about you. Remember that if you forget your password, you will need a <strong>valid</strong> e-mail address to retrieve it. Also, we might want to send important and interesting admin/game information to you, and you don''t want to miss out!'),
          (19, 6, 'How do I get new weapons?', 'From the left \"Game Menu\", go to \"Campus\". Then click onto \"store\" in the Main Building. There will be a selection of what you can purchase, assuming you have enough tokens. To purchase an item, just click on the link labelled ''buy'' on the right hand side of it (under the price).'),
          (20, 6, 'How do I see who''s fought me?', 'You can do this quite easily. If you go to the \"Campus\" on the right hand menu and follow through to your laptop, you''ll find a list of emails stating who''s fought you, added you as a friend and suchlike.'),
          (21, 6, 'How do I move up a level?', 'This is based on your current EXP, or Experience. To make your EXP larger (and thus to level up), you need to win fights with other agents. Also, the higher your level, the more tokens you will be able to earn through doing work on campus.'),
          (22, 6, 'How do I become a staff member?', 'Unfortunately, we do not need any more staff at the present time. We have all the coders, designers, database administrators and public relations staff that we need right now. However, any future vacancies will be advertised on the forum, the news and put in the newsletters sent to your email address.'),
          (23, 6, 'How do I get a friends list?', 'Simply go on the member list and select the person you want to be friends with. On their profile, click ''Invite to be friend''.'),
          (24, 6, 'How do I get my health back?', 'You can regain health in the hospital (Click on \"Campus\" on the left and follow through) or by waiting. Every night at midnight all the agents'' healths are restored. So, even if you have no money, you can live to fight again!');";

    public $pages_query = "CREATE TABLE IF NOT EXISTS `pages` (
          `id` int(11) NOT NULL auto_increment,
          `name` varchar(255) NOT NULL,
          `section` varchar(255) NOT NULL default 'public',
          `redirect` varchar(255) NOT NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $pages_insert_query = "
        INSERT INTO `pages` (`id`, `name`, `section`, `redirect`) VALUES
        (1, 'login', 'public', 'login'),
        (2, 'stats', 'public', 'stats'),
        (3, 'log', 'public', 'laptop'),
        (4, 'items', 'public', 'inventory,store'),
        (5, 'bank', 'public', 'bank'),
        (6, 'hospital', 'public', 'hospital'),
        (7, 'work', 'public', 'work'),
        (8, 'help', 'public', 'help,guesthelp'),
        (9, 'edit_profile', 'public', 'profile_edit'),
        (10, 'battle', 'public', 'battle'),
        (11, 'profile', 'public', 'profile'),
        (12, 'ticket', 'public', 'ticket'),
        (13, 'staff', 'public', 'staff'),
        (14, 'mail', 'public', 'mail'),
        (15, 'portal', 'public', 'portal'),
        (16, 'ranks', 'public', 'ranks'),
        (17, 'index', 'public', 'index'),
        (18, 'members', 'public', 'members'),
        (19, 'ticket', 'admin', 'ticket'),
        (20, 'blueprints', 'admin', 'blueprints'),
        (21, 'quest', 'public', 'quest'),
        (22, 'quest', 'admin', 'quest'),
        (23, 'index_admin', 'admin', 'index'),
        (24, 'admin_template', 'admin', 'panel,portal'),
        (25, 'menu_admin', 'admin', 'menu'),
        (26, 'edit_profile', 'admin', 'profile_edit'),
        (26, 'cron_admin', 'admin', 'cron') ;";

    public $settings_query = "CREATE TABLE IF NOT EXISTS `settings` (
        `name` varchar(125) NOT NULL,
        `value` longtext NOT NULL,
        PRIMARY KEY  (`name`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

    public $settings_insert_query = "INSERT INTO `settings` (`name`, `value`) VALUES
        ('name', 'Quest'),
        ('welcometext', '<p>Welcome to Quest. This is a default welcome message. To add your own, go to the Administration panel and click on \"Messages\".</p>'),
        ('portal_name', 'Campus'),
        ('portal_welcome', '[b]Security Guard:[/b]\n\n[i]Welcome onto Campus, agent. Let me just check your ID.\n\n...\n\nYou have permission to enter, agent. If you follow the path to the left, you can get to the Main Building. To the right you\'ll find the training areas and accommodation. Have a good day.[/i]'),
        ('portal_links', '* Main Building\r\nhospital|Hospital\r\nbank|Bank\r\nstore|Store\r\nwork|Work\r\n--\r\n* Your Room\r\nlaptop|Laptop\r\ninventory|Inventory\r\n\r\n* Dojo\r\nbattle|Combat training'),
        ('quests_code', ''),
        ('admin_notes', ''),
        ('verification_method', '1'),
        ('ban_multiple_email', '1'),
        ('custom_fields', '{\"description\":\"No description.\",\"gender\":\"0\",\"msn\":\" \",\"aim\":\" \",\"skype\":\" \",\"avatar\":\"images\/avatar.png\"}'),
        ('register_ip_check', '1'),
        ('database_report_error', '0');";

    public $quests_query = "CREATE TABLE IF NOT EXISTS `quests` (
        `id` int(3) NOT NULL auto_increment,
        `title` varchar(125) NOT NULL,
        `description` longtext NOT NULL,
        `author` varchar(125) NOT NULL,
        PRIMARY KEY  (`id`),
        UNIQUE KEY `title` (`title`,`author`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

    public $db_query = "ALTER DATABASE COLLATE utf8_general_ci; ALTER TABLE `blueprint_items` RENAME `blueprints`;";

    public $update_tables_query = "

ALTER TABLE `blueprint_items` ADD COLUMN `type2` varchar(255) NOT NULL;
ALTER TABLE `items` ADD COLUMN `status2` tinyint(1) NOT NULL;
ALTER TABLE `mail` ADD COLUMN `status2` tinyint(1) NOT NULL;
ALTER TABLE `user_log` ADD COLUMN `status2` tinyint(1) NOT NULL;
ALTER TABLE `players` ADD COLUMN `show_email` tinyint(3) NOT NULL default '0';
ALTER TABLE `players` ADD COLUMN `password2` text NOT NULL;


ALTER TABLE `players` ADD COLUMN `avatar` varchar(255) NOT NULL default 'images/avatar.png';
ALTER TABLE `players` ADD COLUMN `skin` int(3) NOT NULL default '2';
ALTER TABLE `players` ADD COLUMN `gender` tinyint(1) NOT NULL default '0';
ALTER TABLE `players` ADD COLUMN `msn` varchar(65) NOT NULL default '';
ALTER TABLE `players` ADD COLUMN `aim` varchar(65) NOT NULL default '';
ALTER TABLE `players` ADD COLUMN `skype` varchar(65) NOT NULL default '';
ALTER TABLE `players` ADD COLUMN `login_rand` varchar(255) NOT NULL default '';
ALTER TABLE `players` ADD COLUMN `login_salt` varchar(255) NOT NULL default '';
ALTER TABLE `players` ADD COLUMN `description` text NOT NULL default '';
UPDATE `blueprint_items` set `type2`='weapon' WHERE `type`='weapon';
UPDATE `blueprint_items` set `type2`='armour' WHERE `type`='armour';
UPDATE `items` set `status2`='1' WHERE `status`='equipped';
UPDATE `items` set `status2`='0' WHERE `status`='uneqipped';
UPDATE `mail` set `status2`='1' WHERE `status`='unread';
UPDATE `mail` set `status2`='0' WHERE `status`='read';
UPDATE `user_log` SET `status2`='1' WHERE `status`='read';
UPDATE `user_log` SET `status2`='0' WHERE `status`='unread';
UPDATE `players` SET `password2`=`password`;

                    
ALTER TABLE `blueprint_items` DROP COLUMN `type`;
ALTER TABLE `items` DROP COLUMN `status`;
ALTER TABLE `mail` DROP COLUMN `status`;
ALTER TABLE `user_log` DROP COLUMN `status`;
ALTER TABLE `players` DROP COLUMN `password`;


ALTER TABLE `blueprint_items` CHANGE `type2` `type` varchar(255) NOT NULL;
ALTER TABLE `items` CHANGE `status2` `status` tinyint(1) NOT NULL;
ALTER TABLE `mail` CHANGE `status2` `status` tinyint(1) NOT NULL;
ALTER TABLE `user_log` CHANGE `status2` `status` tinyint(1) NOT NULL;
ALTER TABLE `user_log` CHANGE `msg` `message` text NOT NULL;
                    ALTER TABLE `players` CHANGE `password2` `password` text NOT NULL;
                    ALTER TABLE `players` CHANGE `maxexp` `exp_max` int(11) NOT NULL default '50';
                    ALTER TABLE `players` CHANGE `maxhp` `hp_max` int(11) NOT NULL default '60';
                    ALTER TABLE `players` CHANGE `maxenergy` `energy_max` int(11) NOT NULL default '10';

ALTER TABLE `blueprint_items` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `items` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `mail` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `user_log` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `players` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `blueprint_items` RENAME `blueprints`;
ALTER DATABASE COLLATE utf8_general_ci; 
       ";

    public $menu_query = "CREATE TABLE IF NOT EXISTS `menu` (
        `id` int(11) NOT NULL auto_increment,
        `label` varchar(255) NOT NULL,
        `category` varchar(255) NOT NULL,
        `section` varchar(255) NOT NULL,
        `page` varchar(255) NOT NULL,
        `extra` text NOT NULL,
        `enabled` tinyint(1) NOT NULL,
        `order` int(11) NOT NULL,
        `function` tinyint(1) NOT NULL default '0',
        `guest` tinyint(1) NOT NULL default '0',
        PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
    
    public $menu_insert_query = "
INSERT INTO `menu` (`id`, `label`, `category`, `section`, `page`, `extra`, `enabled`, `order`, `function`, `guest`) VALUES
(1, 'Home', 'Game Menu', 'public', 'index', '', 1, 1, 1, 0),
(2, 'Mail', 'Game Menu', 'public', 'mail', '', 1, 3, 1, 0),
(3, 'Portal', 'Game Menu', 'public', 'portal', '', 1, 4, 1, 0),
(4, 'Player Stats', 'Game Menu', 'public', 'ranks', '', 1, 5, 0, 0),
(5, 'Member List', 'Game Menu', 'public', 'members', '', 1, 6, 0, 0),
(6, 'Edit Profile', 'Game Menu', 'public', 'profile_edit', '', 1, 7, 0, 0),
(7, 'Ticket Control', 'Admin', 'admin', 'ticket', '', 1, 6, 0, 0),
(8, 'Item Blueprints', 'Admin', 'admin', 'blueprints', '', 1, 5, 0, 0),
(9, 'Help', 'Other', 'public', 'help', '', 1, 4, 0, 0),
(10, 'Support Tickets', 'Other', 'public', 'ticket', '', 1, 5, 0, 0),
(11, 'Log Out', 'Other', 'public', 'login', '', 1, 6, 0, 0),
(12, 'Staff List', 'Other', 'public', 'members', '&amp;action=staff', 1, 3, 0, 0),
(13, 'Menu Editor', 'Admin', 'admin', 'menu', '', 1, 4, 0, 0),
(14, 'Quest Control', 'Admin', 'admin', 'quest', '', 1, 3, 0, 0),
(15, 'Control Panel', 'Admin', 'admin', 'index', '', 1, 2, 0, 0),
(16, 'Home', 'Guests', 'public', 'index', '', 1, 4, 0, 1),
(17, 'Register', 'Guests', 'public', 'login', '&amp;action=register', 1, 5, 0, 1),
(18, 'Player Stats', 'Guests', 'public', 'ranks', '', 1, 6, 1, 1),
(19, 'Help', 'Guests', 'public', 'guesthelp', '', 1, 7, 0, 1),
(23, 'Quests', 'Game Menu', 'public', 'quest', '', 1, 2, 0, 0),
(24, 'Cron Editor', 'Admin', 'admin', 'cron', '', 1, 7, 0, 0);
        ";

    public $friends_query = "CREATE TABLE IF NOT EXISTS `friends` (
        `id1` int(11) NOT NULL,
        `id2` int(11) NOT NULL,
        `accepted` tinyint(1) NOT NULL default '0',
        UNIQUE KEY `id1` (`id1`,`id2`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

    public $bank_query = "CREATE TABLE IF NOT EXISTS `bank` (
  `player_id` int(11) NOT NULL,
  `bank_gold_saved` int(11) NOT NULL,
  `bank_gold_deposited` int(11) NOT NULL,
  `interest_owed` double NOT NULL,
  `last_deposit` int(11) NOT NULL,
  PRIMARY KEY  (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

    public $profile_query = "
CREATE TABLE IF NOT EXISTS `profiles` (
  `player_id` int(11) NOT NULL,
  `profile_string` longtext NOT NULL,
  PRIMARY KEY  (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

    public $rpg_query = "
CREATE TABLE IF NOT EXISTS `rpg` (
  `player_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `stat_points` int(11) NOT NULL,
  `quest` text NOT NULL,
  `hp` int(11) NOT NULL,
  `hp_max` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `exp_max` int(11) NOT NULL,
  `energy` int(11) NOT NULL,
  `energy_max` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `vitality` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  PRIMARY KEY  (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    
   /**
    * List of commands to run here, to keep them in the same place as the variables.
    *
    * @return QuestDB_rs
    */
    public function create_database() {
        return $this->db->execute($this->cron_query.$this->blueprints_query.$this->help_query.$this->items_query.
            $this->mail_query.$this->news_query.$this->players_query.$this->skins_query.$this->tickets_query.$this->log_query.$this->help_insert_query.
            $this->cron_insert_query.$this->pages_query.$this->pages_insert_query.$this->settings_query.$this->settings_insert_query.
            $this->menu_query.$this->menu_insert_query.$this->quests_query.$this->friends_query.$this->bank_query.$this->profile_query.
            $this->rpg_query);
    }

   /**
    * ditto
    * (TODO) update the upgrade. lol.
    */
    public function upgrade_database() {
        $this->db->execute($this->cron_query);
        $this->db->execute($this->help_query);
        $this->db->execute($this->news_query);
        $this->db->execute($this->skins_query);
        $this->db->execute($this->tickets_query);
        $this->db->execute($this->cron_insert_query);
        $this->db->execute($this->pages_query);
        $this->db->execute($this->pages_insert_query);
        $this->db->execute($this->settings_query);
        $this->db->execute($this->settings_insert_query);
        $this->db->execute($this->menu_query);
        $this->db->execute($this->menu_insert_query);
        $this->db->execute($this->help_insert_query);
        $this->db->execute($this->quests_query);
        $this->db->execute($this->friends_query);
        $this->db->execute($this->bank_query);
        $this->db->execute($this->profile_query);
        $this->db->execute($this->rpg_query);

        $this->db->execute($this->update_tables_query);
    }

   /**
    * pieces the site together. intended to be overriden by child class to generate $page.
    *
    * @param string $page contains the html intended to go between the menu and the bottom.
    */
    public function construct($page) {

        $output = $this->start_header();
        $output .= $page;
        $output .= $this->skin->footer();

        print $output;

    }

   /**
    * sets up db, player, etc. can't go in construct because that happens after the child class has run.
    *
    * @param string $skin_name name of skin file to load - if left blank, loads skin_common (not recommended)
    */
    public function initiate($skin_name = "") {
        $this->make_skin($skin_name);
    }

   /**
    * section name and page name - db clipped
    *
    * @param string $section name of the site chunk we're in
    * @param string $page name of our particular page
    * @param string $config config values
    */
    public function __construct($section = "", $page = "") {
        $this->section = $section;
        $this->page = $page;
    }
}
?>
