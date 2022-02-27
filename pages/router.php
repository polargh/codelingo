<?php
require_once("../inc/global.inc.php");
$path = $_GET['path'];
if (!$path) {
    die("Error: Unspecified Path");
}
if ($path == "/") $path = "/index";

$parsedPath = "";
foreach(explode("/", $path) as $part) {
    $parsedPath .= strpos($part, ":") !== false ? str_replace(":", "[", $part)."]" : "/".$part; 
}

$_PARAMS = [];
if (isset($_GET['params'])) {
    $_PARAMS = json_decode($_GET['params'], true);
}

if (file_exists("files{$parsedPath}.php")) {
    require_once("files{$parsedPath}.php");
} else if (file_exists("files{$parsedPath}/index.php")) {
    require_once("files{$parsedPath}/index.php");
} else {
    //404
    http_response_code(404);
    require_once("./404.html");
}
?>