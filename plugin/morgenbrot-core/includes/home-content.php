<?php
defined( 'ABSPATH' ) || exit;

function morgenbrot_home_content_defaults() {
	return array(
		'products_eyebrow' => 'Aus der Backstube', 'products_title' => 'Unsere Lieblinge', 'products_link_text' => 'Alles entdecken →',
		'offer_enabled' => '1', 'offer_eyebrow' => 'Angebot der Woche', 'offer_title' => 'Frühstück für zwei',
		'offer_text' => 'Zwei Heißgetränke, vier Brötchen und zwei süße Kleinigkeiten – als rein fiktives Portfolio-Angebot.',
		'offer_price' => '12,90 €', 'offer_note' => 'Demo-Preis', 'offer_image_id' => 0, 'offer_button_text' => '', 'offer_button_url' => '',
		'categories_eyebrow' => 'Für jeden Geschmack', 'categories_title' => 'Unser Sortiment',
		'story_eyebrow' => 'Unsere Idee', 'story_extra' => 'Wir zeigen, wie ein lokales Handwerksunternehmen digital wirken kann – mit klaren Produktinformationen, zugänglicher Bedienung und viel Wärme.',
		'locations_eyebrow' => 'In Ihrer Nähe', 'locations_title' => 'Unsere Filialen',
		'testimonials_eyebrow' => 'Stimmen zum Konzept', 'testimonials_title' => 'So könnte Morgenbrot ankommen',
		'faq_eyebrow' => 'Gut zu wissen', 'faq_title' => 'Häufige Fragen', 'final_eyebrow' => 'Einfach vorbereitet',
	);
}
function morgenbrot_home_content() { return wp_parse_args( (array) get_option( 'morgenbrot_home_content', array() ), morgenbrot_home_content_defaults() ); }
function morgenbrot_home_value( $key, $default = '' ) { $values = morgenbrot_home_content(); return $values[ $key ] ?? $default; }
function morgenbrot_sanitize_home_content( $input ) {
	$out = morgenbrot_home_content();
	foreach ( morgenbrot_home_content_defaults() as $key => $default ) {
		if ( 'offer_enabled' === $key ) { $out[$key] = empty($input[$key]) ? '' : '1'; continue; }
		if ( 'offer_image_id' === $key ) { $out[$key] = absint($input[$key] ?? 0); continue; }
		if ( 'offer_button_url' === $key ) { $out[$key] = esc_url_raw($input[$key] ?? ''); continue; }
		$out[$key] = sanitize_textarea_field( wp_unslash( $input[$key] ?? '' ) );
	}
	return $out;
}
add_action( 'admin_init', function(){ register_setting( 'morgenbrot_home_content_group', 'morgenbrot_home_content', array( 'sanitize_callback'=>'morgenbrot_sanitize_home_content' ) ); } );
add_action( 'admin_menu', function(){ add_submenu_page( 'morgenbrot', 'Startseiten-Inhalte', 'Startseiten-Inhalte', 'edit_morgenbrot_content', 'morgenbrot-home-content', 'morgenbrot_home_content_page' ); }, 21 );
function morgenbrot_home_content_field( $key, $label, $type='text' ) {
	$value=morgenbrot_home_value($key); echo '<div class="morgenbrot-setting"><label for="mbhc_'.esc_attr($key).'"><strong>'.esc_html($label).'</strong></label>';
	if('textarea'===$type) printf('<textarea id="mbhc_%1$s" name="morgenbrot_home_content[%1$s]" rows="4">%2$s</textarea>',esc_attr($key),esc_textarea($value));
	elseif('checkbox'===$type) printf('<label><input id="mbhc_%1$s" type="checkbox" name="morgenbrot_home_content[%1$s]" value="1" %2$s> Anzeigen</label>',esc_attr($key),checked($value,'1',false));
	elseif('media'===$type) printf('<input id="mbhc_%1$s" class="morgenbrot-media-id" type="hidden" name="morgenbrot_home_content[%1$s]" value="%2$d"><div class="morgenbrot-media-preview">%3$s</div><button type="button" class="button morgenbrot-select-media">Bild auswählen</button> <button type="button" class="button-link-delete morgenbrot-clear-media">Entfernen</button>',esc_attr($key),absint($value),$value?wp_get_attachment_image($value,'thumbnail'):'');
	else printf('<input id="mbhc_%1$s" type="%2$s" name="morgenbrot_home_content[%1$s]" value="%3$s">',esc_attr($key),esc_attr($type),esc_attr($value)); echo '</div>';
}
function morgenbrot_home_content_page(){
	if(!current_user_can('edit_morgenbrot_content'))return; echo '<div class="wrap morgenbrot-settings"><h1>Morgenbrot · Startseiten-Inhalte</h1><p>Texte und das Bild des Wochenangebots. Hero-, Geschichte- und CTA-Bilder finden Sie unter <a href="'.esc_url(admin_url('admin.php?page=morgenbrot-home')).'">Startseite</a>.</p><form method="post" action="options.php">';settings_fields('morgenbrot_home_content_group');echo '<div class="morgenbrot-settings-grid">';
	foreach(array('products_eyebrow'=>'Produkte: Überzeile','products_title'=>'Produkte: Titel','products_link_text'=>'Produkte: Linktext','categories_eyebrow'=>'Kategorien: Überzeile','categories_title'=>'Kategorien: Titel','story_eyebrow'=>'Geschichte: Überzeile','locations_eyebrow'=>'Filialen: Überzeile','locations_title'=>'Filialen: Titel','testimonials_eyebrow'=>'Bewertungen: Überzeile','testimonials_title'=>'Bewertungen: Titel','faq_eyebrow'=>'FAQ: Überzeile','faq_title'=>'FAQ: Titel','final_eyebrow'=>'Finaler CTA: Überzeile')as$key=>$label) morgenbrot_home_content_field($key,$label);
	morgenbrot_home_content_field('story_extra','Geschichte: Zusatztext','textarea');morgenbrot_home_content_field('offer_enabled','Wochenangebot','checkbox');morgenbrot_home_content_field('offer_eyebrow','Angebot: Überzeile');morgenbrot_home_content_field('offer_title','Angebot: Titel');morgenbrot_home_content_field('offer_text','Angebot: Beschreibung','textarea');morgenbrot_home_content_field('offer_price','Angebot: Preis');morgenbrot_home_content_field('offer_note','Angebot: Preishinweis');morgenbrot_home_content_field('offer_image_id','Angebot: Bild','media');morgenbrot_home_content_field('offer_button_text','Angebot: Buttontext');morgenbrot_home_content_field('offer_button_url','Angebot: Buttonlink','url');
	echo '</div>';submit_button('Änderungen speichern');echo '</form></div>';
}
