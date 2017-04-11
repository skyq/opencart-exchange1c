<?php
// HTTP
define('HTTP_SERVER',   'http://' . $_SERVER['HTTP_HOST'] . '/export/');
define('HTTP_CATALOG', 'http://' . $_SERVER['HTTP_HOST']);

// HTTPS
define('HTTPS_SERVER',  'https://' . $_SERVER['HTTP_HOST'] . '/export/');
define('HTTPS_CATALOG', 'http://' . $_SERVER['HTTP_HOST']);

// DIR
define('DIR_MODIFICATION',  dirname(__FILE__) . '/subdomains/oc23/httpdocs/system/storage/modification/');
define('DIR_APPLICATION',   dirname(__FILE__) . '/subdomains/oc23/httpdocs/admin/');
define('DIR_LANGUAGE',      dirname(__FILE__) . '/subdomains/oc23/httpdocs/export/language/');
define('DIR_TEMPLATE',      dirname(__FILE__) . '/subdomains/oc23/httpdocs/export/view/template/');
define('DIR_DOWNLOAD',      dirname(__FILE__) . '/subdomains/oc23/httpdocs/system/storage/download/');
define('DIR_SYSTEM',        dirname(__FILE__) . '/subdomains/oc23/httpdocs/system/');
define('DIR_CATALOG',       dirname(__FILE__) . '/subdomains/oc23/httpdocs/catalog/');
define('DIR_CONFIG',        dirname(__FILE__) . '/subdomains/oc23/httpdocs/system/config/');
define('DIR_UPLOAD',        dirname(__FILE__) . '/subdomains/oc23/httpdocs/system/storage/upload/');
define('DIR_IMAGE',         dirname(__FILE__) . '/subdomains/oc23/httpdocs/image/');
define('DIR_CACHE',         dirname(__FILE__) . '/subdomains/oc23/httpdocs/system/storage/cache/');
define('DIR_LOGS',          dirname(__FILE__) . '/subdomains/oc23/httpdocs/system/storage/logs/');

// DB заполняем из admin/config.php
define('DB_HOSTNAME',   '');
define('DB_USERNAME',   '');
define('DB_PASSWORD',   '');
define('DB_DATABASE',   '');
define('DB_DRIVER',     '');
define('DB_PREFIX',     '');
define('DB_PORT',       '');
