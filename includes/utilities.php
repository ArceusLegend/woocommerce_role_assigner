<?php
// Helpful vars
global $default_role_sys_name;
global $default_role_name;
global $default_role_privs;
global $table_name_suffix;
$default_role_sys_name = 'pro_customer';
$default_role_name = 'Pro Customer';
$default_role_privs = [
    'read' => true,
    'delete_posts' => false,
    'delete_published_posts' => false,
    'edit_posts' => false,
    'publish_posts' => false,
    'edit_published_posts' => false,
    'upload_files' => false,
    'moderate_comments' => false,
];
$table_name_suffix = "wc_role_assigner";


function wcra_setup_default_role()
{
    // Create the default role
    global $default_role_sys_name;
    global $default_role_name;
    global $default_role_privs;
    add_role($default_role_sys_name, $default_role_name, $default_role_privs);
    // Add it to the db table that was created before this function
    global $wpdb;
    global $table_name_suffix;
    $table_name = $wpdb->prefix . $table_name_suffix;
    // Check that it doesn't exist already.
    $existing_role = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT role FROM $table_name WHERE role = %s",
            $default_role_sys_name
        )
    );
    if (!$existing_role) {
        $wpdb->insert($table_name, ['role' => $default_role_sys_name]);
    }
}
add_action('init', 'wcra_setup_default_role');

function wcra_setup_db_table()
{
    global $wpdb;
    global $table_name_suffix;
    $table_name = $wpdb->prefix . $table_name_suffix;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        role varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

function wcra_activate()
{
    wcra_setup_db_table();
    wcra_setup_default_role();
}
register_activation_hook(WCRA_MAIN_PLUGIN_FILEPATH, 'wcra_activate');

function wcra_deactivate()
{
    global $default_role_sys_name;
    remove_role($default_role_sys_name);
    // Remove DB table
    global $wpdb;
    global $table_name_suffix;
    $table_name = $wpdb->prefix . $table_name_suffix;
    $wpdb->query("DROP TABLE IF EXISTS $table_name;");
}
register_deactivation_hook(WCRA_MAIN_PLUGIN_FILEPATH, 'wcra_deactivate');


function get_saved_option_from_db(): string
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wc_role_assigner';
    $saved_option = $wpdb->get_var("SELECT role FROM $table_name LIMIT 1");
    return $saved_option ?? '';
}
