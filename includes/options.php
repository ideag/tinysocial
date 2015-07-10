<?php
// ========================================== SETTINGS

class tinySocial_Options {
  private static $defaults = array();
  private static $fields = array();
  public static $values = array();
  private static $id = '';
  private static $menu_title = '';
  private static $title = '';
  private static $description = '';
  private static $file = '';
  private static $role = 'manage_options';

  public static function init($str='tiny',$menu_title,$title,$fields,$file=false,$role=false) {
    self::$fields     = $fields;
    self::$file       = $file ? $file : __FILE__;
    self::$id         = $str.'_options';
    self::$menu_title = $menu_title;
    self::$title      = $title;
    self::$role       = $role ? $role : self::$role;
    self::build_settings();
    add_options_page(self::$title, self::$menu_title, self::$role, self::$file, array('tinySocial_Options','page'));
  }

  // Register our settings. Add the settings section, and settings fields
  public static function build_settings(){
    register_setting( self::$id, self::$id, array( 'tinySocial_Options' , 'validate' ) );
    if (is_array(self::$fields)) foreach (self::$fields as $group_id => $group) {
      add_settings_section( $group_id, $group['title'], $group['callback']?is_array($group['callback'])?$group['callback']:array('tinySocial_Options',$group['callback']):'', self::$file );
      if (is_array($group['options'])) foreach ($group['options'] as $option_id => $option) {
        $option['args']['option_id'] = $option_id;
        $option['args']['title'] = $option['title'];
        add_settings_field($option_id, $option['title'], $option['callback']?is_array($option['callback'])?$option['callback']:array('tinySocial_Options',$option['callback']):'', self::$file, $group_id,$option['args']);
      }
    }
  }

  // ************************************************************************************************************
  // Utilities
  public static function is_assoc($arr) {
      return array_keys($arr) !== range(0, count($arr) - 1);
  }

  // ************************************************************************************************************

  // Callback functions

  // DROP-DOWN-BOX - Name: select - Argument : values: array()
  public static function select($args) {
    $items = $args['values'];
    echo "<select id='".self::$id."_{$args['option_id']}' name='".self::$id."[{$args['option_id']}]'>";
    if (self::is_assoc($items)) {
      foreach($items as $key=>$item) {
        $key = esc_attr($key);
        $selected = selected( $key, tinySocial::$options[$args['option_id']], false );
        echo "<option value='{$key}' $selected>$item</option>";
      }
    } else {
      foreach($items as $item) {
        $key = esc_attr($item);
        $selected = selected( $item, tinySocial::$options[$args['option_id']], false );
        echo "<option value='{$key}' $selected>$item</option>";
      }
    }
    echo "</select>";
  }

  // CHECKBOX - Name: checkbox
  public static function checkbox($args) {
    if (!isset(tinySocial::$options[$args['option_id']])) {
      tinySocial::$options[$args['option_id']] = false;
    }
    $checked = checked( tinySocial::$options[$args['option_id']], true, false );
    echo "<input ".$checked." id='{$args['option_id']}' name='".self::$id."[{$args['option_id']}]' type='checkbox' value=\"1\"/>";
  }

  // TEXTAREA - Name: textarea - Arguments: rows:int=4 cols:int=20
  public static function textarea($args) {
    if (!$args['rows']) $args['rows']=4;
    if (!$args['cols']) $args['cols']=20;
    echo "<textarea id='{$args['option_id']}' name='".self::$id."[{$args['option_id']}]' rows='{$args['rows']}' cols='{$args['cols']}' type='textarea'>".tinySocial::$options[$args['option_id']]."</textarea>";
  }

  // TEXTBOX - Name: text - Arguments: size:int=40
  public static function text($args) {
    if ( !isset($args['size']) ) $args['size']=40;
    $description = isset( $args['description'] ) ? "<p class=\"description\">{$args['description']}</p>": '';
    echo "<input id='{$args['option_id']}' name='".self::$id."[{$args['option_id']}]' size='{$args['size']}' type='text' value='".esc_attr( tinySocial::$options[$args['option_id']] )."' />{$description}";
  }

  // NUMBER TEXTBOX - Name: text - Arguments: size:int=40
  public static function number($args) {
    $options = '';
    if ( is_array($args) ) {
      foreach ($args as $key => $value) {
        if ( in_array( $key, array( 'option_id' ) ) ) {
          continue;
        }
        $options .= " {$key}=\"{$value}\"";
      }
    }
    echo "<input id='{$args['option_id']}' name='".self::$id."[{$args['option_id']}]' type='number' value='".tinySocial::$options[$args['option_id']]."'{$options}/>";
  }

  // PASSWORD-TEXTBOX - Name: password - Arguments: size:int=40
  public static function password($args) {
    if (!$args['size']) $args['size']=40;
    echo "<input id='{$args['option_id']}' name='".self::$id."[{$args['option_id']}]' size='{$args['size']}' type='password' value='".tinySocial::$options[$args['option_id']]."' />";
  }

  // RADIO-BUTTON - Name: plugin_options[option_set1]
  public static function radio($args) {
    $items = $args['values'];
    if (self::is_assoc($items)) {
      foreach($items as $key=>$item) {
        $checked = checked( $key, tinySocial::$options[$args['option_id']], false );
        echo "<label><input ".$checked." value='$key' name='".self::$id."[{$args['option_id']}]' type='radio' /> $item</label><br />";
      }
    } else {
      foreach($items as $item) {
        $checked = checked( $item, tinySocial::$options[$args['option_id']], false );
        echo "<label><input ".$checked." value='$item' name='".self::$id."[{$args['option_id']}]' type='radio' /> $item</label><br />";
      }
    }
  }
  // checklist - Name: plugin_options[option_set1]
  public static function checklist($args) {
    $items = $args['values'];
    if (self::is_assoc($items)) {
      foreach($items as $key=>$item) {
        if ( is_array( tinySocial::$options[$args['option_id']] ) ) {
          $checked = checked( in_array( $key, tinySocial::$options[$args['option_id']] ), true, false );
        } else {
          $checked = checked( true, false, false );
        }
        echo "<label><input ".$checked." value='$key' name='".self::$id."[{$args['option_id']}][]' type='checkbox' /> $item</label><br />";
      }
    } else {
      foreach($items as $item) {
        if ( is_array( tinySocial::$options[$args['option_id']] ) ) {
          $checked = checked( in_array( $item, tinySocial::$options[$args['option_id']] ), true, false );
        } else {
          $checked = checked( true, false, false );
        }
        echo "<label><input ".$checked." value='$item' name='".self::$id."[{$args['option_id']}][]' type='checkbox' /> $item</label><br />";
      }
    }
  }

  // Display the admin options page
  public static function page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
  ?>
    <div class="wrap">
      <div class="icon32" id="icon-page"><br></div>
      <h2><?php echo self::$title; ?></h2>
      <?php echo self::$description; ?>
      <form action="options.php" method="post">
      <?php settings_fields(self::$id); ?>
      <?php do_settings_sections(self::$file); ?>
      <p class="submit">
        <input name="submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
      </p>
      </form>
    </div>
  <?php
  }

  // Validate user data for some/all of your input fields
  public static function validate($input) {
    if ( !is_array($input['role']) ) {
      $input['role'] = array();
    }
    if ( !isset($input['ping']) || !$input['ping'] ) {
      $input['ping'] = 0;
    }
//      var_dump($input);
//    die();
    return $input; // return validated input
  }

}
