<?php
/**
 * Plugin Name: Morgenbrot Core
 * Description: Products, locations, enquiries and demo tools for the Morgenbrot portfolio.
 * Version: 2.0.0
 * Requires PHP: 8.0
 * Text Domain: morgenbrot-core
 */
defined( 'ABSPATH' ) || exit;
define( 'MORGENBROT_CORE_VERSION', '2.0.0' );
define( 'MORGENBROT_CORE_FILE', __FILE__ );
define( 'MORGENBROT_CORE_DIR', plugin_dir_path( __FILE__ ) );
require_once MORGENBROT_CORE_DIR . 'includes/content-types.php';
require_once MORGENBROT_CORE_DIR . 'includes/helpers.php';
require_once MORGENBROT_CORE_DIR . 'includes/forms.php';
require_once MORGENBROT_CORE_DIR . 'includes/demo.php';
require_once MORGENBROT_CORE_DIR . 'includes/admin-settings.php';
require_once MORGENBROT_CORE_DIR . 'includes/home-content.php';
require_once MORGENBROT_CORE_DIR . 'includes/promotions.php';
require_once MORGENBROT_CORE_DIR . 'includes/seo-roles.php';
require_once MORGENBROT_CORE_DIR . 'includes/location-images.php';
function morgenbrot_core_activate() { morgenbrot_register_content_types(); morgenbrot_register_promotions(); morgenbrot_register_roles(); flush_rewrite_rules(); }
register_activation_hook( __FILE__, 'morgenbrot_core_activate' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
