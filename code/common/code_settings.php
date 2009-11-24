<?php
/**
 * core settings module
 *
 * @author josh04
 * @package code_common
 */
class code_settings {

    public $db;
    public $settings;
    public $get = array();

   /**
    * constructor, gives us the db
    *
    * @param array $config db values
    */
    public function __construct($settings = array(), $config = array()) {
        $this->db = code_database_wrapper::get_db($config);
    }

   /**
    * raids the db for some settings
    *
    * @param code_common $common the calling page
    * @return array
    */
    public function load_core($common) {
        $settings_query = $this->db->execute("SELECT * FROM `settings`");
        while ($setting = $settings_query->fetchrow()) {
            $settings[$setting['name']] = $setting['value'];
        }
        $this->get = $settings;
        return $this;
    }

    /**
     * Updates a specific setting.
     *
     * (TODO) code_settings?
     *
     * @param string setting name
     * @param string setting value
     * @return boolean success
     */
    public function set($name, $value) {
        // If no query needs to be executed, the update is complete!
        $setting_query = true;
        // If they're arrays, we're going cyclic
        if (is_array($name) && is_array($value)) {
            if (count($name)!=count($value)) {
                return false;
            }
            foreach ($name as $setting_index => $setting_name) {
                if ($this->get[$setting_name] == $value[$setting_index]) {
                    continue;
                }
                $setting_query = $this->db->execute("UPDATE `settings` SET `value`=? WHERE `name`=?",array($value[$setting_index],$setting_name));

                if (!$setting_query) {
                    return false;
                }
                $this->get[$setting_name] = $value[$setting_index];
            }
            return true;
        } else {
            $setting_query = $this->db->execute("UPDATE `settings` SET `value`=? WHERE `name`=?", array($value, $name));
            if ($setting_query)  {
                $this->get[$name] = $value;
                return $true;
            }
        }
        return false; // This will only occur if it screws up. I expect.
    }
}
?>
