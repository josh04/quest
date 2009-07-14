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
        `title` varchar(65) NOT NULL default '',
        `body` longtext NOT NULL,
        `colour` varchar(8) NOT NULL default 'blue',
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
        `rank` varchar(255) NOT NULL default 'Agent',
        `registered` int(11) NOT NULL default '0',
        `last_active` int(11) NOT NULL default '0',
        `description` text NOT NULL,
        `ip` varchar(255) NOT NULL default '',
        `level` int(11) NOT NULL default '1',
        `stat_points` int(11) NOT NULL default '5',
        `gold` int(11) NOT NULL default '200',
        `bank` int(11) NOT NULL default '20',
        `hp` int(11) NOT NULL default '60',
        `hp_max` int(11) NOT NULL default '60',
        `exp` int(11) NOT NULL default '0',
        `exp_max` int(11) NOT NULL default '50',
        `energy` int(11) NOT NULL default '10',
        `energy_max` int(11) NOT NULL default '10',
        `strength` int(11) NOT NULL default '1',
        `vitality` int(11) NOT NULL default '1',
        `agility` int(11) NOT NULL default '1',
        `interest` tinyint(1) NOT NULL default '0',
        `kills` int(11) NOT NULL default '0',
        `deaths` int(11) NOT NULL default '0',
        `show_email` tinyint(3) NOT NULL default '0',
        `avatar` varchar(255) NOT NULL default 'images/avatar.png',
        `skin` int(3) NOT NULL default '2',
        `gender` tinyint(1) NOT NULL default '0',
        `msn` varchar(65) NOT NULL default '',
        `aim` varchar(65) NOT NULL default '',
        `skype` varchar(65) NOT NULL default '',
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

    public $help_insert_query = "
        INSERT INTO `help` (`id`, `title`, `body`, `colour`) VALUES
        (1, 'Help Home', 'Welcome to the Quest Help Section. You can use the links above to find specific help files about what you want to know. Please make good use of this feature. If your questions are not answered by the help section, please create a ticket and the administrators will get around to helping you as quickly as possible.', 'blue'),
        (2, 'General Game Queries', 'This section deals with general queries about the game. Ergo, \"stuff which don''t fit in elsewhere good but still kinda useful, y''know?\"', 'blue'),
        (3, 'FAQs', '<i>Somewhat incredibly</i> we don''t have any FAQs at the moment. None. Because nobody''s asked a single question. As soon as we start getting them, however, we''ll put them up here for y''all to see =)', 'blue'),
        (4, 'What is..?', '\r\n<ul style=\"list-style-image: url(images/icons/lightbulb.png);\">\r\n<li><a href=\"#d1\" onClick=\"faq_highlight(''1'',''8'',''blue'')\">What is the laptop?</a></li>\r\n<li><a href=\"#d2\" onClick=\"faq_highlight(''2'',''8'',''blue'')\">What is the inventory?</a></li>\r\n<li><a href=\"#d3\" onClick=\"faq_highlight(''3'',''8'',''blue'')\">What is the bank?</a></li>\r\n<li><a href=\"#d4\" onClick=\"faq_highlight(''4'',''8'',''blue'')\">What is the hospital?</a></li>\r\n<li><a href=\"#d5\" onClick=\"faq_highlight(''5'',''8'',''blue'')\">What is combat training?</a></li>\r\n<li><a href=\"#d6\" onClick=\"faq_highlight(''6'',''8'',''blue'')\">What is the store?</a></li>\r\n<li><a href=\"#d7\" onClick=\"faq_highlight(''7'',''8'',''blue'')\">What is work?</a></li>\r\n<li><a href=\"#d8\" onClick=\"faq_highlight(''8'',''8'',''blue'')\">What is the speed of light?</a></li>\r\n</ul>\r\n\r\n<div class=\"bblue\" id=\"m1\"><a class=\"faq\" name=\"d1\">What is the laptop?</a><br />\r\nThe laptop displays all your fight logs, showing who has fought you, and what the outcome was, including any of the final penalties.</div>\r\n\r\n<div class=\"bblue\" id=\"m2\"><a class=\"faq\" name=\"d2\">What is the inventory?</a><br />\r\nThis section displays all of your current weapons and armour. Once an item has been bought, it will appear here. To equip yourself with it, you must first unequip your previous weapon and then equip yourself with your new one.</div>\r\n\r\n<div class=\"bblue\" id=\"m3\"><a class=\"faq\" name=\"d3\">What is the bank?</a><br />\r\nThis is basically your campus piggy bank. All the tokens you win will appear here, as will everything you earn from working. You can deposit all the tokens from your pocket into the bank, and you can withdraw tokens from the bank to your pocket. You can also claim daily interest from the bank, which will appear on your pocket. You can also donate tokens to your friends by going on their profile and selecting how much you wish to donate to them.</div>\r\n\r\n<div class=\"bblue\" id=\"m4\"><a class=\"faq\" name=\"d4\">What is the hospital?</a><br />\r\nThis is where you should go after losing a fight. You can be healed fullyor you can select how much you want to be healed by by paying in tokens.</div>\r\n\r\n<div class=\"bblue\" id=\"m5\"><a class=\"faq\" name=\"d5\">What is combat training ?</a><br />\r\nOne of the most important parts of the game, this is the (newly refurbished) Campus training area in the Dojo. You can call up anyone on campus to fight, and battle them. The winner is determined by who has the better level, and also the best weapons and armour.</div>\r\n\r\n<div class=\"bblue\" id=\"m6\"><a class=\"faq\" name=\"d6\">What is the store?</a><br />\r\nIn the campus store, there is a selection of what you can purchase, assuming you have enough tokens. To purchase it just click on the link labelled ''buy'' on the right hand side of the item under the price.</div>\r\n\r\n<div class=\"bblue\" id=\"m7\"><a class=\"faq\" name=\"d7\">What is work?</a><br />\r\nIf you are in need of urgent tokens, have no fear! Simply go to work in the canteen! It will deduct an energy point off you, and the amount of tokens you will receive depends on your level.</div>\r\n\r\n<div class=\"bblue\" id=\"m8\"><a class=\"faq\" name=\"d8\">What is the speed of light?</a><br />\r\nAbout 299792458 m/s, but this really isn''t the right place to be learning Physics.</div>', 'white'),
        (5, 'Contact Us', 'If you really can''t find a way around a problem, or you want to congratulate us on our hard work, or you want to rant at us for various reasons, there are a few possible ways:<br />\r\n<br />\r\n - You can contact us through our accounts in-game. If you log into your account and click the \"Our Staff\" link on the left-hand side navigation, you should find links to all of our profiles. Use the mail system to send us messages.<br />\r\n - You can email us personally. Currently the best person to email is Grego at ggt500@hotmail.com as he''s the only person doing any work. If this changes, I''ll update this help page.', 'blue'),
        (6, 'How do I..?', 'If you want to know how to do something in the game, this is the right section to come to. Use the links above to specify your question.', 'yellow'),
        (7, 'Dealing with Errors and Bugs', 'Found a problem? Oh dear. Well this section is designed specifically so that you know what to do. It''s got some basic troubleshooting for common errors as well, just in case what you''re experiencing ain''t that unusual...', 'red'),
        (8, 'Walkthrough', 'This section is designed for new users really. It contains a brief guide on how to play the game. Just a nice way to get you started so you know what you''re doing.', 'green'),
        (9, 'What is the point of this game?', 'This game is a browser based game, and your role is as a CHERUB agent living on Campus. You can buy weapons and armour from the Equipment Store, store them in the inventory, and use them in the Combat Training Zone against your friends. There will be a ranking table so you can try and beat your friends. If you lose a match, you can go to the Hospital Wing, and you can cash your tokens in at the Token Bank.', 'blue'),
        (10, 'What is the laptop?', 'The laptop displays all your fight logs, showing who has fought you, and what the outcome was, including any of the final penalties.', 'blue'),
        (11, 'What is the inventory?', 'This section displays all of your current weapons and armour. Once an item has been bought, it will appear here. To equip yourself with it, you must first unequip your previous weapon and then equip yourself with your new one.', 'blue'),
        (12, 'What is the bank?', 'This is basically your campus piggy bank. All the tokens you win will appear here, as will everything you earn from working. You can deposit all the tokens from your pocket into the bank, and you can withdraw tokens from the bank to your pocket. You can also claim daily interest from the bank, which will appear on your pocket. You can also donate tokens to your friends by going on their profile and selecting how much you wish to donate to them.', 'blue'),
        (13, 'What is the hospital?', 'This is where you should go after losing a fight. You can be healed, and you can select how much, by paying in tokens.', 'blue'),
        (14, 'What is the combat training?', 'One of the most important parts of the game, this is the (newly refurbished) Campus training area in the Dojo. You can call up anyone on campus to fight, and battle them. The winner is determined by who has the better level, and also the best weapons and armour.', 'blue'),
        (15, 'What is the store?', 'In the campus store, there is a selection of what you can purchase, assuming you have enough tokens. To purchase it just click on the link labelled ''buy'' on the right hand side of the item under the price.', 'blue'),
        (16, 'What is the work?', 'If you are in need of urgent tokens, have no fear! Simply go to work in the canteen! It will deduct an energy point off you, and the amount of tokens you will receive depends on your level.', 'blue'),
        (17, 'What is the speed of light?', 'About 299792458 m/s, but this really isn''t the right place to be learning Physics.', 'blue'),
        (18, 'How do I change my email and password?', 'Don''t create a new account! I know desperate times call for desperate measures, but it''s really unnecessary. If you click on ''Edit Profile'' on the left menu, you can change it, as well as various other bits of information about you. Remember that if you forget your password, you will need a <strong>valid</strong> e-mail address to retrieve it. Also, we might want to send important and interesting admin/game information to you, and you don''t want to miss out!', 'yellow'),
        (19, 'How do I get new weapons?', 'From the left \"Game Menu\", go to \"Campus\". Then click onto \"store\" in the Main Building. There will be a selection of what you can purchase, assuming you have enough tokens. To purchase an item, just click on the link labelled ''buy'' on the right hand side of it (under the price).', 'yellow'),
        (20, 'How do I see who''s fought me?', 'You can do this quite easily. If you go to the \"Campus\" on the right hand menu and follow through to your laptop, you''ll find a list of emails stating who''s fought you, added you as a friend and suchlike.', 'yellow'),
        (21, 'How do I move up a level?', 'This is based on your current EXP, or Experience. To make your EXP larger (and thus to level up), you need to win fights with other agents. Also, the higher your level, the more tokens you will be able to earn through doing work on campus.', 'yellow'),
        (22, 'How do I become a staff member?', 'Unfortunately, we do not need any more staff at the present time. We have all the coders, designers, database administrators and public relations staff that we need right now. However, any future vacancies will be advertised on the forum, the news and put in the newsletters sent to your email address.', 'yellow'),
        (23, 'How do I get a friends list?', 'Simply go on the member list and select the person you want to be friends with. On their profile, click ''Invite to be friend''.', 'yellow'),
        (24, 'How do I get my health back?', 'You can regain health in the hospital (Click on \"Campus\" on the left and follow through) or by waiting. Every night at midnight all the agents'' healths are restored. So, even if you have no money, you can live to fight again!', 'yellow');
        ";

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
        (4, 'inventory', 'public', 'inventory'),
        (5, 'bank', 'public', 'bank'),
        (6, 'hospital', 'public', 'hospital'),
        (7, 'work', 'public', 'work'),
        (8, 'help', 'public', 'help'),
        (9, 'edit_profile', 'public', 'profile_edit'),
        (10, 'battle', 'public', 'battle'),
        (11, 'profile', 'public', 'profile'),
        (12, 'ticket', 'public', 'ticket'),
        (13, 'staff', 'public', 'staff'),
        (14, 'store', 'public', 'store'),
        (15, 'mail', 'public', 'mail'),
        (16, 'campus', 'public', 'campus'),
        (17, 'guesthelp', 'public', 'guesthelp'),
        (18, 'ranks', 'public', 'ranks'),
        (19, 'index', 'public', 'index'),
        (20, 'members', 'public', 'members'),
        (21, 'ticket', 'admin', 'ticket');";

    public $settings_query = "CREATE TABLE IF NOT EXISTS `settings` (
        `name` varchar(125) NOT NULL,
        `value` longtext NOT NULL,
        PRIMARY KEY  (`name`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

    public $settings_insert_query = "INSERT INTO `settings` (`name`, `value`) VALUES
        ('name', 'Quest'),
        ('welcometext', '<p>Welcome to Quest. This is a default welcome message. To add your own, go to the Administration panel and click on \"Messages\".</p>');";

    public $db_query = "ALTER DATABASE COLLATE utf8_general_ci; ";

    public $blueprints_query_update = "RENAME TABLE blueprint_items TO blueprints;
                        ALTER TABLE `blueprints` ADD COLUMN `type2` varchar(255) NOT NULL;
                        UPDATE `blueprints` set `type2`='weapon' WHERE `type`='weapon';
                        UPDATE `blueprints` set `type2`='armour' WHERE `type`='armour';
                        ALTER TABLE `blueprints` DROP COLUMN `type`;
                        ALTER TABLE `blueprints` CHANGE `type2` `type` varchar(255) NOT NULL;
                        ALTER TABLE `blueprints` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ";

    public $items_query_update = "ALTER TABLE `items` ADD COLUMN `status2` tinyint(1) NOT NULL;
                    UPDATE `items` set `status2`='1' WHERE `status`='equipped';
                    UPDATE `items` set `status2`='0' WHERE `status`='uneqipped';
                    ALTER TABLE `items` DROP COLUMN `status`;
                    ALTER TABLE `items` CHANGE `status2` `status` tinyint(1) NOT NULL;
                    ALTER TABLE `items` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ";

    public $mail_query_update =  "ALTER TABLE `mail` ADD COLUMN `status2` tinyint(1) NOT NULL;
                    UPDATE `mail` set `status2`='1' WHERE `status`='equipped';
                    UPDATE `mail` set `status2`='0' WHERE `status`='uneqipped';
                    ALTER TABLE `mail` DROP COLUMN `status`;
                    ALTER TABLE `mail` CHANGE `status2` `status` tinyint(1) NOT NULL;
                    ALTER TABLE `mail` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ";

    public $players_query_update = "ALTER TABLE `players` ADD COLUMN `show_email` tinyint(3) NOT NULL default '0',
                    ADD COLUMN `avatar` varchar(255) NOT NULL default 'images/avatar.png',
                    ADD COLUMN `skin` int(3) NOT NULL default '2',
                    ADD COLUMN `gender` tinyint(1) NOT NULL default '0',
                    ADD COLUMN `msn` varchar(65) NOT NULL default '',
                    ADD COLUMN `aim` varchar(65) NOT NULL default '',
                    ADD COLUMN `skype` varchar(65) NOT NULL default '',
                    ADD COLUMN `login_rand` varchar(255) NOT NULL default '',
                    ADD COLUMN `login_salt` varchar(255) NOT NULL default '',
                    ADD COLUMN `description` text NOT NULL default '';

                    ALTER TABLE `players` ADD COLUMN `password2` text NOT NULL;
                    UPDATE `players` SET `password2`=`password`;
                    ALTER TABLE `players` DROP COLUMN `password`;
                    ALTER TABLE `players` CHANGE `password2` `password` text NOT NULL;
                    ALTER TABLE `players` CHANGE `maxexp` `exp_max` int(11) NOT NULL default '50';
                    ALTER TABLE `players` CHANGE `maxhp` `hp_max` int(11) NOT NULL default '60';
                    ALTER TABLE `players` CHANGE `maxenergy` `energy_max` int(11) NOT NULL default '10';
                    ALTER TABLE `players` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ";

    public $log_query_update =    "ALTER TABLE `user_log` ADD COLUMN `status2` tinyint(1) NOT NULL;
                    UPDATE `user_log` SET `status2`='1' WHERE `status`='read';
                    UPDATE `user_log` SET `status2`='0' WHERE `status`='unread';
                    ALTER TABLE `user_log` DROP COLUMN `status`;
                    ALTER TABLE `user_log` CHANGE `status2` `status` tinyint(1) NOT NULL;
                    ALTER TABLE `user_log` CHANGE `msg` `message` text NOT NULL;
                    ALTER TABLE `user_log` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ";

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
}
?>
