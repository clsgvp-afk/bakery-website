<?php
defined('ABSPATH')||exit;
function morgenbrot_defaults(){return array(
'bakery_name'=>'Bäckerei Morgenbrot','tagline'=>'Handwerklich gebacken. Jeden Morgen frisch.','email'=>'hallo@morgenbrot.example','phone'=>'+49 7841 000000','address'=>'Achern · Ortenau','support_hours'=>'Mo–Fr 08:00–17:00','preorder_text'=>'Vorbestellen','preorder_url'=>'/vorbestellen/','show_header_cta'=>'1','header_cta_new_tab'=>'','impressum_url'=>'/impressum/','privacy_url'=>'/datenschutz/','disclaimer'=>'Fiktives Portfolio-Projekt – kein echtes Unternehmen.','color_cream'=>'#FFF8ED','color_brown'=>'#4A2C20','color_gold'=>'#C68A3A','color_rust'=>'#A64B2A','color_green'=>'#4F6B46','social_instagram'=>'','social_facebook'=>'','social_mastodon'=>'','logo_id'=>0,'favicon_id'=>0,'builtin_seo'=>'1',
'footer_description'=>'Handwerklich gebacken. Jeden Morgen frisch.','footer_heading'=>'Besuchen Sie uns','footer_contact'=>'Achern · Ortenau','footer_hours'=>'Mo–Fr 06:00–18:00','footer_copyright'=>'© {year} Bäckerei Morgenbrot','footer_show_brand'=>'1','footer_show_menu'=>'1','footer_show_contact'=>'1','footer_show_social'=>'1',
'home_sections'=>'hero,products,features,promotion,categories,story,locations,testimonials,faq,final_cta','hero_eyebrow'=>'Familienbäckerei · Achern','hero_title'=>'Handwerklich gebacken. Jeden Morgen frisch.','hero_text'=>'Entdecken Sie traditionelle Backwaren aus ausgewählten Zutaten – täglich frisch für Achern und die Ortenau.','hero_image_id'=>0,'hero_primary_text'=>'Unser Sortiment','hero_primary_url'=>'/sortiment/','hero_secondary_text'=>'Filiale finden','hero_secondary_url'=>'/filialen/','featured_count'=>4,'featured_ids'=>'','feature_items'=>wp_json_encode(array(array('icon'=>'☀','title'=>'Täglich frisch','text'=>'Jeden Morgen beginnt unsere Backstube neu.'),array('icon'=>'✦','title'=>'Handwerkliche Herstellung','text'=>'Zeit, Erfahrung und ein Blick fürs Detail.'),array('icon'=>'❦','title'=>'Ausgewählte Zutaten','text'=>'Ehrliche Rezeptideen, klar beschrieben.'),array('icon'=>'☺','title'=>'Persönlicher Service','text'=>'Freundlich, nah und unkompliziert.'))),'category_ids'=>'','story_title'=>'Backtradition mit moderner Idee','story_text'=>'Wir verbinden klassische Backideen mit einem modernen Blick auf Zutaten, Zugänglichkeit und lokale Nähe.','story_image_id'=>0,'story_button_text'=>'Mehr über uns','story_button_url'=>'/uber-uns/','location_ids'=>'','testimonials'=>wp_json_encode(array(array('quote'=>'Warm, übersichtlich und genau die Informationen, die ich morgens brauche.','name'=>'Anna, Achern'),array('quote'=>'Die Zutaten und Allergene sind angenehm schnell zu finden.','name'=>'Felix, Oberachern'))),'faq_items'=>wp_json_encode(array(array('question'=>'Kann ich online bezahlen?','answer'=>'Nein. Die Vorbestellung ist eine unverbindliche Anfrage.'),array('question'=>'Sind die Filialen real?','answer'=>'Nein. Standorte und Kontaktdaten sind Demonstrationsdaten.'))),'final_title'=>'Für morgen vorbestellen','final_text'=>'Lieblingsstücke auswählen und unverbindlich zur Abholung anfragen.','final_image_id'=>0,'final_button_text'=>'Vorbestellung starten','final_button_url'=>'/vorbestellen/' );}
function morgenbrot_options(){return wp_parse_args((array)get_option('morgenbrot_options',array()),morgenbrot_defaults());}
function morgenbrot_get_option($key,$default=''){ $options=morgenbrot_options();return $options[$key]??$default; }
function morgenbrot_sanitize_options($input){$old=morgenbrot_options();$out=array();$url_keys=array('preorder_url','impressum_url','privacy_url','social_instagram','social_facebook','social_mastodon','hero_primary_url','hero_secondary_url','story_button_url','final_button_url');$int_keys=array('logo_id','favicon_id','hero_image_id','story_image_id','final_image_id','featured_count');$bool_keys=array('show_header_cta','header_cta_new_tab','builtin_seo','footer_show_brand','footer_show_menu','footer_show_contact','footer_show_social');$json_keys=array('feature_items','testimonials','faq_items');foreach(morgenbrot_defaults()as$key=>$default){$raw=$input[$key]??'';if(in_array($key,$url_keys,true))$out[$key]=esc_url_raw($raw);elseif(in_array($key,$int_keys,true))$out[$key]=absint($raw);elseif(in_array($key,$bool_keys,true))$out[$key]=!empty($raw)?'1':'';elseif(in_array($key,$json_keys,true)){$decoded=json_decode(wp_unslash($raw),true);$out[$key]=is_array($decoded)?wp_json_encode($decoded):$old[$key];}elseif('email'===$key)$out[$key]=sanitize_email($raw);elseif(str_starts_with($key,'color_'))$out[$key]=sanitize_hex_color($raw)?:$default;elseif(in_array($key,array('home_sections','featured_ids','category_ids','location_ids'),true))$out[$key]=sanitize_text_field($raw);else$out[$key]=sanitize_textarea_field($raw);}return$out;}
function morgenbrot_settings_init(){register_setting('morgenbrot_settings','morgenbrot_options',array('sanitize_callback'=>'morgenbrot_sanitize_options'));}add_action('admin_init','morgenbrot_settings_init');
function morgenbrot_settings_menu(){add_submenu_page('morgenbrot','Allgemeine Einstellungen','Allgemein','manage_morgenbrot_settings','morgenbrot-general',fn()=>morgenbrot_settings_page('general'));add_submenu_page('morgenbrot','Startseite','Startseite','edit_morgenbrot_content','morgenbrot-home',fn()=>morgenbrot_settings_page('home'));add_submenu_page('morgenbrot','Footer','Footer','edit_morgenbrot_content','morgenbrot-footer',fn()=>morgenbrot_settings_page('footer'));}add_action('admin_menu','morgenbrot_settings_menu',20);
function morgenbrot_field($key,$label,$type='text',$help=''){ $value=morgenbrot_get_option($key);echo'<div class="morgenbrot-setting"><label for="mb_'.esc_attr($key).'"><strong>'.esc_html($label).'</strong></label>';if('textarea'===$type||'json'===$type)printf('<textarea id="mb_%1$s" name="morgenbrot_options[%1$s]" rows="%2$d">%3$s</textarea>',esc_attr($key),'json'===$type?8:4,esc_textarea($value));elseif('checkbox'===$type)printf('<label><input id="mb_%1$s" type="checkbox" name="morgenbrot_options[%1$s]" value="1" %2$s> Aktiv</label>',esc_attr($key),checked($value,'1',false));elseif('media'===$type){printf('<input id="mb_%1$s" class="morgenbrot-media-id" type="hidden" name="morgenbrot_options[%1$s]" value="%2$d"><div class="morgenbrot-media-preview">%3$s</div><button type="button" class="button morgenbrot-select-media">Bild auswählen</button> <button type="button" class="button-link-delete morgenbrot-clear-media">Entfernen</button>',esc_attr($key),absint($value),$value?wp_get_attachment_image($value,'thumbnail'):'');}else printf('<input id="mb_%1$s" type="%2$s" name="morgenbrot_options[%1$s]" value="%3$s">',esc_attr($key),'url'===$type?'text':esc_attr($type),esc_attr($value));if($help)echo'<p class="description">'.esc_html($help).'</p>';echo'</div>';}
function morgenbrot_settings_page($tab){if(!current_user_can('manage_morgenbrot_settings')&&!current_user_can('edit_morgenbrot_content'))return;$titles=array('general'=>'Allgemein','home'=>'Startseite','footer'=>'Footer');echo'<div class="wrap morgenbrot-settings"><h1>Morgenbrot · '.esc_html($titles[$tab]).'</h1><nav class="nav-tab-wrapper"><a class="nav-tab '.($tab==='general'?'nav-tab-active':'').'" href="'.esc_url(admin_url('admin.php?page=morgenbrot-general')).'">Allgemein</a><a class="nav-tab '.($tab==='home'?'nav-tab-active':'').'" href="'.esc_url(admin_url('admin.php?page=morgenbrot-home')).'">Startseite</a><a class="nav-tab '.($tab==='footer'?'nav-tab-active':'').'" href="'.esc_url(admin_url('admin.php?page=morgenbrot-footer')).'">Footer</a></nav><form method="post" action="options.php">';settings_fields('morgenbrot_settings');echo'<div class="morgenbrot-settings-grid">';if('general'===$tab){morgenbrot_field('bakery_name','Name der Bäckerei');morgenbrot_field('logo_id','Logo','media');morgenbrot_field('favicon_id','Favicon','media');morgenbrot_field('tagline','Slogan','textarea');morgenbrot_field('email','Haupt-E-Mail','email');morgenbrot_field('phone','Telefon');morgenbrot_field('address','Adresse','textarea');morgenbrot_field('support_hours','Servicezeiten');morgenbrot_field('preorder_text','CTA-Text');morgenbrot_field('preorder_url','CTA-Link','url');morgenbrot_field('show_header_cta','CTA anzeigen','checkbox');morgenbrot_field('header_cta_new_tab','CTA in neuem Tab','checkbox');morgenbrot_field('impressum_url','Impressum-Link','url');morgenbrot_field('privacy_url','Datenschutz-Link','url');morgenbrot_field('disclaimer','Hinweis fiktives Projekt','textarea');foreach(array('instagram','facebook','mastodon')as$s)morgenbrot_field('social_'.$s,ucfirst($s),'url');foreach(array('cream'=>'Creme','brown'=>'Braun','gold'=>'Gold','rust'=>'Rost','green'=>'Grün')as$k=>$l)morgenbrot_field('color_'.$k,$l,'color');morgenbrot_field('builtin_seo','Integrierte SEO-Ausgabe','checkbox');}elseif('home'===$tab){morgenbrot_field('home_sections','Abschnittsreihenfolge', 'text','Kommagetrennt: hero,products,features,promotion,categories,story,locations,testimonials,faq,final_cta. Weglassen blendet aus.');morgenbrot_field('hero_eyebrow','Hero: Überzeile');morgenbrot_field('hero_title','Hero: H1');morgenbrot_field('hero_text','Hero: Beschreibung','textarea');morgenbrot_field('hero_image_id','Hero: Bild','media');morgenbrot_field('hero_primary_text','Hero: Hauptbutton');morgenbrot_field('hero_primary_url','Hero: Hauptlink','url');morgenbrot_field('hero_secondary_text','Hero: Nebenbutton');morgenbrot_field('hero_secondary_url','Hero: Nebenlink','url');morgenbrot_field('featured_ids','Beliebte Produkte: IDs','text','Kommagetrennte Produkt-IDs; leer = Empfohlen.');morgenbrot_field('featured_count','Anzahl Karten','number');morgenbrot_field('feature_items','Vorteile (JSON)','json','Array aus icon, title, text; Reihenfolge entspricht Ausgabe.');morgenbrot_field('category_ids','Kategorie-IDs');morgenbrot_field('story_title','Geschichte: Titel');morgenbrot_field('story_text','Geschichte: Text','textarea');morgenbrot_field('story_image_id','Geschichte: Bild','media');morgenbrot_field('story_button_text','Geschichte: Button');morgenbrot_field('story_button_url','Geschichte: Link','url');morgenbrot_field('location_ids','Filial-IDs');morgenbrot_field('testimonials','Bewertungen (JSON)','json','Array aus quote und name.');morgenbrot_field('faq_items','FAQ (JSON)','json','Array aus question und answer.');morgenbrot_field('final_title','Finaler CTA: Titel');morgenbrot_field('final_text','Finaler CTA: Text','textarea');morgenbrot_field('final_image_id','Finaler CTA: Bild','media');morgenbrot_field('final_button_text','Finaler CTA: Button');morgenbrot_field('final_button_url','Finaler CTA: Link','url');}else{morgenbrot_field('footer_show_brand','Markenblock anzeigen','checkbox');morgenbrot_field('footer_description','Beschreibung','textarea');morgenbrot_field('footer_show_menu','Menü anzeigen','checkbox');morgenbrot_field('footer_show_contact','Kontakte anzeigen','checkbox');morgenbrot_field('footer_heading','Kontaktüberschrift');morgenbrot_field('footer_contact','Kontaktdaten','textarea');morgenbrot_field('footer_hours','Öffnungszeiten','textarea');morgenbrot_field('footer_show_social','Social Links anzeigen','checkbox');morgenbrot_field('footer_copyright','Copyright','text','{year} wird durch das aktuelle Jahr ersetzt.');morgenbrot_field('disclaimer','Hinweis fiktives Projekt','textarea');}echo'</div>';submit_button('Änderungen speichern');echo'</form></div>';}
function morgenbrot_settings_assets($hook){if(!str_contains($hook,'morgenbrot'))return;wp_enqueue_media();wp_enqueue_script('morgenbrot-settings',plugins_url('assets/js/settings.js',MORGENBROT_CORE_FILE),array(),MORGENBROT_CORE_VERSION,true);wp_enqueue_style('morgenbrot-settings',plugins_url('assets/css/settings.css',MORGENBROT_CORE_FILE),array(),MORGENBROT_CORE_VERSION);}add_action('admin_enqueue_scripts','morgenbrot_settings_assets');

/** Repair malformed Unicode escapes produced by early demo settings versions. */
function morgenbrot_repair_option_encoding() {
	if ( '1' === get_option( 'morgenbrot_encoding_repair_20260816' ) ) return;
	$options = (array) get_option( 'morgenbrot_options', array() );
	$map = array(
		'u00e4'=>'ä','u00c4'=>'Ä','u00f6'=>'ö','u00d6'=>'Ö','u00fc'=>'ü','u00dc'=>'Ü','u00df'=>'ß',
		'u20ac'=>'€','u2013'=>'–','u2014'=>'—','u201e'=>'„','u201c'=>'“','u201d'=>'”','u00b7'=>'·',
	);
	$repair = function( $value ) use ( $map ) {
		if ( ! is_string( $value ) ) return $value;
		foreach ( $map as $broken=>$correct ) $value = preg_replace( '/(?<!\\\\)'.preg_quote($broken,'/').'/i', $correct, $value );
		return $value;
	};
	$options = array_map( $repair, $options );
	update_option( 'morgenbrot_options', $options, false );
	update_option( 'morgenbrot_encoding_repair_20260816', '1', false );
}
add_action( 'init', 'morgenbrot_repair_option_encoding', 40 );

/** Replace early portfolio-style testimonials with natural bakery reviews. */
function morgenbrot_update_demo_testimonials() {
	if ( '1' === get_option( 'morgenbrot_testimonials_20260816' ) ) return;
	$options = (array) get_option( 'morgenbrot_options', array() );
	$options['testimonials'] = wp_json_encode( array(
		array( 'quote'=>'Das Brot schmeckt wunderbar aromatisch und ist morgens immer herrlich frisch.', 'name'=>'Lena, Achern' ),
		array( 'quote'=>'Die Auswahl ist groß, die Bedienung freundlich und meine Vorbestellung war pünktlich fertig.', 'name'=>'Markus, Oberachern' ),
		array( 'quote'=>'Die knusprigen Brötchen und Croissants gehören für uns inzwischen zu jedem Sonntagsfrühstück.', 'name'=>'Familie Weber, Ortenau' ),
	), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	update_option( 'morgenbrot_options', $options, false );
	update_option( 'morgenbrot_testimonials_20260816', '1', false );
}
add_action( 'init', 'morgenbrot_update_demo_testimonials', 41 );

/** Keep settings from the other admin tabs when the current tab is saved. */
function morgenbrot_preserve_other_settings_tabs( $new, $old ) {
	$referer = isset( $_POST['_wp_http_referer'] ) ? sanitize_text_field( wp_unslash( $_POST['_wp_http_referer'] ) ) : '';
	parse_str( (string) wp_parse_url( $referer, PHP_URL_QUERY ), $query );
	$page = $query['page'] ?? '';
	$tab_keys = array(
		'morgenbrot-general' => array( 'bakery_name','tagline','email','phone','address','support_hours','preorder_text','preorder_url','show_header_cta','header_cta_new_tab','impressum_url','privacy_url','disclaimer','social_instagram','social_facebook','social_mastodon','logo_id','favicon_id','color_cream','color_brown','color_gold','color_rust','color_green','builtin_seo' ),
		'morgenbrot-home'    => array( 'home_sections','hero_eyebrow','hero_title','hero_text','hero_image_id','hero_primary_text','hero_primary_url','hero_secondary_text','hero_secondary_url','featured_count','featured_ids','feature_items','category_ids','story_title','story_text','story_image_id','story_button_text','story_button_url','location_ids','testimonials','faq_items','final_title','final_text','final_image_id','final_button_text','final_button_url' ),
		'morgenbrot-footer'  => array( 'footer_description','footer_heading','footer_contact','footer_hours','footer_copyright','footer_show_brand','footer_show_menu','footer_show_contact','footer_show_social','disclaimer' ),
	);
	if ( ! isset( $tab_keys[ $page ] ) ) return $new;
	$allowed = array_flip( $tab_keys[ $page ] );
	foreach ( (array) $old as $key => $value ) {
		if ( ! isset( $allowed[ $key ] ) ) $new[ $key ] = $value;
	}
	return $new;
}
add_filter( 'pre_update_option_morgenbrot_options', 'morgenbrot_preserve_other_settings_tabs', 10, 2 );

/** Restore required internal links if an earlier settings save cleared them. */
function morgenbrot_restore_required_links() {
	if ( '1' === get_option( 'morgenbrot_required_links_20260816' ) ) return;
	$options = (array) get_option( 'morgenbrot_options', array() );
	if ( empty( $options['impressum_url'] ) ) $options['impressum_url'] = '/impressum/';
	if ( empty( $options['privacy_url'] ) ) $options['privacy_url'] = '/datenschutz/';
	if ( empty( $options['preorder_url'] ) ) $options['preorder_url'] = '/vorbestellen/';
	update_option( 'morgenbrot_options', $options, false );
	update_option( 'morgenbrot_required_links_20260816', '1', false );
}
add_action( 'init', 'morgenbrot_restore_required_links', 42 );

/** Restore presentation essentials cleared by the former cross-tab save bug. */
function morgenbrot_restore_presentation_defaults() {
	if ( '1' === get_option( 'morgenbrot_presentation_defaults_20260816' ) ) return;
	$options  = (array) get_option( 'morgenbrot_options', array() );
	$defaults = morgenbrot_defaults();
	$keys = array(
		'bakery_name','tagline','email','phone','address','support_hours','preorder_text',
		'show_header_cta','impressum_url','privacy_url','disclaimer','color_cream','color_brown',
		'color_gold','color_rust','color_green','builtin_seo','footer_description','footer_heading',
		'footer_contact','footer_hours','footer_copyright','footer_show_brand','footer_show_menu',
		'footer_show_contact','footer_show_social'
	);
	foreach ( $keys as $key ) {
		if ( ! isset( $options[ $key ] ) || '' === $options[ $key ] ) $options[ $key ] = $defaults[ $key ];
	}
	update_option( 'morgenbrot_options', $options, false );
	update_option( 'morgenbrot_presentation_defaults_20260816', '1', false );
}
add_action( 'init', 'morgenbrot_restore_presentation_defaults', 43 );
