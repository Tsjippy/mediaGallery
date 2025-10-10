<?php
namespace SIM\MEDIAGALLERY;
use SIM;

add_action( 'wp_after_insert_post', __NAMESPACE__.'\afterInsertPost', 10, 2);
function afterInsertPost($postId, $post){
    if(has_shortcode($post->post_content, 'mediagallery')){

        $pages  = SIM\getModuleOption(MODULE_SLUG, 'mediagallery-pages', false);

        $pages[]  = $postId;

        SIM\updateModuleOptions(MODULE_SLUG, $pages, 'mediagallery-pages');
    }
}

add_action( 'wp_trash_post', __NAMESPACE__.'\trashPost' );
function trashPost($postId){
    $pages  = SIM\getModuleOption(MODULE_SLUG, 'mediagallery-pages', false);
    $index  = array_search($postId, $pages);
    if($index){ 
        unset($pages[$index]);
        SIM\updateModuleOptions(MODULE_SLUG, $pages, 'mediagallery-pages');
    }
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__.'\enqueueMediaGalleryScripts');
function enqueueMediaGalleryScripts(){
    wp_register_style( 'sim_gallery_style', SIM\pathToUrl(MODULE_PATH.'css/media_gallery.min.css'), array(), MODULE_VERSION);

    wp_register_script('sim_gallery_script', SIM\pathToUrl(MODULE_PATH.'js/media_gallery.min.js'), array('sim_formsubmit_script'), MODULE_VERSION, true);
    wp_register_script('sim_refresh_gallery_script', SIM\pathToUrl(MODULE_PATH.'js/auto_refresh.min.js'), array('sim_formsubmit_script'), MODULE_VERSION, true);

    $pages   = SIM\getModuleOption(MODULE_SLUG, 'mediagallery_pages');
    if(is_numeric(get_the_ID()) && in_array(get_the_ID(), $pages)){
        wp_enqueue_style('sim_gallery_style');
		wp_enqueue_script('sim_gallery_script');
    }
}