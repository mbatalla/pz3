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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'pobena');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'sQUIsHytRIcEPS7.37');

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
define('AUTH_KEY',         '7mw=JBf!*M7ULV=~G$/ofV:#FJeqV(SudkKrT2.?Ht8`3~r{nUzwl.8uYDdP7( %');
define('SECURE_AUTH_KEY',  'f9pKLqaf!V@5&Z C}t&VmqzgK.kv*DkL)^6eX:KD/I5wlp~F:|7#cbIi<9f=,Fdj');
define('LOGGED_IN_KEY',    ':vOSx`3V,m,YvC~YTpd>A[Tkkk1q,]C4u)7Y=G,;{BteL)A`,$~z2>4_A,~Q iuO');
define('NONCE_KEY',        'gZzSsek{+p^@JZ<0c%ApHZ)Tr=/&wm%&XKSU%m!5>-IF=zPk?9<&M;LELsR7teeC');
define('AUTH_SALT',        'O1#_*w[ocGg.rHN58f14.; eq=_*6StpT,!K6$LhG/7::$^}HI #x%|;$l}{&V1_');
define('SECURE_AUTH_SALT', 'FFPi|3pXXI*]-]t7Y$GwptYiL;6hu:a@(|jI,?])#Q tyH|MN3x;vQ38RIjra;E ');
define('LOGGED_IN_SALT',   'k]Iw/w=I;9&tPBz-O:{`&P!WQ(K-s;0Lf:j.U3~gVC+fiHwRQ=d $.Map=.+0pHs');
define('NONCE_SALT',       'Zg>l3s*<]Z6-Vk0H=,(^c&6DrSGBx3~-Mk>AB/I_UibH{Fv+^^S%=IwXA /WaCA@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'pbn_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
