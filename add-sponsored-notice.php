<?php
/*
 * Plugin Name: Add sponsored notice
 * Plugin URI:  https://github.com/sethiele/add-sponsored-notice
 * Description: Add a "Sponsored" notice to your posts.
 * Version:     1.0.0
 * Author:      Sebastian Thiele
 * Author URI:  http://sebastian-thiele.net
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: asnlang
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! defined( 'ASN_FILE' ) ) {
 define( 'ASN_FILE', __FILE__ );
}

if ( ! defined( 'ASN_PATH' ) ) {
 define( 'ASN_PATH', plugin_dir_path( ASN_FILE ) );
}

if ( ! defined( 'ASN_BASENAME' ) ) {
 define( 'ASN_BASENAME', plugin_basename( ASN_FILE ) );
}

if ( ! defined( 'ASN_T_DOMAIN' ) ) {
  define( 'ASN_T_DOMAIN', 'asnlang' );
}

if ( ! defined( 'ASN_OPTION_NAME' ) ) {
  define( 'ASN_OPTION_NAME', 'asn' );
}

$required_files = array(
  'frontend' => ASN_PATH . 'asn-frontend.php'
);

foreach ($required_files as $key => $value) {
  require_once($value);
}

/**
 * Load Textdomain
 *
 * @since 1.0.0
 */
function asn_load_textdomain() {
  load_plugin_textdomain( 'asnlang', FALSE, dirname( ASN_BASENAME ) . '/languages/' );
}
add_action( 'init', 'asn_load_textdomain' );


/**
 * activate plugin
 *
 * - create options with defaults
 * - register uninstall hook
 *
 * @since 1.0.0
 */
function asn_activate(){
  asn_load_textdomain();
  if (get_option(ASN_OPTION_NAME) == NULL) {
    $inital_values = array(
      'short' => array(
        'auto_show' => true,
        'append' => false,
        'versions' => array(
          "[".__( 'Sponsored', ASN_T_DOMAIN)."]"
        )
      ),
      'description' => array(
        'auto_show' => true,
        'append' => false,
        'versions' => array(
          __ ('This Post is a sponsored post', ASN_T_DOMAIN)
        )
      )
    );

    add_option(ASN_OPTION_NAME, $inital_values);
  }

  // Register uninstall
  if ( function_exists('register_uninstall_hook') )
    register_uninstall_hook(__FILE__, 'asn_deinstall');
}
register_activation_hook( __FILE__, 'asn_activate' );


/**
 * uninstall plugin
 *
 * - delete Options
 *
 * @since 1.0.0
 */
function asn_deinstall(){
  delete_option(ASN_OPTION_NAME);
}


/**
* Return Notice Text
*
* @since 1.0.0
*
* @param int      $text The Number of preset text
* @param array    $options {
*     Optional. An array of arguments.
*
*     @type string $before HTML or Text before notice. Default '<p>'
*     @type string $after HTML or Text after notice. Default '</p>'
* }
* @return string  notice
*/
function get_asp_notice($text, $options = array('before' => NULL, 'after' => NULL))
{
  $before = $options['before'] ?: "<p>";
  $after = $options['after'] ?: "</p>";
  $note = get_notice_text($text-1);
  return $before.$note.$after;
}


/**
 * Get notice short text
 * @param  int    $position Position in settings
 * @return string           Value
 */
function get_notice_text($position){
  $values = get_option(ASN_OPTION_NAME);
  return $values['short']['versions'][$position];
}


/**
 * Add Notice to title based on options
 * @param  string $title  actual title
 * @param  int    $id     post id
 * @return string         new title
 *
 * @since 1.0.0
 */
function asn_the_title( $title, $id = null ) {
  $options = get_option(ASN_OPTION_NAME);
  $notice = '<span class="asn-title-notice">' . get_notice_text(0) . "</span>";
  if (isset($options['short']['auto_show'])) {
    $new_title = ($options['short']['append']) ? $title . " " . $notice : $notice . " " . $title;
    return $new_title;
  }
  return $title;
}
add_filter( 'the_title', 'asn_the_title', 10, 2 );

?>
