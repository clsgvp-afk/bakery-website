<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/assets/css/category-corrections.css' ); ?>?v=<?php echo esc_attr( filemtime( get_template_directory() . '/assets/css/category-corrections.css' ) ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Zum Inhalt springen</a>
<?php
$name = function_exists( 'morgenbrot_get_option' ) ? morgenbrot_get_option( 'bakery_name', 'Bäckerei Morgenbrot' ) : 'Bäckerei Morgenbrot';
$logo = function_exists( 'morgenbrot_get_option' ) ? absint( morgenbrot_get_option( 'logo_id' ) ) : 0;
?>
<header class="site-header"><div class="shell header-inner">
	<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $name . ' – Startseite' ); ?>">
		<?php if ( $logo ) : ?>
			<?php echo wp_get_attachment_image( $logo, 'medium', false, array( 'alt' => $name ) ); ?>
		<?php else : ?>
			<svg aria-hidden="true" viewBox="0 0 64 64"><path d="M8 39c0-15 11-27 24-27s24 12 24 27c0 9-9 13-24 13S8 48 8 39Z"/><path d="m21 19 5 13m8-18 1 16m10-10-4 12"/></svg>
		<?php endif; ?>
		<span><?php echo esc_html( $name ); ?></span>
	</a>
	<nav id="primary-navigation" class="primary-navigation" aria-label="Hauptmenü"><?php wp_nav_menu( array( 'theme_location'=>'primary', 'container'=>false, 'menu_class'=>'nav-menu', 'fallback_cb'=>'morgenbrot_fallback_menu' ) ); ?></nav>
	<?php if ( ! function_exists( 'morgenbrot_get_option' ) || morgenbrot_get_option( 'show_header_cta', '1' ) ) : ?>
		<a class="button header-cta" href="<?php echo esc_url( function_exists( 'morgenbrot_get_option' ) ? morgenbrot_get_option( 'preorder_url', home_url( '/vorbestellen/' ) ) : home_url( '/vorbestellen/' ) ); ?>" <?php echo function_exists( 'morgenbrot_get_option' ) && morgenbrot_get_option( 'header_cta_new_tab' ) ? 'target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( function_exists( 'morgenbrot_get_option' ) ? morgenbrot_get_option( 'preorder_text', 'Vorbestellen' ) : 'Vorbestellen' ); ?></a>
	<?php endif; ?>
	<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation"><span class="screen-reader-text">Menü öffnen</span><i></i><i></i><i></i></button>
</div></header>
<?php morgenbrot_breadcrumbs(); ?>
