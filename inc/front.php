<?php

add_action("wp_enqueue_scripts" , "shoacast_register_assets");
add_shortcode("shoa_podcast" ,  "shoa_podcast_shortcode_content");
add_shortcode("shoa_podcast_subscribe_form" ,  "shoa_podcast_subscribe_form_content");

add_shortcode("shoa_last_podcasts_homepage" ,  "shoa_last_podcasts_homepage_content");
add_shortcode("shoa_single_podcast_show_subtitle" ,  "shoa_single_podcast_show_subtitle_callback");

add_shortcode("shoa_single_podcast_related_episode" ,  "shoa_single_podcast_related_episode_callback");

add_shortcode("shoa_single_castbox_player" ,  "shoa_single_castbox_player_callback");


function shoa_single_podcast_related_episode_callback(){
    ob_start();

    $pod_id = get_the_ID();

    $parts = get_post_meta($pod_id , "shoa_parts" , true);

    $parts = isset($parts) ? $parts : [];

    $parts = json_decode($parts);
    
    $shoa_subtitle = get_podcast_part_name($pod_id);    
    $guest = get_podcast_guest($pod_id);
    $guest_title = $guest['fullname'] . ' - ' .  $guest['position'];
    

    ?>
    <div class="related_podcasts">
        <?php 
        foreach($parts as $podcast){
            $podcast->title = 'قسمت ' . get_the_terms($pod_id , "section")[0]->name . ' - بخش ' . $podcast->number;        
            include SHOA_PATH . 'template/related-post-card.php';
        }
        ?>
    </div>
    <script>

        jQuery(document).ready(function($){
            $(".related_item *").click(function(){
                
                var podcast_url = $(this).closest(".related_item").data("sound");
                var vidcast_url = "https://www.aparat.com/video/video/embed/videohash/"+ $(this).closest(".related_item").data("video") +"/vt/frame";
                $(".shoa-podcast-player iframe").attr("src" , podcast_url);
                $(".shoa-videocast-player iframe").attr("src" , vidcast_url);
                $(".wrapper_tabs_name span.part-name").text( ' بخش ' + $(this).closest(".related_item").data("part"));
                scrollToJustAbove();
            });
        });
        
        function scrollToJustAbove() {
            window.scrollTo({ top: jQuery("#shoa_player_section").offsetTop + 0, behavior: "smooth" });
        }
        
    </script>
    <?php

    return ob_get_clean();
}

function shoa_single_castbox_player_callback(){
    include SHOA_PATH . 'template/single-podcast-player.php';
}

function shoa_single_podcast_show_subtitle_callback(){
    ob_start();
    $pod_id = get_the_ID();
    $shoa_subtitle = get_podcast_part_name($pod_id) . ' - ' . get_podcast_title($pod_id);    
    $guest = get_podcast_guest($pod_id);
    $guest_title = $guest['fullname'] . ' - ' .  $guest['position'];
    
    include SHOA_PATH . 'template/single-podcast-summarise.php';

    wp_enqueue_style("shoa_style");
    wp_enqueue_script("shoa_script");
    return ob_get_clean();
}



function shoa_last_podcasts_homepage_content(){

    ob_start();

    $posts = get_posts([
        'post_type' => "shoa-podcast",
        'status' => 'publish',
        'numberposts' => 4,
        'orderby'          => 'date',
		'order'            => 'DESC',
    ]);

    ?>  

    <div class="podcast_header_title">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="28" viewBox="0 0 40 28" fill="none"><rect x="0.61731" width="39.3827" height="28" rx="3" fill="#FDD329"></rect></svg>
            <h4>آخرین قسمت‌های منتشر شده</h4>
        </div>
        <div>
            <a href="/blog" target="_blank">
                <span>مشاهده همه</span>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M16 8.00004C16 8.26526 15.8947 8.51961 15.7071 8.70715C15.5196 8.89469 15.2653 9.00004 15.0001 9.00004H3.41541L7.70893 13.292C7.80189 13.385 7.87564 13.4954 7.92595 13.6169C7.97626 13.7384 8.00216 13.8686 8.00216 14C8.00216 14.1315 7.97626 14.2617 7.92595 14.3832C7.87564 14.5047 7.80189 14.6151 7.70893 14.708C7.61596 14.801 7.5056 14.8748 7.38413 14.9251C7.26266 14.9754 7.13248 15.0013 7.00101 15.0013C6.86953 15.0013 6.73935 14.9754 6.61788 14.9251C6.49642 14.8748 6.38605 14.801 6.29308 14.708L0.293755 8.70804C0.200639 8.61515 0.126761 8.5048 0.0763536 8.38331C0.0259463 8.26182 0 8.13158 0 8.00004C0 7.86851 0.0259463 7.73827 0.0763536 7.61678C0.126761 7.49529 0.200639 7.38493 0.293755 7.29204L6.29308 1.29204C6.48084 1.10427 6.73548 0.998779 7.00101 0.998779C7.26653 0.998779 7.52117 1.10427 7.70893 1.29204C7.89668 1.47982 8.00216 1.73449 8.00216 2.00004C8.00216 2.26559 7.89668 2.52027 7.70893 2.70804L3.41541 7.00004H15.0001C15.2653 7.00004 15.5196 7.1054 15.7071 7.29294C15.8947 7.48047 16 7.73483 16 8.00004Z" fill="#0B0B0B"/></svg>
            </a>
        </div>
    </div>
    <div class="podcast_archive_wrapper column-4">
        <?php foreach($posts as $post): ?>    
            <div class="item">
                <a href="<?php echo get_the_permalink($post->ID); ?>">
                    <div class="image-wrapper">
                        <?php echo get_the_post_thumbnail( $post->ID ); ?>
                    </div>
                </a>
            </div>
        <?php  endforeach; ?>    
    </div>

    <?php

    return ob_get_clean();
}



function shoacast_register_assets(){
        //for frontend user
    wp_enqueue_script("shoa_script" , SHOA_URI . '/assets/js/script.min.js' , ['jquery'] , SHOA_VER , true);
    wp_enqueue_style("shoa_style" , SHOA_URI . '/assets/css/style.css' , [] , SHOA_VER );
}

function shoa_podcast_shortcode_content($atts){
    ob_start();
    include SHOA_PATH . 'template/podcast-shortcode.php';

    wp_enqueue_style("shoa_style");
    
    wp_localize_script("shoa_script" , "SHOA_DATA" , [
        'ajax_url' => admin_url("admin-ajax.php"),
        'podcasts' => get_shoa_podcasts(),
        'guests' => get_guests(),
    ]);
    wp_enqueue_script("shoa_script");

    return ob_get_clean();
}


function shoa_podcast_subscribe_form_content(){
    ob_start();

    ?>

    <form class="shoa_subscribe_notif_form">
        <input type="email" placeholder="برای اطلاع از آخرین قسمت منتشر شده ایمیل خود را وارد کنید" >
        <button type="submit" class="subscribe_notifications">ارسال درخواست</button>
    </form>

    <?php

    return ob_get_clean();
}

function get_guests(){
    $guests = get_terms([
        'taxonomy' => 'shoa_guests',
        'hide_empty' => false,
        'parent'   => 0,
        'orderby'      => 'term_order',
        'order'          => 'ASC'
    ]);

    foreach ($guests as $guest){
        $guest->avatar = get_term_meta($guest->term_id , "shoa-guest-avatar" , true);
        $guest->position = get_term_meta($guest->term_id , "shoa-guest-position" , true);
    }

    return $guests;
}


function get_shoa_podcasts(){


    $podcasts = get_posts([
        'post_type' => 'shoa-podcast',
        'status' => 'publish',
        'numberposts' => -1,
    ]);
    
    $return = [];

    foreach($podcasts as $podcast){

        $object = new stdClass;
        $object->id         = $podcast->ID;
        $object->image      = get_the_post_thumbnail_url($podcast->ID);
        $object->title      = get_podcast_title($podcast->ID);
        $object->part_name  = get_podcast_part_name($podcast->ID);
        $object->guest      = get_podcast_guest($podcast->ID);
        $object->audio_url  = get_post_meta($podcast->ID , "shoa_sound_file_url" , true);
        $object->video_url  = get_post_meta($podcast->ID , "shoa_video_file_url" , true);
        $object->share_url  = get_the_permalink($podcast->ID);
        $object->created_at  = $podcast->post_date_gmt;

        $return[] = $object;

    }

    return $return;

}


function get_podcast_title($post_id){
    $shoa_subtitle = get_post_meta($post_id , "shoa_subtitle" ,  true);
    $terms = get_the_terms( $post_id , 'section' );
    $term_title = isset($terms[0]) && is_object($terms[0]) ? get_term_meta( $terms[0]->term_id, 'shoa-session-title', true ) : '';
    if(isset($shoa_subtitle) && !empty($shoa_subtitle)){
        $term_title = $shoa_subtitle;
    }

    return $term_title;
}




function get_podcast_part_name($post_id){

    $term_session = get_the_terms($post_id , "section");

    if(!isset($term_session[0])){
        return '';
    }

    $session_name = $term_session[0]->name;


    $term_season = get_the_terms($post_id , 'season');

    if(!isset($term_season[0])){
        return '';
    }

    $term_season = $term_season[0]->name;

    return "فصل $term_season - قسمت $session_name";
}


function get_podcast_guest($post_id){
    
    $term_guest = get_the_terms($post_id , "shoa_guests");

    if(!isset($term_guest[0])){
        return '';
    }

    $position = get_term_meta($term_guest[0]->term_id , "shoa-guest-position" , true);

    return [
        'id' => $term_guest[0]->term_id,
        'fullname' => $term_guest[0]->name,
        'position' => $position,
    ];

}