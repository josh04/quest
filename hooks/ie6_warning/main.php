<?php

function warning() {

    // Compose the message
    $message = "<div class='success' style='font-size:10px;'>
    <strong>You should update your browser</strong>
    <img src='hooks/ie6_warning/ielogo.png' alt='hot' style='float:left;width:64px;height:64px;padding:24px 8px;' />

    <p>Records show that you're using Internet Explorer 6 to browse the Internet. This is an outdated browser that has
    a tendency to not render websites correctly and cause problems. We recommend that you upgrade your browser.</p>

    <a href='http://www.microsoft.com/windows/Internet-explorer/default.aspx'>Update Internet Explorer<a><br />
    <a href='http://www.mozilla-europe.org/en/firefox/'>Download Mozilla Firefox</a><br />
    <a href='http://www.opera.com/browser/'>Download Opera</a>

    <br style='clear:both;' />
    </div>";


    // Check if they're using IE6. If so, show the messages
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.') !== false) {
        return $message;
    } else {
        return false;
    }

}