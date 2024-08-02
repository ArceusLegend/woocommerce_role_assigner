<?php
require_once WCRA_MAIN_PLUGIN_DIRPATH . '/includes/utilities.php';
add_action('woocommerce_order_status_completed', 'wcra_add_user_role');

function wcra_add_user_role($order_id)
{
    $role_to_assign = get_saved_option_from_db();
    $user_id = wc_get_order($order_id)->get_user_id();
    $user = get_userdata($user_id);
    if (!$user->has_role($role_to_assign)) {
        $user->add_role($role_to_assign);
    }
}
