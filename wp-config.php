<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'onchainacademic' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'u1aDqB8a9J*WyJCrpK%H%DvW%@JK-? LneK^^@+u.zDE,SH<2j8slK5b+@eM5`qe' );
define( 'SECURE_AUTH_KEY',  'u8jif4p&TwadpHNY)}){zrjIl)B-qs>uUBaE^>*{rjsI]1ohZ)4Wyk2bJ.%f+3Fc' );
define( 'LOGGED_IN_KEY',    '[uuJ-[JDrA=ZAf+V*c*MON>HH6f?4upnGv:mE3:LocJl)&>]92z;)q|yrVb*)q7M' );
define( 'NONCE_KEY',        'O8Ry)f!-%6>ix|q*8[@x^gPeU*[zksnhhp-zN=N</mT]0`;6080mCzp[Z KQ*]f~' );
define( 'AUTH_SALT',        'S re>Pn{}dx1!Ddr;`#pjYqO6dy(,W}Z(qLztwAI7~n[.B=+83Y|8``>s$)cgdx7' );
define( 'SECURE_AUTH_SALT', '*OHuTinr,+#xzZshqz+Ub^LmWR3p~@e_VC=o~!4f5C97H9@E<d{JgXmQWtm@ts54' );
define( 'LOGGED_IN_SALT',   'i]+2Gkf,Zfh*gWa#!,s?sc1-X `t:8iz@mZ$^vQh*D@MV,Oe4iE K,(,t:uVKL*t' );
define( 'NONCE_SALT',       'Iu]cI,p14[MqKkHQVI4lu<Wf{kI{llPQ3_m5q L3O%I<HiDo6ru^355y>>h`0bmh' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
