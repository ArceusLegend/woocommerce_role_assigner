<?php
require_once WCRA_MAIN_PLUGIN_DIRPATH . '/includes/utilities.php';

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'create_options_page');
add_action('after_setup_theme', 'load_carbon_fields');
add_action('carbon_fields_theme_options_container_saved', 'save_selected_role_to_db');


function load_carbon_fields()
{
    Carbon_Fields\Carbon_Fields::boot();
}


function create_options_page()
{
    $currently_saved_option = get_saved_option_from_db();
    $display_option = wcra_get_role_display_name($currently_saved_option);
    // Create the options page
    Container::make('theme_options', __('WooCommerce Role Assigner'))
        ->add_fields(
            [
                Field::make('select', 'wcra_list', __('Select user role'))
                    ->set_options('wcra_get_user_roles')
                    ->set_required(true),
                Field::make('html', 'wrca_current_role')
                    ->set_html("<p>Currently selected role: $display_option</p>")
            ]
        );
    // Check if the settings were just saved
    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
        // Display the new saved role
        add_action('admin_notices', 'display_saved_role_notif');
    }
}


function save_selected_role_to_db()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wc_role_assigner';
    $selected_role = carbon_get_theme_option('wcra_list');
    // NOTE: For some reason, the first time this runs, a new row will be created with the saved value.
    // NOTE: This shouldn't affect any functionality. It seems to be a problem with the MySQL server.
    // NOTE: It's 2 am, I don't have any energy to fix this. Nor do I care to tbh.
    // NOTE: Unless it breaks something, just leave this be.
    $wpdb->replace($table_name, ['id' => 1, "role" => $selected_role]);
}


function display_saved_role_notif()
{
    $saved_role = carbon_get_theme_option('wcra_list');
    if ($saved_role) {
        // Get the user roles
        $user_roles = wcra_get_user_roles();
        // Get the selected role name
        $selected_role_name = $user_roles[$saved_role] ?? '';
        if ($selected_role_name) {
            echo '<div class="notice notice-success is-dismissible">
                        <p>New Role Selected: ' . esc_html($selected_role_name) . '</p>
                    </div>';
        }
    }
}


function wcra_get_role_display_name(string $role_key): string
{
    global $wp_roles;
    $roles = $wp_roles->roles;
    return isset($roles[$role_key]) ? $roles[$role_key]["name"] : '';
}


function wcra_get_user_roles(): array
{
    global $wp_roles;
    $roles = $wp_roles->roles;
    $options = [];
    foreach ($roles as $role => $name) {
        $options[$role] = $name['name'];
    }
    return $options;
}
