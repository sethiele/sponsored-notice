<?php
/**
 * Initaialice Meta Box for Posts
 *
 * @since 1.0.0
 */
function asn_add_meta_box() {
  add_meta_box(
    'asn_meta_box',
    __('Sponsored Notice', ASN_T_DOMAIN),
    'asn_meta_box_callback',
    'post',
    'side'
  );
}
add_action( 'add_meta_boxes', 'asn_add_meta_box' );

function asn_meta_box_callback( $post ){
  wp_nonce_field( 'asn_save_meta_box_data', 'asn_meta_box_nonce' );

  $is_active = get_post_meta( $post->ID, ASN_POST_OPTION_ACTIVE, true );
  $type = get_post_meta( $post->ID, ASN_POST_OPTION_TYPE, true );
  $options = get_option(ASN_OPTION_NAME);
  if ($is_active) {
    $active = "checked";
  } else {
    $active = "";
  }

  ?>
  <p>
    <label for="<? echo ASN_POST_OPTION_ACTIVE; ?>">
      <input type="checkbox" name="<? echo ASN_POST_OPTION_ACTIVE; ?>" id="<? echo ASN_POST_OPTION_ACTIVE; ?>" value="true" <?php echo $active; ?>>
      <?php _e('This Post is comercial', ASN_T_DOMAIN); ?>
    </label>
  </p>
  <p>
    <select id="<?php echo ASN_POST_OPTION_TYPE; ?>" name="<?php echo ASN_POST_OPTION_TYPE; ?>">
      <?php foreach ($options[ASN_POSITION_TITLE]['versions'] as $key => $value): ?>
        <?php if ($value != ""): ?>
          <option value="<?php echo $key; ?>" <?php echo ($key == $type)?"selected":""; ?>><?php echo $value; ?></option>
        <?php endif; ?>
      <?php endforeach; ?>
    </select>
    <label for="<?php echo ASN_POST_OPTION_TYPE; ?>">
      <?php _e('kind of Comercial', ASN_T_DOMAIN); ?>
    </label>
  </p>
  <?php
  // echo '<label for="myplugin_new_field">';
  // _e( 'Description for this field', 'myplugin_textdomain' );
  // echo '</label> ';
  // echo '<input type="text" id="myplugin_new_field" name="myplugin_new_field" value="' . esc_attr( $value ) . '" size="25" />';
}



function asn_save_meta_box_data( $post_id ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['asn_meta_box_nonce'] ) ) {
    return;
  }

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $_POST['asn_meta_box_nonce'], 'asn_save_meta_box_data' ) ) {
    return;
  }

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  // Check the user's permissions.
  if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
      return;
    }

  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }
  }

  /* OK, it's safe for us to save the data now. */

  // Make sure that it is set.
  if ( ! isset( $_POST[ASN_POST_OPTION_ACTIVE] ) ) {
    $_POST[ASN_POST_OPTION_ACTIVE] = false;
  }

  // Sanitize user input.
  $option_active = sanitize_text_field( $_POST[ASN_POST_OPTION_ACTIVE] );
  $option_type = sanitize_text_field( $_POST[ASN_POST_OPTION_TYPE] );

  // Update the meta field in the database.
  update_post_meta( $post_id, ASN_POST_OPTION_ACTIVE, $option_active );
  update_post_meta( $post_id, ASN_POST_OPTION_TYPE, $option_type );
}
add_action( 'save_post', 'asn_save_meta_box_data' );
?>
