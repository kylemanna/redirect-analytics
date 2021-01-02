<?php
require '/composer/vendor/autoload.php';

use TheIconic\Tracking\GoogleAnalytics\Analytics;

function getFullUrl() {
    $proto = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

    return "${proto}://${_SERVER['HTTP_HOST']}${_SERVER['REQUEST_URI']}";
}

$url = getFullUrl();

$id = getenv('UA_TRACKING_ID');
if (empty($id)) {
    throw new Exception('UA_TRACKING_ID is unset.');
}

$dest = getenv('DEST_LOCATION');
if (empty($dest)) {
    throw new Exception('DEST_LOCATION is unset.');
}

// Instantiate the Analytics object
// optionally pass TRUE in the constructor if you want to connect using HTTPS
$analytics = new Analytics(true);

// Build the GA hit using the Analytics class methods
// they should Autocomplete if you use a PHP IDE
$analytics
    ->setProtocolVersion('1')
    ->setTrackingId($id)
    ->setClientId(md5($_SERVER['REMOTE_ADDR']))
    ->setDocumentLocationUrl($url)
    ->setDocumentTitle($_SERVER['HTTP_HOST'])
    ->setIpOverride($_SERVER['REMOTE_ADDR'])
    ->setCampaignName('catchall')
    ->setCampaignMedium('redirect')
    ->setCampaignSource($_SERVER['HTTP_HOST'])
    ->setCampaignContent($url);

if (isset($_SERVER['HTTP_REFERER'])) {
    $analytics->setDocumentReferrer($_SERVER['HTTP_REFERER']);
}

// When you finish bulding the payload send a hit (such as an pageview or event)
$analytics->sendPageview();

// GG
header("Location: ${dest}");

// Habit
die();

?>