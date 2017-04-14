<?php
$rootDir  = ""; //тут прописываем путь аналогичный конфигам admin или каталог
$siteName = $_SERVER['HTTP_HOST'];

// HTTP
define('HTTP_SERVER',   'http://' . $siteName . '/export/');
define('HTTP_CATALOG', 'http://'  . $siteName);

// HTTPS
define('HTTPS_SERVER',  'https://' . $siteName . '/export/');
define('HTTPS_CATALOG', 'http://'  . $siteName);
// DIR
define('DIR_MODIFICATION',  $rootDir . '/system/storage/modification/');
define('DIR_APPLICATION',   $rootDir . '/admin/');
define('DIR_LANGUAGE',      $rootDir . '/export/language/');
define('DIR_TEMPLATE',      $rootDir . '/export/view/template/');
define('DIR_DOWNLOAD',      $rootDir . '/system/storage/download/');
define('DIR_SYSTEM',        $rootDir . '/system/');
define('DIR_CATALOG',       $rootDir . '/catalog/');
define('DIR_CONFIG',        $rootDir . '/system/config/');
define('DIR_UPLOAD',        $rootDir . '/system/storage/upload/');
define('DIR_IMAGE',         $rootDir . '/image/');
define('DIR_CACHE',         $rootDir . '/system/storage/cache/');
define('DIR_LOGS',          $rootDir . '/system/storage/logs/');

// DB заполняем из admin/config.php
define('DB_HOSTNAME',   '');
define('DB_USERNAME',   '');
define('DB_PASSWORD',   '');
define('DB_DATABASE',   '');
define('DB_DRIVER',     '');
define('DB_PREFIX',     '');
define('DB_PORT',       '');
