<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'ycn_vcard');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'DH}?y|_k;6npn~(#9%JecN4&/I0K)[7ApQLo6+UK=aFkXVhjIGtdG/k:9ZT]P-p!');
define('SECURE_AUTH_KEY',  'GXdH?-3IYUwI;clU91/4Bst3n,`a;OJ?KP>KtP>YnechK*!Qf*A|0N3y`8!G]m08');
define('LOGGED_IN_KEY',    'SjE%ZzeD+{;}{<C/3DKYL:*w<Qaru#w@j}eM7vMg|=a}1SPeY{d_4ZVp^&iB<^/t');
define('NONCE_KEY',        '*Eiq3BF[dy90U0=be97R5KI~AbLWxq5^-GYph<+bgnbrQVLCXbQyXP>(*]9ckqC#');
define('AUTH_SALT',        'oGDTAJ*UBlJc9kM,}V7Mw$Q:th$taS8y}@R#6;R HHZ!?H-xv/h>31,N1y-3uGq|');
define('SECURE_AUTH_SALT', 'm9U~z9U=Q(5(q[# TZ/yMZIz@Yl1/cpi:x|*_KTR$zo82`%;0<[=n Xkfv-HA]+Y');
define('LOGGED_IN_SALT',   '#K_5y[6CZi,.Vi0E=qy>9G+~QJ>Hi;6(m@fe#v@g2la@zJ_UU>{[p?1WQ0=Z}j&b');
define('NONCE_SALT',       'JH`W]/_Eel-0ec0kd4F^3RzE][(+RKA{K}0fm^CLj@ijA%L/6f(56c/{vJ]JUU&1');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'ycnv_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d'information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 * 
 * @link https://codex.wordpress.org/Debugging_in_WordPress 
 */
define('WP_DEBUG', false);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');