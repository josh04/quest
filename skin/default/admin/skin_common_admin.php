<?php
/**
 * Class skin overrides for the admin section. Namely, a tiny little padlock. Aww.
 *
 * @author josh04
 * @package skin_public
 */
class skin_common_admin extends skin_common {
    /**
     * glues the left side to the right. yeah.
     * @param string $page The haich tee emm ell.
     * @return string html
     */
    public function glue($page) {
        $page = "<img style='float:right;margin:8px;' src='images/icons/lock.png' alt='admin' title='Administrator page' />".$page;
        $glue .= parent::glue($page);
        return $glue;
    }
}
?>
