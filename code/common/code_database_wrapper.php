<?php
/**
 * The database wrapper is a singleton class (I think!), a class with only one
 * possible instance shared wherever it is used. When you run get_db for the
 * first time (with a $config passed), it calls up ADODB (or whatever) and sets
 * up the database. Every time after that, it just returns the previous database
 * connection. This saves us from all that tedious =& $this->db stuff, you can
 * just put a $this->db = code_...() into __construct() and be done with it.
 * code_bootstrap connects to the db to get the page list, so you can count on
 * it being there for most of the code. If the db isn't set up, running this on
 * it's own will silently error out, and make_db() can be run at any point in
 * the future.
 *
 * @author josh04
 * @package code_common
 */
class code_database_wrapper {
   /**
    * Persisting class instance
    *
    * @var code_database_wrapper
    */
    private static $instance;

   /**
    * And the persisting database object which goes with it
    *
    * @var QuestDB
    */
    public $db;

   /**
    * constructs shiz.
    *
    * @param array $config database settings
    */
    private function __construct($config) {
	$this->db = &NewQuestDB();

        ob_start(); // Do not error if the database isn't there.
        $this->db->Connect($config['server'], $config['db_username'], $config['db_password'], $config['database']);

        $this->db->_output_buffer = ob_get_contents();
        ob_end_clean();
        
    }

   /**
    * gets the current database object
    *
    * @param array $config database settings
    * @return QuestDB database
    */
    public static function &get_db($config = array()) {
        if (!self::$instance) {
            self::$instance = new code_database_wrapper($config);
        }

        return self::$instance->db;
    }

}
?>
