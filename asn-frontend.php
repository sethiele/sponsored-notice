<?php
add_action( 'admin_menu', 'asn_menu' );


function asn_menu() {
  add_options_page( __('Add Sponsored Notice', ASN_T_DOMAIN), __('Sponsored Notice', ASN_T_DOMAIN), 'manage_options', 'add-sponsored-notice', 'asn_setting_page' );
  add_action( 'admin_init', 'asn_plugin_settings' );
}

function asn_plugin_settings(){
  register_setting( 'asn-setting-group', ASN_OPTION_NAME );
}

function asn_setting_page() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }

  // Varaibles
  $hidden_field_name = 'asn_submit_hidden';
  $options = get_option(ASN_OPTION_NAME);
  $short_val = ASN_OPTION_NAME."[short]";
  $description_val = ASN_OPTION_NAME."[description]";


  echo '<div class="wrap">';
  echo '<h1>"' . __('Add Sponsored Notice', ASN_T_DOMAIN) . '" ' . __( 'options', ASN_T_DOMAIN ) . "</h1>";
  ?>
  <form name="form1" method="post" action="options.php">
    <?php settings_fields( 'asn-setting-group' ); ?>
    <?php do_settings_sections( 'asn-setting-group' ); ?>
    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

    <h2 class="title"><?php _e('Short title settings', ASN_T_DOMAIN); ?></h2>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row"><?php _e('Add notice in title?', ASN_T_DOMAIN); ?></th>
          <td>
            <input type="checkbox" name="<? echo $short_val; ?>[auto_show]" value="true" <?php echo (isset($options['short']["auto_show"]))? "checked": ""; ?>>
            <?php _e('Check this to display the notice in title field', ASN_T_DOMAIN); ?>
            <small><?php _e('default:', ASN_T_DOMAIN); ?> <?php _e('checked', ASN_T_DOMAIN); ?></small>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Notice position', ASN_T_DOMAIN); ?></th>
          <td>
            <input type="radio" name="<? echo $short_val; ?>[append]" value="true" <?php echo ($options["short"]["append"])? "checked": ""; ?>>
            <?php _e('Before title', ASN_T_DOMAIN); ?>
            <input type="radio" name="<? echo $short_val; ?>[append]" value="" <?php echo (!$options["short"]["append"])? "checked": ""; ?>>
            <?php _e('After title', ASN_T_DOMAIN); ?>
            <small><?php _e('default:', ASN_T_DOMAIN); ?> <?php _e('before', ASN_T_DOMAIN); ?></small>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Short notice', ASN_T_DOMAIN); ?></th>
          <td>
            <?php foreach ($options["short"]["versions"] as $key => $value): ?>
              <?php if ($options["short"]["versions"][$key] != ""): ?>
                <p>
                  <input type="text" name="<? echo $short_val; ?>[versions][<?php echo $key; ?>]" value="<?php echo $options["short"]["versions"][$key]; ?>" class="regular-text ltr">
                  <?php printf(__("Version [%s]", ASN_T_DOMAIN), $key+1); ?>
                  <?php if($key == 0){echo "(" . __("Default", ASN_T_DOMAIN) . ")";} ?>
                </p>
              <?php endif; ?>
            <?php endforeach; ?>
            <p><input type="text" name="<? echo $short_val; ?>[versions][]" value="" class="regular-text ltr"></p>
          </td>
        </tr>
      </tbody>
    </table>
    <h2 class="title"><?php _e('Description settings', ASN_T_DOMAIN); ?></h2>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row"><?php _e('Add description in Body?', ASN_T_DOMAIN); ?></th>
          <td>
            <input type="checkbox" name="<? echo $description_val; ?>[auto_show]" value="true" <?php echo (isset($options['description']["auto_show"]))? "checked": ""; ?>>
            <?php _e('Check this to display the description in body', ASN_T_DOMAIN); ?>
            <small><?php _e('default:', ASN_T_DOMAIN); ?> <?php _e('checked', ASN_T_DOMAIN); ?></small>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Description position', ASN_T_DOMAIN); ?></th>
          <td>
            <input type="radio" name="<? echo $description_val; ?>[append]" value="true" <?php echo ($options["description"]["append"])? "checked": ""; ?>>
            <?php _e('Before content', ASN_T_DOMAIN); ?>
            <input type="radio" name="<? echo $description_val; ?>[append]" value="" <?php echo (!$options["description"]["append"])? "checked": ""; ?>>
            <?php _e('After content', ASN_T_DOMAIN); ?>
            <small><?php _e('default:', ASN_T_DOMAIN); ?> <?php _e('before', ASN_T_DOMAIN); ?></small>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Long description', ASN_T_DOMAIN); ?></th>
          <td>
            <?php foreach ($options["description"]["versions"] as $key => $value): ?>
              <?php if ($options["description"]["versions"][$key] != ""): ?>
                <p>
                  <input type="text" name="<? echo $description_val; ?>[versions][<?php echo $key; ?>]" value="<?php echo $options["description"]["versions"][$key]; ?>" class="regular-text ltr">
                  <?php printf(__("Version [%s]", ASN_T_DOMAIN), $key+1); ?>
                  <?php if($key == 0){echo "(" . __("Default", ASN_T_DOMAIN) . ")";} ?>
                </p>
            <?php endif; ?>
            <?php endforeach; ?>
            <input type="text" name="<? echo $description_val; ?>[versions][]" value="" class="regular-text ltr">
          </td>
        </tr>
      </tbody>
    </table>
    <?php submit_button(); ?>
  </form>
  <?php

  if (defined('WP_DEBUG') && true === WP_DEBUG) {
    echo "<pre>";
    echo "<h3>Debug:</h3>";
    print_r($options);
    echo "</pre>";
  }
  echo '</div>';
}
?>
