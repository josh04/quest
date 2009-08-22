<?php
/**
 * Description of code_cron_admin
 *
 * @author josh04
 * @package code_public
 */
class code_cron_admin extends code_common {

   /**
    * class override. calls parents, sends kids home.
    *
    * @return string html
    */
    public function construct() {
        $this->initiate("skin_cron_admin");

        $code_cron_admin = $this->cron_switch();

        parent::construct($code_cron_admin);
    }
    
   /**
    * Decides what to do
    * 
    * @return string html
    */
    public function cron_switch() {
        $cron_switch = $this->cron_page();
        return $cron_switch;
    }
    
    public function cron_page() {
        $cron_query = $this->db->execute("SELECT * FROM `cron`");

        while ($cron = $cron_query->fetchrow()) {
            $cron['hours'] = intval($cron['period']/3600);
            $cron['minutes'] = intval(($cron['period'] % 3600)/60);
            $cron['seconds'] = intval($cron['period'] % 60);
            $cron['last_active'] = date("F j, Y", $cron['last_active']);
            if ($cron['enabled']) {
                $cron['enabled'] = "Yes";
            }
            $cron_html .= $this->skin->cron_row($cron);

        }
        
        $cron_page = $this->skin->cron_page_wrap($cron_html);
        return $cron_page;
    }

}
?>
