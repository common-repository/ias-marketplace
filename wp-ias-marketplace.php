<?php
/**
 * WP-IAS-Marketplace
 *
 *
 * @package   WP-IAS-Marketplace
 * @author    Integrated Auction Solutions, LLC.
 * @link      https://www.integratedauctionsolutions.com
 * @copyright (c) 2019 Integrated Auction Solutions, LLC.
 *
 * @wordpress-plugin
 * Plugin Name:       ias-marketplace
 * Plugin URI:        https://www.integratedauctionsolutions.com/wordpress/plugin
 * Description:       IAS Marketplace Plugin for Wordpress
 * Version:           1.0.0
 * Author:            Integrated Auction Solutions
 * Author URI:        https://www.integratedauctionsolutions.com
 * Text Domain:       wp-ias-marketplace
 * License URI:       https://www.integratedauctionsolutions.com/terms-conditions-order-form/
 * Domain Path:       /languages
 */

namespace IAS\WPIAS;

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die();
}

define('WP_IAS_MARKETPLACE_VERSION', '1.0.0');

/**
 * Autoloader
 *
 * @param string $class The fully-qualified class name.
 * @return void
 *
 *  * @since 1.0.0
 */
spl_autoload_register(function ($class) {
  // project-specific namespace prefix
  $prefix = __NAMESPACE__;

  // base directory for the namespace prefix
  $base_dir = __DIR__ . '/includes/';

  // does the class use the namespace prefix?
  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) {
    // no, move to the next registered autoloader
    return;
  }

  // get the relative class name
  $relative_class = substr($class, $len);

  // replace the namespace prefix with the base directory, replace namespace
  // separators with directory separators in the relative class name, append
  // with .php
  $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

  // if the file exists, require it
  if (file_exists($file)) {
    require $file;
  }
});

/**
 * Initialize Plugin
 *
 * @since 1.0.0
 */
function init()
{
  $wpr = Plugin::get_instance();
  $wpr_shortcode = Shortcode::get_instance();
  $wpr_admin = Admin::get_instance();
  $wpr_rest = Endpoint\Example::get_instance();
}
add_action('plugins_loaded', 'IAS\\WPIAS\\init');

/**
 * Register activation and deactivation hooks
 */
register_activation_hook(__FILE__, array('IAS\\WPIAS\\Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('IAS\\WPIAS\\Plugin', 'deactivate'));
