<?php
/**
 * This controller creates a SAML request and redirects to
 * Maestrano SAML Identity Provider
 *
 */

//-----------------------------------------------
// Define root folder
//-----------------------------------------------
define("MAESTRANO_ROOT", realpath(dirname(__FILE__) . '/../../'));

error_reporting(0);

require MAESTRANO_ROOT . '/app/init/auth.php';

$_SESSION['mno_previous_uri'] = $_SERVER['HTTP_REFERER'];

$req = new Maestrano_Saml_Request($_GET);
header('Location: ' . $req->getRedirectUrl());
