<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
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
define( 'AUTH_KEY',         'm>Y^KT-dvdM=vR&0x!Yk[#x;j*?WL,)QEZL)M@,~by%TXa>171)_ A}MZ>acz,SF' );
define( 'SECURE_AUTH_KEY',  'gLPiXGRI0[xM7zi6n:rwBm(:cBVu}MB:vCIdW}Rokwo]ifJPo5wep%6YM>s(A*>I' );
define( 'LOGGED_IN_KEY',    'DT6[M1f3plN%k(|^FJwO&}*Oz8H*{Z@RPs6~pZ@]l^sjdG0 OM6h?9/~HSwCsLS#' );
define( 'NONCE_KEY',        '$%bW=(`4*eQ!UX4SL5yM-(J+l<fhkmf&7<J?iS04c.KneB(;ZxZQKBEfW6U!5xFL' );
define( 'AUTH_SALT',        'WT>[Kg%c0a$U$#m6QpFzThrRj;pLw*ov3y>zowo6%(ktRv6OVm/mM(E>v6Y>l,P8' );
define( 'SECURE_AUTH_SALT', '&CvmO5Dft [.:Cpo9Tz[oyKS[)u<5=>#sA=n/<vRlm5kZ-FX|`skZjD)6iA)4<tW' );
define( 'LOGGED_IN_SALT',   '.:~NDyr_`Z6n:StcfEbBBDYZRscb);}EjYbGp==f_O0/Uw571{h7$k*ao=D`BGtn' );
define( 'NONCE_SALT',       'e?y$5_Vz}@BuIGkCH-:=73YKZU4qGgi2$>OkP>cE4@Rtex(2PV3vt29[kHY/iY[s' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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

