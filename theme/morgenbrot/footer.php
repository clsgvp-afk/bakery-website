<?php
$opt  = fn( $key, $default = '' ) => function_exists( 'morgenbrot_get_option' ) ? morgenbrot_get_option( $key, $default ) : $default;
$name = $opt( 'bakery_name', 'Bäckerei Morgenbrot' );
?>
<footer class="site-footer">
	<div class="shell footer-grid">
		<?php if ( $opt( 'footer_show_brand', '1' ) ) : ?>
			<div><a class="brand footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span><?php echo esc_html( $name ); ?></span></a><p><?php echo nl2br( esc_html( $opt( 'footer_description', 'Handwerklich gebacken. Jeden Morgen frisch.' ) ) ); ?></p></div>
		<?php endif; ?>
		<?php if ( $opt( 'footer_show_menu', '1' ) ) : ?>
			<nav aria-label="Footer-Navigation"><?php wp_nav_menu( array( 'theme_location'=>'footer', 'container'=>false, 'menu_class'=>'footer-menu', 'fallback_cb'=>'morgenbrot_fallback_menu', 'depth'=>1 ) ); ?></nav>
		<?php endif; ?>
		<?php if ( $opt( 'footer_show_contact', '1' ) ) : ?>
			<div><h2><?php echo esc_html( $opt( 'footer_heading', 'Besuchen Sie uns' ) ); ?></h2><p><?php echo nl2br( esc_html( $opt( 'footer_contact', 'Achern · Ortenau' ) ) ); ?><br><?php echo nl2br( esc_html( $opt( 'footer_hours', '' ) ) ); ?></p><a href="<?php echo esc_url( get_post_type_archive_link( 'bakery_location' ) ?: home_url( '/filialen/' ) ); ?>">Filialen ansehen →</a></div>
		<?php endif; ?>
	</div>
	<div class="shell footer-bottom">
		<p><?php echo esc_html( $opt( 'disclaimer', 'Fiktives Portfolio-Projekt – kein echtes Unternehmen.' ) ); ?></p>
		<p><?php echo esc_html( str_replace( '{year}', wp_date( 'Y' ), $opt( 'footer_copyright', '© {year} Bäckerei Morgenbrot' ) ) ); ?> · <a href="<?php echo esc_url( $opt( 'impressum_url' ) ?: home_url( '/impressum/' ) ); ?>">Impressum</a> · <a href="<?php echo esc_url( $opt( 'privacy_url' ) ?: home_url( '/datenschutz/' ) ); ?>">Datenschutz</a></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
