<?php
$userAgent = $_SERVER['HTTP_USER_AGENT'];

$browserPattern = '/(firefox|msie|trident|chrome|safari|edge|opera)[\/\s](\d+\.\d+)/i';
preg_match($browserPattern, $userAgent, $browserMatch);

$osPattern = '/(windows|macintosh|linux|unix|android|iphone|ipad|ios)/i';
preg_match($osPattern, $userAgent, $osMatch);

if (!empty($browserMatch)) {
    echo "Браузер: " . ucfirst($browserMatch[1]) . " версии: " . $browserMatch[2] . "<br>";
} else {
    echo "Браузер не распознан.<br>";
}

if (!empty($osMatch)) {
    echo "Операционная система: " . ucfirst($osMatch[1]) . "<br>";
} else {
    echo "Операционная система не распознана.<br>";
}
?>
