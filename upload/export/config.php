<?php
$rootDir  = dirname(__FILE__);
$siteName = $_SERVER['HTTP_HOST'];

// HTTP
define('HTTP_SERVER',   'http://' . $siteName . '/export/');
define('HTTP_CATALOG', 'http://'  . $siteName);

// HTTPS
define('HTTPS_SERVER',  'https://' . $siteName . '/export/');
define('HTTPS_CATALOG', 'http://'  . $siteName);
// DIR
define('DIR_MODIFICATION',  $rootDir . '/subdomains/oc23/httpdocs/system/storage/modification/');
define('DIR_APPLICATION',   $rootDir . '/subdomains/oc23/httpdocs/admin/');
define('DIR_LANGUAGE',      $rootDir . '/subdomains/oc23/httpdocs/export/language/');
define('DIR_TEMPLATE',      $rootDir . '/subdomains/oc23/httpdocs/export/view/template/');
define('DIR_DOWNLOAD',      $rootDir . '/subdomains/oc23/httpdocs/system/storage/download/');
define('DIR_SYSTEM',        $rootDir . '/subdomains/oc23/httpdocs/system/');
define('DIR_CATALOG',       $rootDir . '/subdomains/oc23/httpdocs/catalog/');
define('DIR_CONFIG',        $rootDir . '/subdomains/oc23/httpdocs/system/config/');
define('DIR_UPLOAD',        $rootDir . '/subdomains/oc23/httpdocs/system/storage/upload/');
define('DIR_IMAGE',         $rootDir . '/subdomains/oc23/httpdocs/image/');
define('DIR_CACHE',         $rootDir . '/subdomains/oc23/httpdocs/system/storage/cache/');
define('DIR_LOGS',          $rootDir . '/subdomains/oc23/httpdocs/system/storage/logs/');

// DB заполняем из admin/config.php
define('DB_HOSTNAME',   '');
define('DB_USERNAME',   '');
define('DB_PASSWORD',   '');
define('DB_DATABASE',   '');
define('DB_DRIVER',     '');
define('DB_PREFIX',     '');
define('DB_PORT',       '');
