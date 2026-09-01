<?php
defined( 'ABSPATH' ) || exit;

function morgenbrot_register_promotions() {
	register_post_type( 'bakery_promotion', array(
		'labels' => array( 'name' => 'Aktionen', 'singular_name' => 'Aktion', 'add_new_item' => 'Aktion hinzufügen', 'edit_item' => 'Aktion bearbeiten' ),
		'public' => true, 'show_in_rest' => true, 'has_archive' => 'aktionen', 'rewrite' => array( 'slug' => 'aktionen' ),
		'menu_icon' => 'dashicons-megaphone', 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
	) );
}
add_action( 'init', 'morgenbrot_register_promotions' );

function morgenbrot_promotion_boxes() { add_meta_box( 'morgenbrot_promotion', 'Aktionsdetails', 'morgenbrot_promotion_box', 'bakery_promotion', 'normal', 'high' ); }
add_action( 'add_meta_boxes', 'morgenbrot_promotion_boxes' );
function morgenbrot_promotion_box( $post ) {
	wp_nonce_field( 'morgenbrot_promotion', 'morgenbrot_promotion_nonce' );
	$fields = array( 'short_text'=>'Kurztext', 'start'=>'Start (YYYY-MM-DD HH:MM)', 'end'=>'Ende (YYYY-MM-DD HH:MM)', 'discount'=>'Rabatttext', 'button_text'=>'Buttontext', 'button_url'=>'Buttonlink', 'product_ids'=>'Produkt-IDs (kommagetrennt)', 'location_ids'=>'Filial-IDs (kommagetrennt)' );
	foreach ( $fields as $key=>$label ) printf( '<p><label><strong>%1$s</strong><br><input class="widefat" name="mb_promo_%2$s" value="%3$s"></label></p>', esc_html($label), esc_attr($key), esc_attr(get_post_meta($post->ID,'_mb_promo_'.$key,true)) );
	$position=get_post_meta($post->ID,'_mb_promo_position',true)?:'both';
	echo '<p><label><strong>Anzeige</strong><br><select name="mb_promo_position"><option value="home" '.selected($position,'home',false).'>Startseite</option><option value="catalog" '.selected($position,'catalog',false).'>Sortiment</option><option value="both" '.selected($position,'both',false).'>Beide</option></select></label></p>';
	printf('<p><label><input type="checkbox" name="mb_promo_active" value="1" %s> Aktiv</label></p>',checked(get_post_meta($post->ID,'_mb_promo_active',true),'1',false));
}
function morgenbrot_save_promotion( $id ) {
	if ( ! isset($_POST['morgenbrot_promotion_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['morgenbrot_promotion_nonce'])),'morgenbrot_promotion') || (defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE) || ! current_user_can('edit_post',$id) ) return;
	foreach(array('short_text','start','end','discount','button_text','button_url','product_ids','location_ids') as $key){$v=isset($_POST['mb_promo_'.$key])?wp_unslash($_POST['mb_promo_'.$key]):'';update_post_meta($id,'_mb_promo_'.$key,'button_url'===$key?esc_url_raw($v):sanitize_text_field($v));}
	update_post_meta($id,'_mb_promo_position',in_array($_POST['mb_promo_position']??'',array('home','catalog','both'),true)?$_POST['mb_promo_position']:'both');
	update_post_meta($id,'_mb_promo_active',isset($_POST['mb_promo_active'])?'1':'');
}
add_action('save_post_bakery_promotion','morgenbrot_save_promotion');

function morgenbrot_active_promotions($position='home'){
	$now=current_time('timestamp'); $posts=get_posts(array('post_type'=>'bakery_promotion','posts_per_page'=>-1,'orderby'=>'menu_order','order'=>'ASC'));
	return array_values(array_filter($posts,function($p)use($position,$now){$active=get_post_meta($p->ID,'_mb_promo_active',true);$where=get_post_meta($p->ID,'_mb_promo_position',true)?:'both';$start=strtotime((string)get_post_meta($p->ID,'_mb_promo_start',true));$end=strtotime((string)get_post_meta($p->ID,'_mb_promo_end',true));return '1'===$active&&('both'===$where||$position===$where)&&(!$start||$start<=$now)&&(!$end||$end>=$now); }));
}
