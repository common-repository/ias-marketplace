<?php
/**
 * WP-IAS-Marketplace
 *
 *
 * @package   WP-IAS-Marketplace
 * @author    Integrated Auction Solutions, LLC.
 * @link      https://www.integratedauctionsolutions.com
 * @copyright (c) 2019 Integrated Auction Solutions, LLC.
 */

namespace IAS\WPIAS\Endpoint;
use IAS\WPIAS;

/**
 * @subpackage REST_Controller
 */
class Example
{
  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Initialize the plugin by setting localization and loading public scripts
   * and styles.
   *
   * @since     1.0.0
   */
  private function __construct()
  {
    $plugin = WPIAS\Plugin::get_instance();
    $this->plugin_slug = $plugin->get_plugin_slug();
  }

  /**
   * Set up WordPress hooks and filters
   *
   * @return void
   */
  public function do_hooks()
  {
    add_action('rest_api_init', array($this, 'register_routes'));
  }

  /**
   * Return an instance of this class.
   *
   * @since     1.0.0
   *
   * @return    object    A single instance of this class.
   */
  public static function get_instance()
  {
    // If the single instance hasn't been set, set it now.
    if (null == self::$instance) {
      self::$instance = new self();
      self::$instance->do_hooks();
    }

    return self::$instance;
  }

  /**
   * Register the routes for the objects of the controller.
   */
  public function register_routes()
  {
    $version = '1';
    $namespace = $this->plugin_slug . '/v' . $version;
    $endpoint = '/example/';

    register_rest_route($namespace, $endpoint, array(
      array(
        'methods' => \WP_REST_Server::READABLE,
        'callback' => array($this, 'get_example'),
        //'permission_callback' => array($this, 'example_permissions_check'),
        'args' => array()
      )
    ));

    register_rest_route($namespace, $endpoint, array(
      array(
        'methods' => \WP_REST_Server::CREATABLE,
        'callback' => array($this, 'update_example'),
        'permission_callback' => array($this, 'example_permissions_check'),
        'args' => array()
      )
    ));

    register_rest_route($namespace, $endpoint, array(
      array(
        'methods' => \WP_REST_Server::EDITABLE,
        'callback' => array($this, 'update_example'),
        'permission_callback' => array($this, 'example_permissions_check'),
        'args' => array()
      )
    ));

    register_rest_route($namespace, $endpoint, array(
      array(
        'methods' => \WP_REST_Server::DELETABLE,
        'callback' => array($this, 'delete_example'),
        'permission_callback' => array($this, 'example_permissions_check'),
        'args' => array()
      )
    ));
  }

  /**
   * Get Example
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Request
   */
  public function get_example($request)
  {
    $example_option = get_option('wpias_example_setting');

    // Don't return false if there is no option
    if (!$example_option) {
      return new \WP_REST_Response(
        array(
          'success' => true,
          'value' => ''
        ),
        200
      );
    }

    return new \WP_REST_Response(
      array(
        'success' => true,
        'value' => $example_option
      ),
      200
    );
  }

  /**
   * Create OR Update Example
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Request
   */
  public function update_example($request)
  {
    $updated = update_option(
      'wpias_example_setting',
      $request->get_param('exampleSetting')
    );

    return new \WP_REST_Response(
      array(
        'success' => $updated,
        'value' => $request->get_param('exampleSetting')
      ),
      200
    );
  }

  /**
   * Delete Example
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Request
   */
  public function delete_example($request)
  {
    $deleted = delete_option('wpias_example_setting');

    return new \WP_REST_Response(
      array(
        'success' => $deleted,
        'value' => ''
      ),
      200
    );
  }

  /**
   * Check if a given request has access to update a setting
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function example_permissions_check($request)
  {
    return current_user_can('manage_options');
  }
}
