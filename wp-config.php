<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '256M');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'admin_yeswatwo');

/** MySQL database username */
define('DB_USER', 'yeswatwo');

/** MySQL database password */
define('DB_PASSWORD', 'rootyeswatwo');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', 'utf8_general_ci');

define('WP_ALLOW_REPAIR', true);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'r_oy7hGezIlG,oi-k)@C5L4JbV~75<l4$U7&SJKF;w_75!;C|XXVs=R7k1%UbO0=');
define('SECURE_AUTH_KEY',  ':dF8Wj|5V[SnkT`FlVP=AXk/9gaX>ozsxibIWgun/3S8oXvo2hXk~?>!`7a3yp4&');
define('LOGGED_IN_KEY',    'L_eX<^1g[Y6gm{^(pNyT|5[[avk<um%{9;fronJgloKTW$0Yf2bKiXtLh>zq(8K:');
define('NONCE_KEY',        'e<andn9 #,HUPS+S3Y?b[a],U<%EKG>BfkLR.%8la6Yu:^zFKfQB0Lqe!_KV?v&/');
define('AUTH_SALT',        '~yE8=~XF}Q550/O]&DFAX<Q$];~Lq@(iHSvYc?Z2bMvW]qnjO2Wld8yLh)|XMXwK');
define('SECURE_AUTH_SALT', 'wAJP-]%Ibu4w8?nP@%lFQtKCan}-~9uh()|fc|ch*2NCDgjc08OxutE=Y^|`e1P;');
define('LOGGED_IN_SALT',   'Z0e~ow;5K[CvO*P _V-~#yT*;SC`yQ9rzp?rezuB(APq},w%@8k uX]Xtj)epN~l');
define('NONCE_SALT',       '=]mPQk<m4U6vR70]ai!szvlK+n%WmnJ4:x(ad[v}&l^b+e[>)XqqXz-Vz__ Vv3o');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ya_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
//define('FS_METHOD','direct');
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

