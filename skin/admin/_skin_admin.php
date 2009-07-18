<?php
/**
 * Class skin overrides for the admin section. Namely, a tiny little padlock. Aww.
 *
 * @author josh04
 * @package skin_public
 */
class _skin_admin extends skin_common {
    /**
     * glues the left side to the right. yeah.
     * @return string html
     */
    public function glue($section) {
        $glue = parent::glue();
        $glue .= "<img style='float:right;margin:8px;' src='images/icons/lock.png' alt='admin' title='Admin page' />";
        return $glue;
    }
}
?>
