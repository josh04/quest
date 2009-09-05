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
        `verified` tinyint(1) NOT NULL default 0,
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
          (1, 0, 'Help Home', '', 0),
          (2, 1, 'About Quest', '<p>Quest is soon to be a free open-source PHP persistent browser-based game. Breaking down all those long words:</p>\r\n<ul>\r\n<li>Quest is <strong>free</strong>. You can download it for free to use yourself. We also strongly encourage game masters to let players join their games for free.</li>\r\n<li>Quest is <strong>open-source</strong>. You can look at the code behind it, and fiddle with it to meet your needs. If you find a better way to do something, we love it when you tell us!</li>\r\n<li>Quest is written in <strong>PHP</strong>, a free scripting language. You can find out more about PHP <a href="http://www.php.net/">here</a>.</li>\r\n<li>Quest is <strong>persistent</strong>. When you log out, the gameplay continues and you can carry on playing when you get back.</li>\r\n<li>Quest is <strong>browser-based</strong>. You can play it through any sort of web browser, such as Firefox, Opera, Safari or Internet Explorer.</li>\r\n<li>Quest is a <strong>game</strong>, first and foremost. It''s designed for people to enjoy playing. If you have any ideas to make it better, tell us!</li>\r\n</ul>', 0),
          (3, 1, 'Need help?', '<p>If you need help, there are various options available to you.</p>\r\n\r\n<p>Firstly, check if there''s anything that can help in these help pages. The solution to your problem could be here, and it will be a lot quicker to check here than try another option.</p>\r\n\r\n<p>If you can''t find a solution in the help pages, you can create a <a href=''index.php?page=ticket''>Support Ticket</a>, which the staff will read and act upon.</p>\r\n\r\n<p>If your problem is critical, or requires a personal touch, you can contact <a href=''index.php?page=members&act=staff''>our staff</a> directly through the mail system. However, there is no guarantee that they''ll respond to you.</p>', 3),
          (4, 1, 'Walk-through', '<p>Here''s a quick walk-through of the various elements of Quest. If you want to know what something is, this guide should provide you with a quick background.</p>\r\n\r\n<h4>Mail</h4>\r\n<p>Quest''s internal mail system lets you send messages to other players. These messages are only accessible through the main Quest site and support BBcode.</p>\r\n\r\n<h4>Portal</h4>\r\n<p>The Portal provides you with access to all the important parts of Quest. Through it, you can quickly navigate your way through the game.</p>\r\n\r\n<h4>Quests</h4>\r\n<p>Quest''s Quests allow you to take part in an adventure, gaining experience as you go, without needing to directly do anything. Following a specific storyline, Quests lead you on all sorts of adventures.</p>\r\n\r\n<h4>Log</h4>\r\n<p>Your log tells keeps track of all your battles and experiences. Make sure you check it regularly to see what''s been happening to your character.</p>', 1),
          (5, 1, 'How do I regain health?', '<p>One of the most common questions we''re asked is how to regain health. Well, your first choice is to check out <a href=''index.php?page=hospital''>the hospital</a>, where you can pay for medical assistance. Otherwise, all players health is reset on a regular basis. In other words, just leave it for a bit!</p>', 2);";

    public $pages_query = "CREATE TABLE IF NOT EXISTS `pages` (
          `id` int(11) NOT NULL auto_increment,
          `name` varchar(255) NOT NULL,
          `section` varchar(255) NOT NULL default 'public',
          `redirect` varchar(255) NOT NULL,
          `mod` varchar(255) NOT NULL,
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
        (27, 'messages', 'admin', 'messages'),
        (29, 'cron_admin', 'admin', 'cron'),
        (30, 'pages', 'admin', 'pages') ;";

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
        ('database_report_error', '0');
        ('avatar_url', '1'),
        ('avatar_gravatar', '1'),
        ('avatar_upload', '0'),
        ('avatar_library', '0');";

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

ALTER TABLE `players` ADD COLUMN `skin` int(3) NOT NULL default '2';
ALTER TABLE `players` ADD COLUMN `login_rand` varchar(255) NOT NULL default '';
ALTER TABLE `players` ADD COLUMN `login_salt` varchar(255) NOT NULL default '';
ALTER TABLE `players` ADD COLUMN `verified` text NOT NULL default '';
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
(24, 'Cron Editor', 'Admin', 'admin', 'cron', '', 1, 7, 0, 0),
(25, 'Mod Loader', 'Admin', 'admin', 'pages', '', 1, 8, 0, 0);
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

   public $lang_query = "CREATE TABLE IF NOT EXISTS `lang` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `override` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

";
    
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
            $this->rpg_query.$this->lang_query);
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
        $this->db->execute($this->lang_query);
        $player_query = $this->db->execute("SELECT * FROM `players`");

        while ($player = $player_query->fetchrow()) {
            $player['player_id'] = $player['id'];
            $player['hp_max'] = $player['maxhp'];
            $player['energy_max'] = $player['maxenergy'];
            $player['exp_max'] = $player['maxexp'];
            $this->db->AutoExecute('profiles', $player, 'INSERT'); // so so cheap and dirty
            $this->db->AutoExecute('rpg', $player, 'INSERT');
        }
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
        $this->make_lang();
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
