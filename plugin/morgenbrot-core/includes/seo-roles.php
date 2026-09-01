<?php
defined( 'ABSPATH' ) || exit;
function morgenbrot_register_roles(){
	$all=array('read'=>true,'upload_files'=>true,'edit_posts'=>true,'edit_pages'=>true,'publish_posts'=>true,'publish_pages'=>true,'edit_published_posts'=>true,'edit_published_pages'=>true,'delete_posts'=>true,'manage_morgenbrot_settings'=>true,'edit_morgenbrot_content'=>true,'manage_morgenbrot_orders'=>true);
	add_role('morgenbrot_manager','Morgenbrot Manager',$all);
	add_role('morgenbrot_content_editor','Morgenbrot Inhaltsredaktion',array_merge($all,array('manage_morgenbrot_settings'=>false,'manage_morgenbrot_orders'=>false)));
	add_role('morgenbrot_order_manager','Morgenbrot Bestellungen',array('read'=>true,'manage_morgenbrot_orders'=>true));
	$admin=get_role('administrator');if($admin)foreach(array_keys($all)as$cap)$admin->add_cap($cap);
}
add_action('init','morgenbrot_register_roles',5);

function morgenbrot_seo_boxes(){foreach(array('post','page','bakery_product','bakery_location','bakery_promotion')as$type)add_meta_box('morgenbrot_seo','SEO & Social','morgenbrot_seo_box',$type,'normal','default');}
add_action('add_meta_boxes','morgenbrot_seo_boxes');
function morgenbrot_seo_box($post){wp_nonce_field('morgenbrot_seo','morgenbrot_seo_nonce');foreach(array('title'=>'SEO-Titel','description'=>'Meta-Beschreibung','image'=>'Social-Bild-ID')as$key=>$label)printf('<p><label><strong>%s</strong><br>%s</label></p>',esc_html($label),'description'===$key?'<textarea class="widefat" name="mb_seo_'.$key.'">'.esc_textarea(get_post_meta($post->ID,'_mb_seo_'.$key,true)).'</textarea>':'<input class="widefat" name="mb_seo_'.$key.'" value="'.esc_attr(get_post_meta($post->ID,'_mb_seo_'.$key,true)).'">');}
function morgenbrot_save_seo($id){if(!isset($_POST['morgenbrot_seo_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['morgenbrot_seo_nonce'])),'morgenbrot_seo')||(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)||!current_user_can('edit_post',$id))return;foreach(array('title','description','image')as$key)update_post_meta($id,'_mb_seo_'.$key,'image'===$key?absint($_POST['mb_seo_'.$key]??0):sanitize_text_field(wp_unslash($_POST['mb_seo_'.$key]??'')));}
add_action('save_post','morgenbrot_save_seo');
function morgenbrot_seo_head(){if(is_admin()||!morgenbrot_get_option('builtin_seo','1')||!is_singular())return;$id=get_queried_object_id();$title=get_post_meta($id,'_mb_seo_title',true);$description=get_post_meta($id,'_mb_seo_description',true)?:get_the_excerpt($id);$image=wp_get_attachment_image_url(absint(get_post_meta($id,'_mb_seo_image',true)),'full')?:get_the_post_thumbnail_url($id,'full');if($title)echo '<meta property="og:title" content="'.esc_attr($title).'">\n';if($description)echo '<meta name="description" content="'.esc_attr(wp_strip_all_tags($description)).'"><meta property="og:description" content="'.esc_attr(wp_strip_all_tags($description)).'">\n';if($image)echo '<meta property="og:image" content="'.esc_url($image).'">\n';}
add_action('wp_head','morgenbrot_seo_head',2);
