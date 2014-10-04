<?php
/**
 * Plugin Name: Maestrano Single Sign-On
 * Description: Enable Single Sign-On via Maestrano
 * Version: 1.0
 * Author: Maestrano
 * Author URI: https://maestrano.com
 * License: GPL2
 */

require_once ABSPATH . 'maestrano/app/init/base.php';
$maestrano = MaestranoService::getInstance();
// Redefine session and login functions if enabled
if ($maestrano->isSsoEnabled()) {
  
  // Redefined function to check if maestrano session
  // is still valid
  function is_user_logged_in() {
    $maestrano = MaestranoService::getInstance();
  	$user = wp_get_current_user();
    
    // Start session if not started already
    if (!isset($_SESSION)) session_start();
    
    // Check user exists and maestrano session is still valid
  	if ( !$user->exists() || !$maestrano->getSsoSession()->isValid()) {
      if ($maestrano->isSsoIntranetEnabled()) {
        // Redirect straight to authentication if intranet
        // mode enabled
        wp_redirect($maestrano->getSsoInitUrl());
      } else {
        return false;
      }
  	  
  	}

  	return true;
  }
  
  // Change auth redirect url
  function auth_redirect() {
  	// Checks if a user is logged in, if not redirects them to the login page

  	$secure = ( is_ssl() || force_ssl_admin() );

  	$secure = apply_filters('secure_auth_redirect', $secure);

  	// If https is required and request is http, redirect
  	if ( $secure && !is_ssl() && false !== strpos($_SERVER['REQUEST_URI'], 'wp-admin') ) {
  		if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
  			wp_redirect( set_url_scheme( $_SERVER['REQUEST_URI'], 'https' ) );
  			exit();
  		} else {
  			wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
  			exit();
  		}
  	}

  	if ( is_user_admin() )
  		$scheme = 'logged_in';
  	else
  		$scheme = apply_filters( 'auth_redirect_scheme', '' );

  	if ( $user_id = wp_validate_auth_cookie( '',  $scheme) ) {
  		do_action('auth_redirect', $user_id);

  		// If the user wants ssl but the session is not ssl, redirect.
  		if ( !$secure && get_user_option('use_ssl', $user_id) && false !== strpos($_SERVER['REQUEST_URI'], 'wp-admin') ) {
  			if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
  				wp_redirect( set_url_scheme( $_SERVER['REQUEST_URI'], 'https' ) );
  				exit();
  			} else {
  				wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
  				exit();
  			}
  		}

  		return;  // The cookie is good so we're done
  	}

  	// The cookie is no good so force login
  	nocache_headers();

  	$redirect = ( strpos( $_SERVER['REQUEST_URI'], '/options.php' ) && wp_get_referer() ) ? wp_get_referer() : set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    
    // Change login url
    $maestrano = MaestranoService::getInstance();
  	$login_url = $maestrano->getSsoInitUrl();

  	wp_redirect($login_url);
  	exit();
  }
  
  function mno_redirect_user_from_login() {
    $maestrano = MaestranoService::getInstance();
    
    if ($_GET["loggedout"] == 'true') {
      if ($maestrano->isSsoIntranetEnabled()) {
        // Redirect straight to maestrano logout page
        $redirect = $maestrano->getSsoLogoutUrl();
      } else {
        // Redirect to blog home page
        $redirect = '/';
      }
    } else {
      // Login - Trigger SSO
    	$redirect = $maestrano->getSsoInitUrl();
    }
    
  	echo "<script type='text/javascript'>window.location = '{$redirect}';</script>";
  }
  
  // Add the Star! framework to Wordpress - Only on administration
  // pages
  function mno_add_star_framework() {
    if ( is_user_admin() ) {
      ?>
      <script src="//cdn.maestrano.com/apps/mno_libs/mno-loader.js" type="text/javascript"></script>
      <script type="text/javascript">
        window.mnoLoader.init('wordpress','1');
      </script>
      <?php
    }
  }
  
  // Make sure user goes through maestrano login/logout process
  add_action( 'login_head', 'mno_redirect_user_from_login' );
  
  // Add Star! framework
  add_action('wp_footer', 'mno_add_star_framework');
}
