<?php
/*
 * Plugin Name: Sponsored notice
 * Plugin URI:  https://github.com/sethiele/sponsored-notice
 * Description: Add a "Sponsored" notice to your posts.
 * Version:     0.9.0.rc1
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

$required_files = array(
  'varaibles' => ASN_PATH . 'asn-variables.php',
  'frontend'  => ASN_PATH . 'asn-frontend.php',
  'metabox'   => ASN_PATH . 'asn-meta-box.php',
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
      ASN_POSITION_TITLE => array(
        'auto_show' => true,
        'append' => false,
        'versions' => array(
          "[".__( 'Sponsored', ASN_T_DOMAIN)."]"
        )
      ),
      ASN_POSITION_CONTENT => array(
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
 * Check if post is sponsored
 * @param  post  $post current post
 * @return boolean       Is Sponsored post (true) or not (false)
 */
function asn_is_sponsored($post){
  return get_post_meta( $post->ID, ASN_POST_OPTION_ACTIVE, true );
}


/**
* Return Notice Text
*
* @param string   $before Text or HTML Tags placed befor the notice. Default: '<p>'
* @param string   $after  Text or HTML Tags placed after the notice. Default: '</p>'

* @return string  notice
*
* * @since 1.0.0
*/
function asp_get_notice($before = NULL, $after = NULL)
{
  global $post;
  if (asn_is_sponsored($post)) {
    $before = $before ?: "<p>";
    $after = $after ?: "</p>";
    $version = get_post_meta( $post->ID, ASN_POST_OPTION_TYPE, true );
    $note = get_notice_text($version);
    return $before.$note.$after;
  }
}

/**
 * Get notice short text
 * @param  int    $position Position in settings
 * @return string           short text at position
 *
 * @since 1.0.0
 */
function get_notice_text($position = 0){
  $values = get_option(ASN_OPTION_NAME);
  return $values[ASN_POSITION_TITLE]['versions'][$position];
}

/**
 * Get notice description text
 * @param  int    $position Position in Settings
 * @return stirng           description at position
 *
 * @since 1.0.0
 */
function get_notice_description($position = 0){
  $values = get_option(ASN_OPTION_NAME);
  return $values[ASN_POSITION_CONTENT]['versions'][$position];
}

/**
 * Auto display in position
 * @param  string $position Position where to display. Accepts ASN_POSITION_TITLE, ASN_POSITION_CONTENT
 * @return boolean           is visible at position
 *
 * @since 1.0.0
 */
function visible_in($position){
  if($position != ASN_POSITION_CONTENT && $position != ASN_POSITION_TITLE){
    trigger_error(__('Error: Unknown $position', ASN_T_DOMAIN), E_USER_ERROR);
  }
  return isset(get_option(ASN_OPTION_NAME)[$position]['auto_show']);
}

/**
 * Append or prepand
 * @param  string $position Position where to display. Accepts ASN_POSITION_TITLE, ASN_POSITION_CONTENT
 * @return boolean           append (true) prepand (false)
 *
 * @since 1.0.0
 */
function append_or_prepend($position){
  if($position != ASN_POSITION_CONTENT && $position != ASN_POSITION_TITLE){
    trigger_error(__('Error: Unknown $position', ASN_T_DOMAIN), E_USER_ERROR);
  }
  return get_option(ASN_OPTION_NAME)[$position]['append'];
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
  global $pagenow;
  global $post;
  $notice = '<span class="asn-title-notice">' . get_notice_text(get_post_meta( $post->ID, ASN_POST_OPTION_TYPE, true )) . "</span>";
  if (visible_in(ASN_POSITION_TITLE) && asn_is_sponsored($post) && 'edit.php' != $pagenow) {
    $new_title = (append_or_prepend(ASN_POSITION_TITLE)) ? $title . " " . $notice : $notice . " " . $title;
    return $new_title;
  }
  return $title;
}
add_filter( 'the_title', 'asn_the_title', 10, 2 );


/**
 * Add Description to content
 * @param  string $content actual content
 * @return string          new string
 *
 * @since 1.0.0
 */
function asn_the_content($content){
  global $post;
  $description = '<p class="asn-description-notice">' . get_notice_description(get_post_meta( $post->ID, ASN_POST_OPTION_TYPE, true )) . "</p>";
  if (visible_in(ASN_POSITION_CONTENT) && asn_is_sponsored($post)) {
    $new_content = (append_or_prepend(ASN_POSITION_CONTENT)) ? $content . " " . $description : $description . " " . $content;
    return $new_content;
  }
  return $content;
}
add_filter( 'the_content', 'asn_the_content' );

?>
