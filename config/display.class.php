<?php
/**
 * display.php : Display class, for all output.
 * print'ing stuff.
 * Essentially, each specific page has it's own function here. the function
 * calls the appropriate template files and prints them. This helps us seperate 
 * code from html.
 * 
 * @package display
 */

class display {


    public $db;
    public $player;
    private $html = "";                   //This is the "body" of the page.
    public $pagetitle = "CherubQuest";   //Set the title with this.
    public $folder = "templates/";            //Template Extension.
    
  
    // Okay, I've cleaned up the model somewhat. Now, instead of display.php
    // being a billion lines long and containing all the functions ever, functions
    // have been moved into their individual .inc.php files, and are automatically
    // included by this magical piece of code. This should cut down on instances
    // where code gets overwritten when people upload old versions of display.php,
    // which can only be a good thing. Also removes the need for display_admin.php

    // Variables get passed through to the array $var, as in $a[0], $a[1] etc.
    // Not figured out a way yet to name them, save starting the include files
    // with $a[0] = $importantnumber etc.

    // This is some CLEVER SHIT.

    /**
    * this seemed clever shit when I first made it, now I want to redo it.
    *
    * @deprecated
    * @param string $function name of function
    * @param array $a variables
    */
    public function __call($function, $a) {
    if (file_exists($this->folder.$function.".inc.php")){
          require($this->folder.$function.".inc.php");
        } else {
          print "File not found: ".$function;
        }
    }


    /**
    * Makes a page with text in a little dinky box.
    *
    * @param string $content text to display
    * @param string $style format options
    * @param string $class format class
    */
    function simple_page($content, $style="", $class="") {
        $this->html = htmlentities($content,ENT_COMPAT,'UTF-8');
        if ($style || $class) {
          $this->html = "<div class='".$class."' style='".$style."'>".$this->html."</div>";
        }
        $this->html = nl2br($this->html);
        $this->final_output();
        exit;
    }

    /**
    * HTML override function.
    *
    * @deprecated
    * @param string $content text to display
    * @param string $style format options
    * @param string $class format class
    */
    function html_page($content) {
        $this->html = $content;
        $this->final_output();
        exit;
    }

    /**
    * This is a private function. ONLY functions in display.php can call it.
    * It straps the header and the footer to the html which should have been
    * put in $display->html ($this->html), and then prints the whole bunch.
    * @deprecated
    */
    private function final_output() {
        $header = $this->header();            //All pages have the header
        $footer = $this->footer();            //and the footer.
        print $header                         //Let 'er rip!
              .$this->html
              .$footer;
    }
  
}
?>
