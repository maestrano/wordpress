<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '{{ wordpress_db_name }}');

/** MySQL database username */
define('DB_USER', '{{ wordpress_db_user }}');

/** MySQL database password */
define('DB_PASSWORD', '{{ wordpress_db_password }}');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
// TODO: generate at setup
// define('AUTH_KEY',         'put your unique phrase here');
// define('SECURE_AUTH_KEY',  'put your unique phrase here');
// define('LOGGED_IN_KEY',    'put your unique phrase here');
// define('NONCE_KEY',        'put your unique phrase here');
// define('AUTH_SALT',        'put your unique phrase here');
// define('SECURE_AUTH_SALT', 'put your unique phrase here');
// define('LOGGED_IN_SALT',   'put your unique phrase here');
// define('NONCE_SALT',       'put your unique phrase here');
define('AUTH_KEY',         'IZ;xQLkz$;kj3{yKD-sS&$;:v*??@~_xSi#y}-@sW*3.(2)=+P$+m(IdZEED=<>f');
define('SECURE_AUTH_KEY',  'jlC)@Y+a+4%/:*B#lsWU#G-{M*~jUF*1#0p6cF4=cYaO^.r0 l,,^<6r[JX}?IT{');
define('LOGGED_IN_KEY',    '?h$Jh)/6+0n-FB(H;|r7j>_7+jh:7h hG9jSyVD?YsJBs_5|j:^0-At<_|44y6q^');
define('NONCE_KEY',        'N<2K]PLpRNf9c;5&!b^7-gys?|C{ ?vsBulqQ+xmTZr4y}.P^~.p;!Qv^$O0,@XI');
define('AUTH_SALT',        'la9`p|hT4&2h0^|e[=$bGG)dN-89?Ml+|d1lC`o$5G$|q%{-V2uYi(,jn>y%`=2e');
define('SECURE_AUTH_SALT', 'o0:czSnW[|v4 WJ;D>+~l-cgcpBpd{hq}WN4:d},Ff*}g]UE6[PGlR$G=-H^:Pfl');
define('LOGGED_IN_SALT',   '1>z-Qsm`3_bc9R5 djX |U%71c@z?_B.D*/ D~%l/gjn$%.(O#__j[n45W%seE0z');
define('NONCE_SALT',       '+vpbd~6L`+m}nL!`*5uyyRlC5g$73;Xu(RoS:PChaI#H{e-Bpf-DTu<D=fmaT:G/');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
