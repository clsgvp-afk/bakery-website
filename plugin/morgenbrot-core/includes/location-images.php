<?php
defined( 'ABSPATH' ) || exit;

/** Import distinct demo-location photos once and keep them editable as featured images. */
function morgenbrot_install_location_images() {
	if ( '2' === get_option( 'morgenbrot_location_images_version' ) ) return;
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$base = get_template_directory() . '/assets/images/locations/';
	$locations = array(
		'Filiale Achern Zentrum' => 'achern-zentrum.png',
		'Filiale Oberachern' => 'oberachern-v2.png',
		'Morgenbrot Mobil Ortenau' => 'ortenau-stadt-v2.png',
		'Filiale Ortenau Stadt' => 'ortenau-stadt-v2.png',
	);
	$complete = true;
	foreach ( $locations as $title => $filename ) {
		$query = new WP_Query( array( 'post_type'=>'bakery_location', 'post_status'=>'any', 'title'=>$title, 'posts_per_page'=>1, 'no_found_rows'=>true ) );
		if ( ! $query->posts || ! file_exists( $base . $filename ) ) { $complete=false; continue; }
		$post_id = $query->posts[0]->ID;
		$existing = get_posts( array( 'post_type'=>'attachment', 'post_status'=>'inherit', 'meta_key'=>'_morgenbrot_location_photo', 'meta_value'=>$filename, 'posts_per_page'=>1 ) );
		if ( $existing ) { set_post_thumbnail( $post_id, $existing[0]->ID ); continue; }
		$tmp = wp_tempnam( $filename );
		if ( ! $tmp || ! copy( $base . $filename, $tmp ) ) { $complete=false; continue; }
		$attachment_id = media_handle_sideload( array( 'name'=>$filename, 'tmp_name'=>$tmp ), $post_id, $title );
		if ( is_wp_error( $attachment_id ) ) { @unlink($tmp); $complete=false; continue; }
		update_post_meta( $attachment_id, '_morgenbrot_location_photo', $filename );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title . ' – fiktive Demonstrationsfiliale' );
		set_post_thumbnail( $post_id, $attachment_id );
	}
	if ( $complete ) update_option( 'morgenbrot_location_images_version', '2', false );
}
add_action( 'init', 'morgenbrot_install_location_images', 30 );

/** Keep the third demo branch consistent with its permanent storefront photo. */
function morgenbrot_rename_ortenau_location() {
	if ( '1' === get_option( 'morgenbrot_ortenau_name_20260816' ) ) return;
	$posts = get_posts( array( 'post_type'=>'bakery_location', 'post_status'=>'any', 's'=>'Morgenbrot Mobil Ortenau', 'posts_per_page'=>5 ) );
	foreach ( $posts as $post ) {
		if ( 'Morgenbrot Mobil Ortenau' === $post->post_title ) wp_update_post( array( 'ID'=>$post->ID, 'post_title'=>'Filiale Ortenau Stadt' ) );
	}
	update_option( 'morgenbrot_ortenau_name_20260816', '1', false );
}
add_action( 'init', 'morgenbrot_rename_ortenau_location', 31 );
