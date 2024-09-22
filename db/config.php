<?php
/*if($_SERVER['HTTP_X_FORWARDED_PROTO']=='http')
{
   header('Location:https://www.eclickprojects.com'.$_SERVER['REQUEST_URI']);
   exit();
}*/
substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') ? ob_start("ob_gzhandler") : ob_start();
error_reporting(0);
//ini_set('display_errors',1); error_reporting(E_ALL);

//-------------------------------------------------------------------
$basename = (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ? 'https://' : 'http://';
define('BASE',                  $basename . $_SERVER['HTTP_HOST']);            // For Live Server BASE       = "http://www."
define('DOMAIN',                "/skeleton-modified");                         // For Live Server DOMAIN     = "domainname.com"
define('ROOT_PATH',             $_SERVER['DOCUMENT_ROOT'] . DOMAIN);         // For Live server ROOT_PATH  = $_SERVER['DOCUMENT_ROOT'];
define('SITE_LOC_PATH',         BASE . DOMAIN);
define('SITE_DASHBOARD_PATH',   SITE_LOC_PATH . '/dashboard');
define("DEFAULT_SESSION_NAME",  'ECLICK');
//-------------------------------------------------------------------

/*db connecting variables-------------------------------------------*/
define("DB_HOST",               "localhost");
define("DB_USER",               "root");
define("DB_PASS",               "");
define("DB_NAME",               "eclick_skeleton_");
/*-------------------------------------------------------------------*/

define("SITE_PRIVATE_KEY",      '0v2woElxKRqyv174SIOUd6AlEehVSl2AfVyYYoGd');
define("SITE_PUBLIC_KEY",       'yqaKrWfWA1jsWzPGcPiycur251ingLuN');

include(ROOT_PATH . "/library/core.php");


// Uncomment this to have KEYs
/* $salt = new Security;
echo '<pre>SITE_PRIVATE_KEY -> ' . $salt->genRandString(40) . '<br>';
echo 'SITE_PUBLIC_KEY -> ' . $salt->genRandString(32) . '</pre>';
exit(); */
