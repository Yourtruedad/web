<?php

ob_start();
// Session support
session_start();

// Error reporting //
error_reporting(E_ALL);
ini_set("display_errors", 0);

// Includes //
// Config
require_once('config/config.php');
// Autoload packages
require_once('vendor/autoload.php');
// Autoload classes
spl_autoload_register(function ($className) {
    include('models/' . $className . '.class.php');
});
// End Includes //

date_default_timezone_set(CONFIG_SYSTEM_TIMEZONE);

$common = new common();

// Filter user input - GET AND POST only are used
$_POST = $common->removeNotAllowedWords($_POST);
$_GET = $common->removeNotAllowedWords($_GET);

// Set the default module name
$module = DEFAULT_MODULE_NAME;
$action = '';

if (isset($_GET['module']) && !empty($_GET['module'])) {
    $getModule = $common->secureStringVariable($_GET['module']);
    if (in_array($getModule, $systemModules) && true === file_exists('views/' . $getModule . MODULE_FILE_EXTENSION)) {
        $module = $getModule;
    }
    if (isset($_GET['action']) && !empty($_GET['action'])) {
        $getAction = $common->secureStringVariable($_GET['action']);
        if (in_array($getAction, $systemModuleActions[$module])) {
            $action = $getAction;
        }
    }
}

// Account ID
if (!empty($_SESSION['AccountID'])) {
    $accountId = $_SESSION['AccountID'];
}

include('views/template.php');

//session_write_close();

?>