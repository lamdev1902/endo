<?php
/**
 * Plugin Name: Exercise UI
 * Plugin URI: https://endomondo.com/
 * Description: Exercise Database
 * Author: Astronet
 * Author URI: https://endomondo.com/
 * Version: 1.1
 * Requires at least: 5.9
 * Requires PHP: 5.6
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
  exit;
}

// Define plugin constants
define('EXERCISE_VERSION', '1.0');
define('MY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MY_PLUGIN_URL', plugin_dir_url(__FILE__));

include_once MY_PLUGIN_DIR . 'db/exercise-installer.php';
include_once MY_PLUGIN_DIR . 'api/survey_api.php';
include_once MY_PLUGIN_DIR . 'api/user_api.php';
include_once MY_PLUGIN_DIR . 'api/forgot_password_api.php';
include_once MY_PLUGIN_DIR . 'api/update_password_api.php';
include_once MY_PLUGIN_DIR . 'api/update_survey_api.php';
include_once MY_PLUGIN_DIR . 'api/plan_api.php';
include_once MY_PLUGIN_DIR . 'api/social_user.php';
include_once MY_PLUGIN_DIR . 'api/report_api.php';



/**
 * Load our main menu.
 *
 * Submenu items added in version 1.1.0
 *
 * @since 0.1.0
 *
 * @internal
 */
function exerciseui_plugin_menu()
{
  global $muscle_page, $equipment_page, $exercise_page;
  /**
   * Filters the required capability to manage EXERCISEUI settings.
   *
   * @since 1.3.0
   *
   * @param string $value Capability required.
   */
  $capability = apply_filters('exerciseui_required_capabilities', 'manage_options');
  $parent_slug = 'exerciseui_main_menu';

  add_menu_page(
    esc_html__('Exercise UI', 'exercise-ui'),
    esc_html__('EXERCISE UI', 'exercise-ui'),
    '',
    $parent_slug,
    'manage_options',
    ''
  );

  $muscle_page = add_submenu_page($parent_slug, esc_html__('Muscle Anatomy', 'exercise-ui'), esc_html__('Muscle Anatomy', 'exercise-ui'), $capability, 'exerciseui_manage_muscle', 'exerciseui_manage_muscle');
  $equipment_page = add_submenu_page($parent_slug, esc_html__('Equipment', 'exercise-ui'), esc_html__('Equipment', 'exercise-ui'), $capability, 'exerciseui_manage_equipment', 'exerciseui_manage_equipment');
  $exercise_page = add_submenu_page($parent_slug, esc_html__('Exercise', 'exercise-ui'), esc_html__('Exercise', 'exercise-ui'), $capability, 'exerciseui_manage_exercise', 'exerciseui_manage_exercise');
  $training_type_page = add_submenu_page($parent_slug, esc_html__('Training Type', 'exercise-ui'), esc_html__('Training Type', 'exercise-ui'), $capability, 'exerciseui_manage_training_type', 'exerciseui_manage_training_type');
  $muscle_type_page = add_submenu_page($parent_slug, esc_html__('Muscle Type', 'exercise-ui'), esc_html__('Muscle Type', 'exercise-ui'), $capability, 'exerciseui_manage_muscle_type', 'exerciseui_manage_muscle_type');
  $plan_page = add_submenu_page($parent_slug, esc_html__('Exercise Plan', 'exercise-ui'), esc_html__('Exercise Plan', 'exercise-ui'), $capability, 'exerciseui_manage_plan', 'exerciseui_manage_plan');

  add_action("load-$muscle_page", "muscle_screen_options");
  add_action("load-$equipment_page", "equipment_screen_options");
  add_action("load-$exercise_page", "exercise_screen_options");
  add_action("load-$training_type_page", "training_type_screen_options");
  add_action("load-$muscle_type_page", "muscle_type_screen_options");
  add_action("load-$plan_page", "plan_screen_options");
}
add_action('admin_menu', 'exerciseui_plugin_menu');

/**
 * Fire our CPTUI Loaded hook.
 *
 * @since 1.3.0
 *
 * @internal Use `cptui_loaded` hook.
 */
function exerciseui_loaded()
{

  /**
   * Fires upon plugins_loaded WordPress hook.
   *
   * CPTUI loads its required files on this hook.
   *
   * @since 1.3.0
   */
  do_action('exerciseui_loaded');
}
add_action('plugins_loaded', 'exerciseui_loaded');

/**
 * Load our submenus.
 *
 * @since 1.0.0
 *
 * @internal
 */
function exerciseui_create_submenus()
{
  require_once plugin_dir_path(__FILE__) . 'action/manage_exercise.php';

  require_once plugin_dir_path(__FILE__) . 'inc/muscle.php';
  require_once plugin_dir_path(__FILE__) . 'inc/exercise.php';
  require_once plugin_dir_path(__FILE__) . 'inc/plan.php';
  require_once plugin_dir_path(__FILE__) . 'inc/equipment.php';
  require_once plugin_dir_path(__FILE__) . 'inc/training-type.php';
  require_once plugin_dir_path(__FILE__) . 'inc/muscle-type.php';

  // if ( defined( 'WP_CLI' ) && WP_CLI ) {
  // 	require_once plugin_dir_path( __FILE__ ) . 'inc/wp-cli.php';
  // }

}
add_action('exerciseui_loaded', 'exerciseui_create_submenus');


add_action('admin_print_footer_scripts', function () {
  ?>
  <script type="text/javascript">
    /* <![CDATA[ */
    (function ($) {
      $(function () {
        var checkboxAdded = false;

        $(document).on("wplink-open", function () {
          if (!checkboxAdded) {
            $("#link-options").append(
              $("<div style='margin-left: 5px'></div>").addClass("link-nofollow").html(
                $("<label></label>").html([
                  $("<span></span>"),
                  $("<input></input>").attr({ "type": "checkbox", "id": "wp-link-nofollow" }),
                  " Add no follow to link"
                ])
              )
            );
            checkboxAdded = true;
          }

          if (wpLink && typeof (wpLink.getAttrs) == "function") {
            var originalGetAttrs = wpLink.getAttrs;
            wpLink.getAttrs = function () {
              wpLink.correctURL();
              var attrs = originalGetAttrs.call(this);
              attrs.rel = $("#wp-link-nofollow").prop("checked") ? "nofollow" : null;
              return attrs;
            };
          }

          $("#wp-link-nofollow").prop("checked", false);
        });

        $(document).on("wplink-submit", function () {
          var editor = window.wpActiveEditor;
          var checkboxId = "wp-link-nofollow";
          var relValue = $("#" + checkboxId).prop("checked") ? "nofollow" : null;

          if (relValue) {
            var content = tinymce.get(editor).selection.getContent({ format: "html" });
            content = content.replace(/<a /, '<a rel="nofollow" ');
            tinymce.get(editor).selection.setContent(content);
          }
        });
      });
    })(jQuery);
    /* ]]> */
  </script>
  <?php
}, 45);
/**
 * Load style
 */
function atn_enqueue_script()
{
  wp_enqueue_script('jquery');

  wp_enqueue_media();

  wp_enqueue_style('jquery-ui-css', '//cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css');
  wp_enqueue_script('jquery-ui-js', '//cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', array('jquery'), null, true);
  wp_enqueue_script(
    'searchable-option-list-js',
    get_template_directory_uri() . '/assets/searchable/jquery.multiselect.js',
    array('jquery'),
    null,
    true
  );

  wp_enqueue_style(
    'searchable-option-list-css',
    get_template_directory_uri() . '/assets/searchable/jquery.multiselect.css',
    array(),
    null
  );
  wp_enqueue_style('exercise-style', plugin_dir_url(__FILE__) . 'assets/css/exercise-main.css', array(), '1.0.4', 'all');
  wp_enqueue_script('exercise-script', plugin_dir_url(__FILE__) . 'assets/js/function.js', array('jquery'), '1.1.1', true);
}

add_action('admin_enqueue_scripts', 'atn_enqueue_script');

register_activation_hook(__FILE__, ['Exercise_Installer', 'activate']);


function gutenberg_exercise_block()
{
  wp_register_script(
    'exercise-block',
    plugins_url('assets/js/exercise-block.js', __FILE__),
    array('wp-blocks', 'wp-element', 'wp-components', 'wp-data')
  );

  global $wpdb;


  $exercise_names = $wpdb->get_col("SELECT name FROM {$wpdb->prefix}exercise");

  wp_localize_script('exercise-block', 'exerciseNames', $exercise_names);

  register_block_type('gutenberg-exercise-block/exercise-list', array(
    'editor_script' => 'exercise-block',
  ));
}
add_action('init', 'gutenberg_exercise_block');


function custom_add_exercise_name_field()
{
  add_meta_box(
    'exercise_name_meta_box',
    'Exercise Name',
    'custom_render_exercise_name_field',
    'exercise',
    'normal',
    'default'
  );

  add_meta_box(
    'best_exercise_meta_box',
    'Best Exercise Option',
    'custom_render_best_exercise_option_field',
    'best_exercise',
    'normal',
    'default'
  );

  add_meta_box(
    'exercise_meta_box',
    'All Exercise Option',
    'custom_render_all_exercise_option_field',
    'best_exercise',
    'normal',
    'default'
  );
}
add_action('add_meta_boxes', 'custom_add_exercise_name_field');

function custom_render_exercise_name_field($post)
{
  global $wpdb;

  $exercise_name = get_post_meta($post->ID, 'exercise_name', true);
  $all_exercise_names = $wpdb->get_results(
    "SELECT * From {$wpdb->prefix}exercise",
    ARRAY_A
  );
  ?>
  <label for="exercise_name">Select Exercise Name:</label>
  <select id="exercise_name" name="exercise_name">
    <option value="" <?php $exercise_name ? "" : "selected"; ?>>Select an exercise</option>
    <?php foreach ($all_exercise_names as $exercise): ?>
      <option value="<?php echo esc_attr($exercise['id']); ?>" <?php selected($exercise['id'], $exercise_name); ?>>
        <?php echo $exercise['name']; ?>
      </option>
    <?php endforeach; ?>
  </select>
  <?php
}

function custom_render_best_exercise_option_field($post)
{
  global $wpdb;

  $exercises = get_post_meta($post->ID, 'best_exercise_list', true) ? explode(',', get_post_meta($post->ID, 'best_exercise_list', true)) : [];
  $all_exercise_names = $wpdb->get_results(
    "SELECT * From {$wpdb->prefix}exercise",
    ARRAY_A
  );

  ?>
  <div class="filter-section">
    <div class="best-exericse">
      <label for="bestEx">Select Best Exercise:</label>
      <select id="bestEx" name="best_exercise_list[]" multiple>
        <?php foreach ($all_exercise_names as $exercise): ?>
          <option value="<?php echo esc_attr($exercise['id']); ?>" <?php selected(in_array($exercise['id'], $exercises), true); ?>>
            <?php echo $exercise['name']; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button class='clearAll'>Clear Best Exercise</button>
    </div>
  </div>
  <?php
}

function custom_render_all_exercise_option_field($post)
{
  global $wpdb;

  $mt = get_post_meta($post->ID, 'all_mt_list', true) ? explode(',', get_post_meta($post->ID, 'all_mt_list', true)) : [];
  $ma = get_post_meta($post->ID, 'all_ma_list', true) ? explode(',', get_post_meta($post->ID, 'all_ma_list', true)) : [];
  $eq = get_post_meta($post->ID, 'all_eq_list', true) ? explode(',', get_post_meta($post->ID, 'all_eq_list', true)) : [];
  $ex = get_post_meta($post->ID, 'all_ex_list', true) ? explode(',', get_post_meta($post->ID, 'all_ex_list', true)) : [];


  $all_exercise_names = $wpdb->get_results(
    "SELECT * From {$wpdb->prefix}exercise",
    ARRAY_A
  );

  $queryE = "
    SELECT DISTINCT eq.id, eq.name
    FROM wp_exercise_equipment AS eq
    WHERE EXISTS (
        SELECT 1
        FROM wp_exercise_equipment_option AS eo
        INNER JOIN {$wpdb->prefix}exercise AS e ON e.id = eo.exercise_id
        WHERE eo.equipment_id = eq.id
        AND e.slug IS NOT NULL
        AND e.slug != ''
        AND e.active = 1
    );
";

  $queryMA = "
    SELECT DISTINCT ma.id, ma.name
    FROM {$wpdb->prefix}exercise_muscle_anatomy AS ma
    INNER JOIN {$wpdb->prefix}exercise_primary_option AS epo ON epo.muscle_id = ma.id
    INNER JOIN {$wpdb->prefix}exercise AS e ON e.id = epo.exercise_id
    WHERE e.slug IS NOT NULL
    AND e.slug != ''
    AND e.active = 1
";

  $muscle_anatomy = $wpdb->get_results($queryMA, ARRAY_A);

  $equipments = $wpdb->get_results($queryE, ARRAY_A);

  $queryMS = "
    SELECT DISTINCT mt.id, mt.name
    FROM {$wpdb->prefix}exercise_muscle_type AS mt
    WHERE EXISTS (
        SELECT 1
        FROM {$wpdb->prefix}exercise_muscle_anatomy AS ma
        INNER JOIN {$wpdb->prefix}exercise_primary_option AS epo ON epo.muscle_id = ma.id
        INNER JOIN {$wpdb->prefix}exercise AS e ON e.id = epo.exercise_id
        WHERE ma.type_id = mt.id
        AND e.slug IS NOT NULL
        AND e.slug != ''
        AND e.active = 1
    );
";

  $muscle_types = $wpdb->get_results($queryMS, ARRAY_A);

  $check = 1;

  if(!$ex && ($mt || $eq || $ma)) {
    $check = 2;
  }

  ?>
  <div class="filter-section">
    <div class="filter-all">
      <div class="label-filter">
        <p class="<?= $check == 1 ? "active" : ''?>">Manual</p>
        <p class="<?= $check == 2 ? "active" : ''?>">Auto</p>
      </div>
      <div class="ft-ex ft <?= $check != 1 ? "hide" : ''?>">
        <div class="exercise">
          <label for="speEx">Select Exercise:</label>
          <select id="speEx" name="all_ex_list[]" multiple>
            <?php foreach ($all_exercise_names as $exercise): ?>
              <option value="<?php echo esc_attr($exercise['id']); ?>" <?php selected(in_array($exercise['id'], $ex), true); ?>>
                <?php echo $exercise['name']; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <button class='clearAll'>Clear Exercise List</button>
        </div>
      </div>
      <div class="ft-auto ft <?= $check != 2 ? "hide" : ''?>">
        <div class="mt">
          <label for="mt">Select Muscle Type:</label>
          <select id="mt" name="all_mt_list[]" multiple>
            <?php foreach ($muscle_types as $idMt): ?>
              <option value="<?php echo esc_attr($idMt['id']); ?>" <?php selected(in_array($idMt['id'], $mt), true); ?>>
                <?php echo $idMt['name']; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <button class='clearAll'>Clear Muscle Type</button>
        </div>
        <div class="ma">
          <label for="ma">Select Muscle Anatomy:</label>
          <select id="ma" name="all_ma_list[]" multiple>
            <?php foreach ($muscle_anatomy as $idMa): ?>
              <option value="<?php echo esc_attr($idMa['id']); ?>" <?php selected(in_array($idMa['id'], $ma), true); ?>>
                <?php echo $idMa['name']; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <button class='clearAll'>Clear Muscle Anatomy</button>
        </div>
        <div class="eq">
          <label for="eq">Select Equipment:</label>
          <select id="eq" name="all_eq_list[]" multiple>
            <?php foreach ($equipments as $idEq): ?>
              <option value="<?php echo esc_attr($idEq['id']); ?>" <?php selected(in_array($idEq['id'], $eq), true);
                 ; ?>>
                <?php echo $idEq['name']; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <button class='clearAll'>Clear Equipment</button>
        </div>
      </div>
    </div>
  </div>
  <?php
}



function custom_save_exercise_name_field($post_id)
{
  if (isset($_POST['exercise_name'])) {
    update_post_meta($post_id, 'exercise_name', $_POST['exercise_name']);
  }
}
add_action('save_post_exercise', 'custom_save_exercise_name_field');

function custom_save_best_exercise_field($post_id)
{
  $options = [
    'best_exercise_list',
    'all_mt_list',
    'all_ma_list',
    'all_eq_list',
    'all_ex_list'
  ];

  foreach ($options as $op) {
    if (isset($_POST[$op])) {
      update_post_meta($post_id, $op, implode(',', $_POST[$op]));
    } else {
      update_post_meta($post_id, $op, '');
    }
  }

}
add_action('save_post_best_exercise', 'custom_save_best_exercise_field');

function handle_scheduled_post_meta($ID)
{
    custom_save_best_exercise_field($ID);
}
add_action('future_best_exercise', 'handle_scheduled_post_meta', 10, 3);

function publish_ex_post_meta($ID)
{
    custom_save_best_exercise_field($ID);
}
add_action('publish_best_exercise', 'publish_ex_post_meta', 10, 3);

new Exercise_Survey_API();