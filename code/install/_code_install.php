<?php
/**
 * let's make magic happen
 *
 * @author josh04
 * @package code_install
 */
class _code_install extends code_common {

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

    public $players_query = "CREATE TABLE IF NOT EXISTS `players` (
        `id` int(11) NOT NULL auto_increment,
        `username` varchar(255) NOT NULL default '',
        `password` text NOT NULL,
        `email` varchar(255) NOT NULL default '',
        `rank` varchar(255) NOT NULL default 'Member',
        `registered` int(11) NOT NULL default '0',
        `last_active` int(11) NOT NULL default '0',
        `ip` varchar(255) NOT NULL default '',
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
        (9, 'edit_profile', 'public', 'profile_edit'),
        (11, 'profile', 'public', 'profile'),
        (12, 'ticket', 'public', 'ticket'),
        (14, 'mail', 'public', 'mail'),
        (17, 'index', 'public', 'index'),
        (18, 'members', 'public', 'members'),
        (19, 'ticket', 'admin', 'ticket'),
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
        ('quests_code', ''),
        ('admin_notes', ''),
        ('verification_method', '1'),
        ('ban_multiple_email', '1'),
        ('custom_fields', '{\"description\":\"No description.\",\"gender\":\"0\",\"msn\":\" \",\"aim\":\" \",\"skype\":\" \",\"avatar\":\"images/avatar.png\",\"angst_count\":\"0\",\"glee_count\":\"0\"}'),
        ('register_ip_check', '1'),
        ('database_report_error', '0');";

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
(4, 'Player Stats', 'Game Menu', 'public', 'ranks', '', 1, 5, 0, 0),
(5, 'Member List', 'Game Menu', 'public', 'members', '', 1, 6, 0, 0),
(6, 'Edit Profile', 'Game Menu', 'public', 'profile_edit', '', 1, 7, 0, 0),
(7, 'Ticket Control', 'Admin', 'admin', 'ticket', '', 1, 6, 0, 0),
(9, 'Help', 'Other', 'public', 'help', '', 1, 4, 0, 0),
(10, 'Support Tickets', 'Other', 'public', 'ticket', '', 1, 5, 0, 0),
(11, 'Log Out', 'Other', 'public', 'login', '', 1, 6, 0, 0),
(13, 'Menu Editor', 'Admin', 'admin', 'menu', '', 1, 4, 0, 0),
(15, 'Control Panel', 'Admin', 'admin', 'index', '', 1, 2, 0, 0),
(16, 'Home', 'Guests', 'public', 'index', '', 1, 4, 0, 1),
(17, 'Register', 'Guests', 'public', 'login', '&amp;action=register', 1, 5, 0, 1),
(19, 'Help', 'Guests', 'public', 'guesthelp', '', 1, 7, 0, 1),
(24, 'Cron Editor', 'Admin', 'admin', 'cron', '', 1, 7, 0, 0),
(25, 'Mod Loader', 'Admin', 'admin', 'pages', '', 1, 8, 0, 0);
        ";

    public $friends_query = "CREATE TABLE IF NOT EXISTS `friends` (
        `id1` int(11) NOT NULL,
        `id2` int(11) NOT NULL,
        `accepted` tinyint(1) NOT NULL default '0',
        UNIQUE KEY `id1` (`id1`,`id2`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

    public $profile_query = "
CREATE TABLE IF NOT EXISTS `profiles` (
  `player_id` int(11) NOT NULL,
  `profile_string` longtext NOT NULL,
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
        return $this->db->execute($this->cron_query.$this->help_query.
            $this->mail_query.$this->players_query.$this->skins_query.$this->tickets_query.$this->help_insert_query.
            $this->cron_insert_query.$this->pages_query.$this->pages_insert_query.$this->settings_query.$this->settings_insert_query.
            $this->menu_query.$this->menu_insert_query.$this->friends_query.$this->profile_query.
            $this->lang_query);
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
        $this->make_default_lang();
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
