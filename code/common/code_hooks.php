<?php
/**
 * Description of code_hooks
 *
 * @author grego
 */


define('HK_CONCAT', 0);
define('HK_ARRAY', 1);
define('HK_MAXIMUM', 2);
define('HK_MINIMUM', 3);


class code_hooks {

    public $hook_array = array();
    private $common;

   /**
    * core modules loading function
    *
    * @param code_common $common page calling
    * @return code_hooks this
    */
    public function load_core($common) {
        require('hooks/index.php');

        $this->hook_array = $hooks;

        $this->common =& $common; // CHEATING
        return $this;
    }



   /**
    * performs a hook action
    *
    * @param string $hook name of the hook to be executed
    * @param constant (HK_*) $method how to return the hooks
    * @return mixed whatever the hooks do
    */
    public function get($hook, $method = HK_CONCAT) {

        if(!isset($this->hook_array[$hook])) {
            return false;
        }

        // removed array_unique as it has 'issues' with multi-dimensional arrays
        foreach($this->hook_array[$hook] as $h) {
            $return[] = $this->run_hook($h);
        }

        switch ($method) {
            case HK_ARRAY:
                return $return;
                break;

            case HK_MAXIMUM:
                return max($return);
                break;

            case HK_MINIMUM:
                return min($return);
                break;

            case HK_CONCAT:
            default:
                return implode("", $return);
        }
    }

   /**
    * performs a single function for a hook
    *
    * @param string $hook an array containing details of the hook we're doing
    * @return mixed whatever the hook does
    */
    private function run_hook($hook) {

        $mod = $hook[0];
        $file = $hook[1];
        $function = $hook[2];

        if ($mod == "" || $file == "" || $function == "") {
            return false;
        }

        $include_path = "./hooks/".$mod."/".$file;

        if (!file_exists($include_path)) {
            return false;
        }
        
        // So we don't include anything that slipped outside the function into the page
        ob_start();
            @require_once($include_path);
        ob_end_clean();

        return $function(&$this->common);
    }
}
?>
