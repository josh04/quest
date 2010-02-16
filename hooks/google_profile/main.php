<?php

function google_profile($factory) {
    $interact = array(
        'url' => "http://www.google.com/search?q=" . $factory->profile->username,
        'caption' => 'Search for "'.$factory->profile->username.'" on Google',
    );
    return $interact;
}