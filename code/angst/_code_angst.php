<?php
/**
 * Description of _code_angst
 *
 * @author josh04
 * @package code_angst
 */
class _code_angst extends code_common {

   /**
    * makes "2th nov" into "2 hours ago!"
    * (TODO) this is a code_skin candidate
    *
    * @param int $time the time, datestamp
    * @return string the time
    */
    public function format_time($time) {
        $delta = time() - $time;

        if ($delta == 1) {
          return $this->lang->a_second_ago;
        }

        if ($delta < 1 * 60) {
          return $delta.$this->lang->seconds_ago;
        }

        if ($delta < 2 * 60)  {
          return $this->lang->a_minute_ago;
        }

        if ($delta < 45 * 60) {
          return intval($delta/60).$this->lang->minutes_ago;
        }

        if ($delta < 90 * 60) {
          return $this->lang->a_hour_ago;
        }

        if ($delta < 24 * 3600) {
          return intval($delta/3600).$this->lang->hours_ago;
        }

        if ($delta < 48 * 3600) {
          return $this->lang->yesterday;
        }

        if ($delta < 30 * 86400) {
          return intval($delta/86400).$this->lang->days_ago;
        }

        if ($delta > 30 * 86400 && $delta < 60 * 86400) {
            return $this->lang->a_month_ago;
        }

        return date("jS M, h:i A", $time);

    }

}
?>
