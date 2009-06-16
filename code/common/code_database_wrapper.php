<?php
/**
 * Description of code_database_wrapper
 *
 * @author josh04
 * @package code_common
 */
class code_database_wrapper {
    private static $instance;
    public $db;

   /**
    * constructs shiz.
    *
    * @global int $ADODB_QUOTE_FIELDNAMES adodb setting
    * @param array $config database settings
    */
    private function __construct($config) {
        global $ADODB_QUOTE_FIELDNAMES;
        // Set up the Database
        $this->db = &ADONewConnection('mysqli'); //Get our database object.
        //$this->db->debug = true;
        ob_start(); // Do not error if the database isn't there.
        $status = $this->db->Connect(     $config['server'],
                                          $config['db_username'],
                                          $config['db_password'],
                                          $config['database']     );
        ob_end_clean();

        $ADODB_QUOTE_FIELDNAMES = 1;
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC); //Set to fetch associative arrays
    }

   /**
    * gets the current database object
    *
    * @param array $config database settings
    * @return object database
    */
    public static function &get_db($config) {
        if (!self::$instance) {
            self::$instance = new code_database_wrapper($config);
        }

        return self::$instance->db;
    }

}
?>
