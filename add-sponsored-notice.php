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
define( 'TDOMAIN', 'asnlang' );
define( 'OPTION_NAME', 'asn' );


/**
 * Load Textdomain
 *
 * @since 1.0.0
 */
function asn_load_textdomain() {
  load_plugin_textdomain( 'asnlang', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
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
  if (get_option(OPTION_NAME) == NULL) {

    add_option(OPTION_NAME, array(
      'short_text' => array(
        "[".__( 'Sponsored', TDOMAIN)."]"
      ),
      'description' => array(
        __ ('This Post is a sponsored post', TDOMAIN)
      ),
      'in_title' => true,
      'append' => true
    ));

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
  delete_option(OPTION_NAME);
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
  $values = get_option(OPTION_NAME);
  return $values['short_text'][$position];
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
  $options = get_option(OPTION_NAME);
  if ($options['in_title']) {
    $new_title = ($options['append'] ? $title." ".get_notice_text(0) : get_notice_text(0)." ".$title );
    return $new_title;
  }
  return $title;
}
add_filter( 'the_title', 'asn_the_title', 10, 2 );
?>
