<?php
/**
 * This controller processes a SAML response and deals with
 * user matching, creation and authentication
 * Upon successful authentication it redirects to the URL
 * the user was trying to access.
 * Upon failure it redirects to the Maestrano access
 * unauthorized page
 *
 */

//-----------------------------------------------
// Define root folder
//-----------------------------------------------
define("MAESTRANO_ROOT", realpath(dirname(__FILE__) . '/../../'));

error_reporting(0);

require MAESTRANO_ROOT . '/app/init/auth.php';

// Destroy session completely to avoid garbage (undeclared classes)
// but keep previous url if defined
session_start();
if(isset($_SESSION['mno_previous_url'])) {
	$previous_url = $_SESSION['mno_previous_url'];
}
session_unset();
session_destroy();

// Restart session and inject previous url if defined
session_start();
if(isset($previous_url)) {
	$_SESSION['mno_previous_url'] = $previous_url;
}

// Options variable
if (!isset($opts)) {
  $opts = array();
}

// Build SAML response
$samlResponse = new Maestrano_Saml_Response($_POST['SAMLResponse']);

try {
    if ($samlResponse->isValid()) {
        // Get the user as well as the user group
        $user = new Maestrano_Sso_User($samlResponse);

        // Get Maestrano User
        $sso_user = new MnoSsoUser($samlResponse, $opts);

        // Find or create the User
        $sso_user->findOrCreate();

        // Once the user is created/identified, we store the maestrano session.
        // This session will be used for single logout
        $mnoSession = new Maestrano_Sso_Session($_SESSION, $user);
        $mnoSession->save();

        // Redirect the user to previous or home page
        if(isset($_SESSION['mno_previous_uri'])) {
          header('Location: ' . $_SESSION['mno_previous_uri']);
        } else {
          header('Location: /wp-admin');
        }
    }
    else {
        echo 'There was an error during the authentication process.<br/>';
        echo 'Please try again. If issue persists please contact support@maestrano.com';
    }
}
catch (Exception $e) {
    echo 'There was an error during the authentication process.<br/>';
    echo 'Please try again. If issue persists please contact support@maestrano.com';
}
