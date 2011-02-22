<?php
// Authentication
define('AUTH_USER', 'bbteam');
define('AUTH_PASS', 'gang0ftw0');

// Root Path
define('APP_DIR', dirname(__FILE__));
define('CLASSES_DIR', '/srv/www/blogbridge.com/fl');

// Sources
define('CLASSES_TEMPLATE', CLASSES_DIR . '/_template');
define('CLASSES_SITES_SUFFIX', '.blogbridge.com');

// Apache config
define('APACHE_VHOSTS_CONFIG', '/etc/httpd/vhosts/feedlibraries.conf');
define('APACHE_RESTART', 'sudo /usr/sbin/apachectl graceful');

// Smarty config
define('SMARTY', APP_DIR . '/smarty');
define('SMARTY_TEMPLATES', SMARTY . '/templates');
define('SMARTY_TEMPLATES_C', SMARTY . '/templates_c');
define('SMARTY_CONFIG', SMARTY . '/config');

// Database
define('DB_USER', 'pito');
define('DB_PASS', 'pitodb');
define('DB_HOST', 'localhost');
define('DB_NAME', 'bb_sm');
define('DB_TYPE', 'mysql://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . '/' . DB_NAME);
define('DB_DEBUG', false);

// Dependances
require_once 'ado_lite/adodb.inc.php';
require_once SMARTY . '/libs/Smarty.class.php';

?>
