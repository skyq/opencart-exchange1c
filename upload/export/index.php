<?php
// Version
define('VERSION', '2.3.0.2.1');

// Configuration
if (is_file('config.php')) {
    require_once('config.php');
}


// Startup
require_once(DIR_SYSTEM . 'startup.php');

$registry   = new Registry();
$request    = new Request();
// Config
$config     = new Config();
$config->load('default');
$config->load('export');
$registry->set('config', $config);

// Event
$event      = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action_event')) {
    foreach ($config->get('action_event') as $key => $value) {
        $event->register($key, new Action($value));
    }
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Database
if ($config->get('db_autostart')) {
    $db = new DB($config->get('db_type'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port'));
    $registry->set('db', $db);
}

// Session
$session = new Session();

if ($config->get('session_autostart')) {
    $session->start();
}

$registry->set('session', $session);

// Cache
$registry->set('cache', new Cache($config->get('cache_type'), $config->get('cache_expire')));

// Url
if ($config->get('url_autostart')) {
    $registry->set('url', new Url($config->get('site_base'), $config->get('site_ssl')));
}

$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);
// Language
$language = new Language($config->get('language_default'));
$language->load($config->get('language_default'));
$registry->set('language', $language);

// Document
$registry->set('document', new Document());

// Config Autoload
if ($config->has('config_autoload')) {
    foreach ($config->get('config_autoload') as $value) {
        $loader->config($value);
    }
}

// Language Autoload
if ($config->has('language_autoload')) {
    foreach ($config->get('language_autoload') as $value) {
        $loader->language($value);
    }
}

// Library Autoload
if ($config->has('library_autoload')) {
    foreach ($config->get('library_autoload') as $value) {
        $loader->library($value);
    }
}

// Model Autoload
if ($config->has('model_autoload')) {
    foreach ($config->get('model_autoload') as $value) {
        $loader->model($value);
    }
}

// Front Controller
$controller = new Front($registry);

// Pre Actions
if ($config->has('action_pre_action')) {
    foreach ($config->get('action_pre_action') as $value) {
        $controller->addPreAction(new Action($value));
    }
}
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");

foreach ($query->rows as $setting) {
    if (!$setting['serialized']) {
        $config->set($setting['key'], $setting['value']);
    } else {
        $config->set($setting['key'], $setting['value']);
    }
}
// Router
// Language
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $db->escape($config->get('config_admin_language')) . "'");

if ($query->num_rows) {
    $config->set('config_language_id', $query->row['language_id']);
}

if (isset($request->get['mode']) && $request->get['type'] == 'catalog') {

    switch ($request->get['mode']) {
        case 'checkauth':
            $action = new Action('extension/module/exchange1c/modeCheckauth');
            break;

        case 'init':
            $action = new Action('extension/module/exchange1c/modeCatalogInit');
            break;

        case 'file':
            $action = new Action('extension/module/exchange1c/modeFile');
            break;

        case 'import':
            $action = new Action('extension/module/exchange1c/modeImport');
            break;

        default:
            echo "success\n";
    }

} else if (isset($request->get['mode']) && $request->get['type'] == 'sale') {

    switch ($request->get['mode']) {
        case 'checkauth':
            $action = new Action('module/exchange1c/modeCheckauth');
            break;

        case 'init':
            $action = new Action('module/exchange1c/modeSaleInit');
            break;

        case 'query':
            $action = new Action('module/exchange1c/modeQueryOrders');
            break;

        case 'success':
            $action = new Action('module/exchange1c/modeOrdersChangeStatus');
            break;

        default:
            echo "success\n";
    }

} else {
    echo "error\n";
    echo "no mode no type\n";
    exit;
}
// Dispatch
if (isset($action)) {
    $controller->dispatch($action, new Action($config->get('action_error')));
}

// Output
//$response->setCompression($config->get('config_compression'));
$response->output();

