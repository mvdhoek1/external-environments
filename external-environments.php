<?php

declare(strict_types=1);

/**
 * Plugin Name:       External environments
 * Plugin URI:        https://www.yard.nl/
 * Description:       Add external environments to the wp-admin bar.
 * Version:           1.0.0
 * Author:            Mike van den Hoek
 * Author URI:        https://www.mikevandenhoek.nl/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       mvdh-environments
 * Domain Path:       /languages
 */

/**
 * If this file is called directly, abort.
 */
if (! defined('WPINC')) {
    die;
}

define('MVDH_VERSION', '1.0.0');
define('MVDH_DIR', basename(__DIR__));
define('MVDH_ROOT_PATH', __DIR__);

/**
 * Manual loaded file: the autoloader.
 */
require_once __DIR__ . '/autoloader.php';
$autoloader = new MVDH\Environments\Autoloader();

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
\add_action('plugins_loaded', function () {
    $plugin = (new \MVDH\Environments\Foundation\Plugin(__DIR__))->boot();
}, 10);
