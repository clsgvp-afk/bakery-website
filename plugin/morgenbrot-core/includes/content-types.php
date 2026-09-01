<?php
defined( 'ABSPATH' ) || exit;

function morgenbrot_register_content_types() {
	register_post_type( 'bakery_product', array(
		'labels' => array( 'name' => 'Sortiment', 'singular_name' => 'Produkt', 'add_new_item' => 'Produkt hinzufügen', 'edit_item' => 'Produkt bearbeiten', 'new_item' => 'Neues Produkt', 'view_item' => 'Produkt ansehen', 'search_items' => 'Produkte durchsuchen', 'not_found' => 'Keine Produkte gefunden' ),
		'public' => true, 'show_in_rest' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'sortiment', 'with_front' => false ), 'menu_icon' => 'dashicons-food', 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
	) );
	register_taxonomy( 'bakery_product_category', 'bakery_product', array( 'labels' => array( 'name' => 'Produktkategorien', 'singular_name' => 'Produktkategorie' ), 'public' => true, 'hierarchical' => true, 'show_in_rest' => true, 'rewrite' => array( 'slug' => 'produktkategorie' ) ) );
	register_taxonomy( 'bakery_allergen', 'bakery_product', array( 'labels' => array( 'name' => 'Allergene', 'singular_name' => 'Allergen' ), 'public' => true, 'hierarchical' => false, 'show_in_rest' => true, 'rewrite' => array( 'slug' => 'allergen' ) ) );
	register_post_type( 'bakery_location', array(
		'labels' => array( 'name' => 'Filialen', 'singular_name' => 'Filiale', 'add_new_item' => 'Filiale hinzufügen', 'edit_item' => 'Filiale bearbeiten', 'view_item' => 'Filiale ansehen', 'not_found' => 'Keine Filialen gefunden' ),
		'public' => true, 'show_in_rest' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'filialen', 'with_front' => false ), 'menu_icon' => 'dashicons-location-alt', 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
	) );
	register_post_type( 'bakery_preorder', array( 'labels' => array( 'name' => 'Vorbestellungen', 'singular_name' => 'Vorbestellung' ), 'public' => false, 'show_ui' => true, 'show_in_rest' => false, 'menu_icon' => 'dashicons-clipboard', 'supports' => array( 'title' ), 'capability_type' => 'post' ) );
	register_post_type( 'bakery_contact', array( 'labels' => array( 'name' => 'Kontaktanfragen', 'singular_name' => 'Kontaktanfrage' ), 'public' => false, 'show_ui' => true, 'show_in_rest' => false, 'menu_icon' => 'dashicons-email-alt', 'supports' => array( 'title' ), 'capability_type' => 'post' ) );
}
add_action( 'init', 'morgenbrot_register_content_types' );

function morgenbrot_product_fields() {
	return array(
		'sku' => array( 'label' => 'Artikelnummer (SKU)', 'type' => 'text' ), 'gallery_ids' => array( 'label' => 'Galerie', 'type' => 'gallery' ),
		'price' => array( 'label' => 'Regulärer Preis (€)', 'type' => 'decimal' ), 'sale_price' => array( 'label' => 'Aktionspreis (€)', 'type' => 'decimal' ), 'price_unit' => array( 'label' => 'Preiseinheit', 'type' => 'text' ), 'weight' => array( 'label' => 'Gewicht', 'type' => 'text' ),
		'ingredients' => array( 'label' => 'Zutaten', 'type' => 'textarea' ), 'availability' => array( 'label' => 'Verfügbarkeitshinweis', 'type' => 'text' ), 'stock_quantity' => array( 'label' => 'Verfügbare Menge', 'type' => 'integer' ), 'availability_status' => array( 'label' => 'Verfügbarkeitsstatus', 'type' => 'select', 'options' => array( 'in_stock' => 'Verfügbar', 'limited' => 'Begrenzt verfügbar', 'out_of_stock' => 'Nicht verfügbar' ) ),
		'vegan' => array( 'label' => 'Vegan', 'type' => 'checkbox' ), 'vegetarian' => array( 'label' => 'Vegetarisch', 'type' => 'checkbox' ), 'seasonal' => array( 'label' => 'Saisonal', 'type' => 'checkbox' ), 'featured' => array( 'label' => 'Empfohlen', 'type' => 'checkbox' ),
	);
}
function morgenbrot_location_fields() {
	$fields = array( 'street' => array( 'label' => 'Demo-Straße', 'type' => 'text' ), 'postal_code' => array( 'label' => 'PLZ', 'type' => 'text' ), 'city' => array( 'label' => 'Ort', 'type' => 'text' ), 'phone' => array( 'label' => 'Telefon', 'type' => 'text' ), 'email' => array( 'label' => 'E-Mail', 'type' => 'email' ), 'latitude' => array( 'label' => 'Breitengrad', 'type' => 'coordinate' ), 'longitude' => array( 'label' => 'Längengrad', 'type' => 'coordinate' ), 'temporarily_closed' => array( 'label' => 'Vorübergehend geschlossen', 'type' => 'checkbox' ), 'parking' => array( 'label' => 'Parkplätze', 'type' => 'checkbox' ), 'accessible' => array( 'label' => 'Barrierefreier Eingang', 'type' => 'checkbox' ), 'services' => array( 'label' => 'Weitere Services', 'type' => 'textarea' ) );
	foreach ( array( 'monday' => 'Montag', 'tuesday' => 'Dienstag', 'wednesday' => 'Mittwoch', 'thursday' => 'Donnerstag', 'friday' => 'Freitag', 'saturday' => 'Samstag', 'sunday' => 'Sonntag' ) as $day => $label ) $fields[ 'hours_' . $day ] = array( 'label' => $label . ' (z. B. 06:00-12:00, 13:00-18:00)', 'type' => 'hours' );
	return $fields;
}

function morgenbrot_add_meta_boxes() {
	add_meta_box( 'morgenbrot_product_data', 'Produktdaten', 'morgenbrot_render_product_box', 'bakery_product', 'normal', 'high' );
	add_meta_box( 'morgenbrot_location_data', 'Filialdaten', 'morgenbrot_render_location_box', 'bakery_location', 'normal', 'high' );
	add_meta_box( 'morgenbrot_preorder_data', 'Anfragedaten', 'morgenbrot_render_preorder_box', 'bakery_preorder', 'normal', 'high' );
	add_meta_box( 'morgenbrot_contact_data', 'Kontaktdaten', 'morgenbrot_render_contact_box', 'bakery_contact', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'morgenbrot_add_meta_boxes' );

function morgenbrot_render_fields( $post, $fields ) {
	wp_nonce_field( 'morgenbrot_save_' . $post->post_type, 'morgenbrot_meta_nonce' );
	echo '<div class="morgenbrot-admin-fields">';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, '_morgenbrot_' . $key, true ); $type = $field['type'];
		echo '<div class="morgenbrot-admin-field"><label><strong>' . esc_html( $field['label'] ) . '</strong></label>';
		if ( 'checkbox' === $type ) printf( '<label><input type="checkbox" name="morgenbrot_%1$s" value="1" %2$s> Ja</label>', esc_attr( $key ), checked( $value, '1', false ) );
		elseif ( 'textarea' === $type || 'hours' === $type ) printf( '<textarea class="widefat" rows="%1$d" name="morgenbrot_%2$s">%3$s</textarea>', 'textarea' === $type ? 4 : 2, esc_attr( $key ), esc_textarea( $value ) );
		elseif ( 'select' === $type ) { echo '<select class="widefat" name="morgenbrot_' . esc_attr( $key ) . '">'; foreach ( $field['options'] as $option => $label ) printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $option ), selected( $value, $option, false ), esc_html( $label ) ); echo '</select>'; }
		elseif ( 'gallery' === $type ) { echo '<input class="widefat morgenbrot-gallery-ids" type="hidden" name="morgenbrot_gallery_ids" value="' . esc_attr( $value ) . '"><div class="morgenbrot-gallery-preview">'; foreach ( array_filter( array_map( 'absint', explode( ',', (string) $value ) ) ) as $image_id ) echo wp_get_attachment_image( $image_id, 'thumbnail' ); echo '</div><button type="button" class="button morgenbrot-select-gallery">Bilder auswählen</button> <button type="button" class="button-link-delete morgenbrot-clear-gallery">Galerie leeren</button>'; }
		else printf( '<input class="widefat" type="%1$s" step="%2$s" name="morgenbrot_%3$s" value="%4$s">', in_array( $type, array( 'decimal', 'integer', 'coordinate' ), true ) ? 'number' : ( 'email' === $type ? 'email' : 'text' ), 'integer' === $type ? '1' : '0.000001', esc_attr( $key ), esc_attr( $value ) );
		echo '</div>';
	} echo '</div>';
}
function morgenbrot_render_product_box( $post ) { morgenbrot_render_fields( $post, morgenbrot_product_fields() ); echo '<p><em>SKU muss eindeutig sein. Preise bitte als Zahl eingeben, z. B. 4,80 oder 4.80.</em></p>'; }
function morgenbrot_render_location_box( $post ) { morgenbrot_render_fields( $post, morgenbrot_location_fields() ); echo '<p><em>Alle Adress- und Koordinatendaten sind als Demonstrationsdaten zu kennzeichnen. Keine privaten Wohnadressen verwenden.</em></p>'; }
function morgenbrot_render_preorder_box( $post ) { wp_nonce_field( 'morgenbrot_save_bakery_preorder', 'morgenbrot_meta_nonce' ); $data = (array) get_post_meta( $post->ID, '_morgenbrot_order', true ); foreach ( array( 'name'=>'Name','email'=>'E-Mail','phone'=>'Telefon','location_name'=>'Filiale','pickup'=>'Abholung','items_text'=>'Positionen','comment'=>'Kommentar' ) as $key=>$label ) printf( '<p><strong>%s:</strong><br>%s</p>', esc_html($label), nl2br(esc_html($data[$key]??'')) ); $status=get_post_meta($post->ID,'_morgenbrot_status',true); echo '<p><label><strong>Status</strong><br><select name="morgenbrot_status">'; foreach(array('new'=>'New','confirmed'=>'Confirmed','preparation'=>'In Preparation','ready'=>'Ready','completed'=>'Completed','cancelled'=>'Cancelled')as$key=>$label)printf('<option value="%s" %s>%s</option>',esc_attr($key),selected($status,$key,false),esc_html($label)); echo'</select></label></p>'; printf('<p><label><strong>Interne Notiz</strong><textarea class="widefat" rows="4" name="morgenbrot_internal_note">%s</textarea></label></p>',esc_textarea(get_post_meta($post->ID,'_morgenbrot_internal_note',true))); }
function morgenbrot_render_contact_box( $post ) { foreach ( (array) get_post_meta( $post->ID, '_morgenbrot_contact', true ) as $key=>$value ) printf('<p><strong>%s:</strong><br>%s</p>',esc_html(ucfirst($key)),nl2br(esc_html($value))); }

function morgenbrot_sanitize_decimal( $value ) { $value = str_replace( array( '€', ' ' ), '', (string) $value ); $value = str_replace( ',', '.', $value ); return is_numeric( $value ) ? number_format( max( 0, (float) $value ), 2, '.', '' ) : ''; }
function morgenbrot_sku_exists( $sku, $exclude_id ) { $ids = get_posts( array( 'post_type'=>'bakery_product','post_status'=>'any','fields'=>'ids','posts_per_page'=>1,'post__not_in'=>array($exclude_id),'meta_key'=>'_morgenbrot_sku','meta_value'=>$sku ) ); return ! empty( $ids ); }
function morgenbrot_save_content_meta( $post_id, $post ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_revision($post_id) || ! current_user_can('edit_post',$post_id) || empty($_POST['morgenbrot_meta_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['morgenbrot_meta_nonce'])),'morgenbrot_save_'.$post->post_type) ) return;
	if ( 'bakery_preorder' === $post->post_type ) { $allowed=array('new','confirmed','preparation','ready','completed','cancelled');$status=sanitize_key($_POST['morgenbrot_status']??'new');update_post_meta($post_id,'_morgenbrot_status',in_array($status,$allowed,true)?$status:'new');update_post_meta($post_id,'_morgenbrot_internal_note',sanitize_textarea_field(wp_unslash($_POST['morgenbrot_internal_note']??'')));return; }
	$fields = 'bakery_product' === $post->post_type ? morgenbrot_product_fields() : ( 'bakery_location' === $post->post_type ? morgenbrot_location_fields() : array() );
	foreach ( $fields as $key=>$field ) { $raw=wp_unslash($_POST['morgenbrot_'.$key]??''); switch($field['type']){case'checkbox':$value=isset($_POST['morgenbrot_'.$key])?'1':'';break;case'decimal':$value=morgenbrot_sanitize_decimal($raw);break;case'integer':$value=(string)max(0,absint($raw));break;case'email':$value=sanitize_email($raw);break;case'coordinate':$value=is_numeric($raw)?(string)(float)$raw:'';break;case'textarea':$value=sanitize_textarea_field($raw);break;case'hours':$value=morgenbrot_sanitize_hours($raw);break;case'gallery':$value=implode(',',array_filter(array_map('absint',explode(',',(string)$raw))));break;case'select':$value=array_key_exists(sanitize_key($raw),$field['options'])?sanitize_key($raw):array_key_first($field['options']);break;default:$value=sanitize_text_field($raw);}
		if('sku'===$key){$value=strtoupper($value);if($value&&morgenbrot_sku_exists($value,$post_id)){add_filter('redirect_post_location',fn($location)=>add_query_arg('morgenbrot_error','duplicate_sku',$location));continue;}}
		update_post_meta($post_id,'_morgenbrot_'.$key,$value);
	}
	if('bakery_product'===$post->post_type){$regular=(float)get_post_meta($post_id,'_morgenbrot_price',true);$sale=(float)get_post_meta($post_id,'_morgenbrot_sale_price',true);update_post_meta($post_id,'_morgenbrot_effective_price',($sale>0&&$sale<$regular)?$sale:$regular);}
}
add_action( 'save_post', 'morgenbrot_save_content_meta', 10, 2 );
function morgenbrot_admin_notice(){if(($_GET['morgenbrot_error']??'')==='duplicate_sku')echo'<div class="notice notice-error"><p>Die Artikelnummer ist bereits vergeben. Der bisherige SKU-Wert wurde nicht geändert.</p></div>';}add_action('admin_notices','morgenbrot_admin_notice');
function morgenbrot_admin_assets($hook){global$post;if(!in_array($hook,array('post.php','post-new.php'),true)||!$post||'bakery_product'!==$post->post_type)return;wp_enqueue_media();wp_enqueue_script('morgenbrot-admin',plugins_url('assets/js/admin.js',MORGENBROT_CORE_FILE),array(),MORGENBROT_CORE_VERSION,true);wp_enqueue_style('morgenbrot-admin',plugins_url('assets/css/admin.css',MORGENBROT_CORE_FILE),array(),MORGENBROT_CORE_VERSION);}add_action('admin_enqueue_scripts','morgenbrot_admin_assets');
