<?php
//-----------------------------------------------
// Define root folder
//-----------------------------------------------
if (!defined('MAESTRANO_ROOT')) {
  define("MAESTRANO_ROOT", realpath(dirname(__FILE__) . '/../../'));
}

if (!defined('ROOT_PATH')) { define('ROOT_PATH', realpath(MAESTRANO_ROOT . '/../')); }

// Include Maestrano required libraries
require_once ROOT_PATH . '/vendor/autoload.php';
Maestrano::configure(ROOT_PATH . '/maestrano.json');

require_once MAESTRANO_ROOT . '/app/sso/MnoSsoUser.php';
