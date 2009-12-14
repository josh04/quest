<?php

function frontpage_news($factory) {

$mode = $factory->settings->get['frontpage_news_mode'];
if(!in_array($mode, array("manual","rss","twitter") )) $mode = "manual";

$title = $factory->settings->get['frontpage_news_title'];
if(!$title) $title = "Latest news";

$twitter_user = $factory->settings->get['frontpage_news_twitter'];
if(!$twitter_user && $mode=="twitter") $mode = "manual";

$rss_url = $factory->settings->get['frontpage_news_rss'];
if(!$rss_url && $mode=="rss") $mode = "manual";

switch ($mode) {
    case "rss":
        $news = frontpage_news_rss($rss_url);
        break;

    case "twitter":
        $news = frontpage_news_twitter($twitter_user);
        break;

    case "manual":
    default:
        $news = frontpage_news_manual($factory->db);
        break;
}

return "<h4>".$title."</h4>" . $news;
}

// Retrieves a Twitter stream to display
function frontpage_news_twitter($user) {

    $url = "http://twitter.com/statuses/user_timeline.json?screen_name=".$user."&count=10";
    $stream = json_decode(file_get_contents($url));

    if(empty($stream)) return "Error retrieving stream from Twitter";

    $first = true;

    foreach($stream as $status) {
        if($first) {
            $status->text = "<span style='font-size:16px;'>".$status->text."</span>";
            $first = false;
        }
        $news .= "<p>".$status->text."
        <a href='http://twitter.com/".$status->user->screen_name."/status/".$status->id."' style='font-size:10px;color:#A00;text-decoration:none;'>
        <span>".date("h:i A; M jS",strtotime($status->created_at))."</span>
        </a></p>";
    }

    return $news;

}

// Retrieves an RSS feed and echos it
function frontpage_news_rss($url) {
    $obj = simplexml_load_file($url);

    // If an RSS feed:
    if(!empty($obj->channel)) {
        $description = "description";
        $pubDate = "pubDate";
        $collect = $obj->channel->item;
    // Else an Atom feed
    } else {
        $description = "content";
        $pubDate = "published";
        $collect = $obj->entry;
    }

    foreach($collect as $item) {
        $news .= "<div style='border:1px solid #CCC;margin:4px;padding:4px;-moz-border-radius-topright:12px;border-radius-topright:12px;'>
        <a href='".$item->link."'>".$item->title."</a><br />
        <span style='font-size:10px;'>".date("h:i A M jS",strtotime($item->$pubDate))."</span>
        <p>".$item->$description."</p></div>";
    }

    return $news;
}

// Pulls locally stored updates
function frontpage_news_manual(&$db) {
    $newsq = $db->Execute("SELECT * FROM `frontpage_news`");
    if($newsq->numrows()==0) return "No news to show.";

    while($item = $newsq->fetchrow()) {
        $news .= "<div style='border:1px solid #CCC;margin:4px;padding:4px;-moz-border-radius-topright:12px;border-radius-topright:12px;'>
        <a>".$item['title']."</a><br />
        <span style='font-size:10px;'>".date("h:i A M jS",$item['time'])."</span>
        <p>".$item['body']."</p></div>";
    }
    return $news;
}