<?php
/**
 * bbcode engine.
 *
 * @author Grego
 * @package code_common
 */
class core_bbcode {

   /**
    *
    * @param code_common $common page that's calling
    * @return core_bbcode
    */
    public function load_core($common) {
        return $this;
    }


    /**
     * parses bbcode to return html
     * (TODO) preg_replace is sloooooow
     *
     * @param string $code bbcode
     * @param boolean $full whether to fully parse
     * @return string html
     */
    public function parse($code, $full=false) {
        $code = htmlentities($code, ENT_QUOTES, 'utf-8', false); //things should be htmlentitised _before_ they go into the DB - you shouldn't do it twice
        if ($full) {
            $code = nl2br($code);
        }

        preg_match_all("/\[code\](.*?)\[\/code\]/is", $code, $matches, PREG_SET_ORDER);
        foreach($matches as $match) {
            $match[1] = str_replace("[", "&#91;", $match[1]);
            $code = str_replace($match[0], "<pre>" . $match[1] . "</pre>", $code);
        }

        $match = array (
                "/\[b\](.*?)\[\/b\]/is",
                "/\[i\](.*?)\[\/i\]/is",
                "/\[u\](.*?)\[\/u\]/is",
                "/\[s\](.*?)\[\/s\]/is",
                "/\[url\](.*?)\[\/url\]/is",
                "/\[url=(.*?)\](.*?)\[\/url\]/is",
                "/\[img\](.*?)\[\/img\]/is",
                "/\[colo(u)?r=([a-z]*?|#?[A-Fa-f0-9]*){3,6}\](.*?)\[\/colo(u)?r\]/is",
                );
        $replace = array (
                "<strong>$1</strong>",
                "<em>$1</em>",
                "<ins>$1</ins>",
                "<del>$1</del>",
                "<a href='$1' target='_blank'>$1</a>",
                "<a href='$1' target='_blank'>$2</a>",
                "<img src='$1' alt='[image]' />",
                "<span style='color:$2;'>$3</span>",
                );
        $code = preg_replace($match, $replace, $code);
        while ($this->quote($code)) {
            continue; // a fake loop that drills through
        }
        return $code;
    }

    /**
     * parses quotes in bbcode (looped for multiple quotes)
     *
     * @param string $code bbcode
     * @param boolean $full whether to fully parse
     * @return int how many done
     */
    public function quote(&$code) {
        $match = array (
                "/\[quote=(.+?)\](.*?)\[\/quote\]/is",
                "/\[quote=?\](.*?)\[\/quote\]/is",
                );
        $replace = array (
                "<div class='quote-head'>Quote ($1)</div><div class='quote'>$2</div>",
                "<div class='quote-head'>Quote</div><div class='quote'>$1</div>",
                );
        $code = preg_replace($match, $replace, $code, -1, $count);
        return $count;
    }

}
?>