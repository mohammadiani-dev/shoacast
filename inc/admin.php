<?php

add_action("admin_enqueue_scripts" , "shoacast_register_admin_assets");

add_action("init" , "shoacast_init");
add_action("add_meta_boxes", "shoacast_register_metabox" );
add_filter('enter_title_here', 'shoa_change_title_text' );

add_action( 'created_section', 'section_save_term_fields' );
add_action( 'edited_section', 'section_save_term_fields' );
add_filter( 'simple_register_taxonomy_settings', 'section_fields' );
add_action( 'section_edit_form_fields', 'section_edit_term_fields', 10, 2 );
add_action( 'section_add_form_fields', 'section_add_term_fields' );


add_action( 'created_shoa_guests', 'shoa_guests_save_term_fields' );
add_action( 'edited_shoa_guests', 'shoa_guests_save_term_fields' );
add_filter( 'simple_register_taxonomy_settings', 'shoa_guests_fields' );
add_action( 'shoa_guests_edit_form_fields', 'shoa_guests_edit_term_fields', 10, 2 );
add_action( 'shoa_guests_add_form_fields', 'shoa_guests_add_term_fields' );


add_filter('manage_shoa-podcast_posts_columns', "shoa_series_columns");
add_action('manage_shoa-podcast_posts_custom_column', "shoa_series_columns_content", 10, 2);


add_action( 'save_post', 'shoacast_save_post' );

function shoacast_register_admin_assets(){

    //for administartor
    wp_register_script("shoa_admin_script" , SHOA_URI . '/assets/js/admin-script.min.js' , ['jquery'] , filemtime(SHOA_PATH . 'assets/js/admin-script.min.js') , true);
    wp_register_style("shoa_admin_style" , SHOA_URI . '/assets/css/admin-style.css' , [] , filemtime(SHOA_PATH . 'assets/css/admin-style.css' ) );

}

function shoacast_save_post($post_id){

    if(get_post_type($post_id) == "shoa-podcast"){

        if( isset($_POST['shoa_parts_list']) && !empty($_POST['shoa_parts_list']) ){
            update_post_meta($post_id , "shoa_parts" , $_POST['shoa_parts_list'] );
        }

        if(isset($_POST['shoa_sound_file_url']) && !empty($_POST['shoa_sound_file_url'])){
            update_post_meta($post_id , "shoa_sound_file_url" , sanitize_text_field( $_POST['shoa_sound_file_url'] ));
        }
        if(isset($_POST['shoa_video_file_url']) && !empty($_POST['shoa_video_file_url'])){
            update_post_meta($post_id , "shoa_video_file_url" , sanitize_text_field( $_POST['shoa_video_file_url'] ));
        }
        if(isset($_POST['shoa_part_number']) && !empty($_POST['shoa_part_number'])){
            update_post_meta($post_id , "shoa_part_number" , sanitize_text_field( $_POST['shoa_part_number'] ));
        }
        if(isset($_POST['shoa_subtitle']) && !empty($_POST['shoa_subtitle'])){
            update_post_meta($post_id , "shoa_subtitle" , sanitize_text_field( $_POST['shoa_subtitle'] ));
        }

        
    }

}

function shoa_series_columns($columns){
    unset($columns['date']);
    unset($columns['title']);
    unset($columns['taxonomy-shoa_guests']);
    unset($columns['taxonomy-section']);
    
    $columns['podcast_title']   = __("موضوع پادکست");
    $columns['taxonomy-shoa_guests'] = __("مهمان برنامه");
    $columns['taxonomy-section']   = __("فصل" );
    $columns['date']    = __("تاریخ ایجاد");
    return $columns;
}

function shoa_series_columns_content($column, $post_id){
    $part_number = get_post_meta($post_id , "shoa_part_number" , true);

    switch ($column) {     
        case 'podcast_title':
            $shoa_subtitle = get_post_meta($post_id , "shoa_subtitle" ,  true);
            $terms = get_the_terms( get_the_ID(), 'section' );
	        $term_title = isset($terms[0]) && is_object($terms[0]) ? get_term_meta( $terms[0]->term_id, 'shoa-session-title', true ) : '';

            if(isset($shoa_subtitle) && !empty($shoa_subtitle)){
                $term_title = $shoa_subtitle;
            }

            echo "<strong>$term_title - قسمت $part_number</strong>";
        break;
    }
    
}

function shoacast_init(){
    //register post types
    shoacast_register_post_type();
    
    //register taxonomies
    shoacast_register_taxonomy();
}

function shoa_change_title_text( $title ){
     $screen = get_current_screen();
   
     if  ( 'shoa-podcast' == $screen->post_type ) {
          $title = 'موضوع';
     }
   
     return $title;
}
   
function shoacast_register_taxonomy(){

    $labels = array(
        'name' => 'مهمان ها',
        'singular_name' => 'مهمان',
        'search_items' =>  'جستجو مهمان',
        'all_items' => 'همه مهمان ها',
        'parent_item' => 'مادر مهمان',
        'parent_item_colon' => __( 'مادر مهمان:' ),
        'edit_item' => __( 'ویرایش مهمان' ), 
        'update_item' => __( 'به روزرسانی مهمان' ),
        'add_new_item' => __( 'افزودن مهمان جدید' ),
        'new_item_name' => __( 'نام مهمان جدید' ),
        'menu_name' => __( 'مهمان ها' ),
        'not_found'  => __( 'هیچ مهمانی یافت نشد!' ),
    );    
 
        // Now register the taxonomy
    register_taxonomy('shoa_guests',array('shoa-podcast'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => false,
        'show_admin_column' => true,
        'query_var' => true,
    ));


    $labels = array(
        'name' => 'قسمت ها',
        'singular_name' => 'قسمت',
        'search_items' =>  'جستجو قسمت',
        'all_items' => 'همه قسمت ها',
        'parent_item' => 'مادر قسمت',
        'parent_item_colon' => __( 'مادر قسمت:' ),
        'edit_item' => __( 'ویرایش قسمت' ), 
        'update_item' => __( 'به روزرسانی قسمت' ),
        'add_new_item' => __( 'افزودن قسمت جدید' ),
        'new_item_name' => __( 'نام قسمت جدید' ),
        'menu_name' => __( 'قسمت ها' ),
        'not_found'  => __( 'هیچ قسمتی یافت نشد!' ),
    );    
 
        // Now register the taxonomy
    register_taxonomy('section',array('shoa-podcast'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => false,
        'show_admin_column' => true,
        'query_var' => true,
    ));

    $labels = array(
        'name' => 'فصل ها',
        'singular_name' => 'فصل',
        'search_items' =>  'جستجو فصل',
        'all_items' => 'همه فصل ها',
        'parent_item' => 'مادر فصل',
        'parent_item_colon' => __( 'مادر فصل:' ),
        'edit_item' => __( 'ویرایش فصل' ), 
        'update_item' => __( 'به روزرسانی فصل' ),
        'add_new_item' => __( 'افزودن فصل جدید' ),
        'new_item_name' => __( 'نام فصل جدید' ),
        'menu_name' => __( 'فصل ها' ),
        'not_found'  => __( 'هیچ فصلی یافت نشد!' ),
    );    

    // Now register the taxonomy
    register_taxonomy('season',array('shoa-podcast'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => false,
        'show_admin_column' => true,
        'query_var' => true,
    ));

}

function shoacast_register_post_type(){
    $labels = array(
        'name'                => 'پادکست ها',
        'singular_name'       => 'پادکست',
        'menu_name'           => 'پادکست ها',
        'all_items'           => 'همه پادکست ها',
        'view_item'           => 'نمایش',
        'add_new_item'        => 'افزودن پادکست جدید',
        'add_new'             => 'افزودن',
        'edit_item'           => 'ویرایش پادکست',
        'update_item'         => 'به روزرسانی پادکست',
        'search_items'        => 'جستجو',
        'not_found'           => 'هیچ پادکستی پیدا نشد!',
        'not_found_in_trash'  => 'هیچ پادکستی در زباله دان پیدا نشد!'
    );
    register_post_type('shoa-podcast', array(
        'label'               => 'پادکست ها',
        'description'         => 'پادکست های شعاع',
        'labels'              => $labels,
        'supports'            => array('thumbnail'  , 'editor' , 'title'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'podcasts' ),
        'capability_type'     => 'post',
        // 'show_in_rest' => true,
    ));         
}

function shoacast_register_metabox(){
    add_meta_box(
        'shoacast_pocast_details',
        'تنظیمات پادکست',
        'shoacast_podcast_details_metabox',
        'shoa-podcast',
    );
}

function shoacast_podcast_details_metabox($post){
    include SHOA_PATH . 'template/podcast-metabox.php';
}

function shoa_guests_add_term_fields( $taxonomy ) {
    ?>
	<div class="form-field">
        <label for="shoa-guest-position">سمت مهمان</label>
        <input type="text" name="shoa-guest-position" id="shoa-guest-position" />
        <p>مثلا بنیانگذار دیجی‌کالا</p>
	</div>

    <div class="form-field">
        <label for="shoa-guest-position">تصویر مهمان</label>
        <input type="hidden" name="shoa-guest-avatar" id="shoa-guest-avatar" class="shoa-guest-avatar" />
        <button class="button guest-select-avatar" name="guest-select-avatar" id="guest-select-avatar" >انتخاب تصویر مهمان</button>
        <div style="margin-top : 16px">
            <img class="guest-select-avatar-img" src="http://localhost/wp-content/uploads/2022/06/pngtree-businessman-user-avatar-character-vector-illustration-png-image_2242909.jpg" width="64" height="64">
        </div>
	</div>
    <?php
    wp_enqueue_media();
    wp_enqueue_script("shoa_admin_script");

}

function shoa_guests_edit_term_fields( $term, $taxonomy ) {

	$value = get_term_meta( $term->term_id, 'shoa-guest-position', true );
	$avatar = get_term_meta( $term->term_id, 'shoa-guest-avatar', true );
	?>
	<tr class="form-field">
        <th>
            <label for="shoa-guest-position">سمت مهمان</label>
        </th>
        <td>
            <input name="shoa-guest-position" id="shoa-guest-position" type="text" value="<?php echo esc_attr( $value ) ?>" />
            <p class="description">مثلا بنیانگذار دیجی‌کالا</p>
        </td>
	</tr>

    <tr class="form-field">
        <th>
            <label for="shoa-guest-position">تصویر مهمان</label>
        </th>
        <td>
            <input type="hidden" name="shoa-guest-avatar" id="shoa-guest-avatar" class="shoa-guest-avatar" value="<?php echo $avatar; ?>" />
            <button class="button guest-select-avatar" name="guest-select-avatar" id="guest-select-avatar" >انتخاب تصویر مهمان</button>
            <div style="margin-top : 16px">
                <img class="guest-select-avatar-img" src="<?php echo $avatar; ?>" width="64" height="64">
            </div>
        </td>
	</tr>

    <?php
    wp_enqueue_media();
    wp_enqueue_script("shoa_admin_script");

}

function shoa_guests_save_term_fields( $term_id ) {

	update_term_meta(
		$term_id,
		'shoa-guest-position',
		sanitize_text_field( $_POST[ 'shoa-guest-position' ] )
	);

    update_term_meta(
		$term_id,
		'shoa-guest-avatar',
		sanitize_text_field( $_POST[ 'shoa-guest-avatar' ] )
	);

}

function shoa_guests_fields( $fields ) {

	$fields[] = array(
 		'id'	=> 'shoaGuestPosition',
 		'taxonomy' => array( 'shoa_guests' ),
 		'fields' => array(
			array(
				'id' => 'shoa-guest-position',
				'label' => 'Text Field',
				'type' => 'text',
			),
 		)
 	);

    $fields[] = array(
 		'id'	=> 'shoaGuestAvatar',
 		'taxonomy' => array( 'shoa_guests' ),
 		'fields' => array(
			array(
				'id' => 'shoa-guest-avatar',
				'label' => 'لینک تصویر مهمان',
				'type' => 'hidden',
			),
 		)
 	);

	return $fields;

}

function section_add_term_fields( $taxonomy ) {
    ?>
	<div class="form-field">
        <label for="shoa-session-title">موضوع قسمت</label>
        <input type="text" name="shoa-session-title" id="shoa-session-title" />
        <p>مثلا مقیاس پذیری (اسکیل آپ)</p>
	</div>
    <?php
}

function  section_edit_term_fields( $term, $taxonomy ) {

	$value = get_term_meta( $term->term_id, 'shoa-session-title', true );
	?>
	<tr class="form-field">
        <th>
            <label for="shoa-session-title">موضوع فصل</label>
        </th>
        <td>
            <input name="shoa-session-title" id="shoa-session-title" type="text" value="<?php echo esc_attr( $value ) ?>" />
            <p class="description">مثلا مقیاس پذیری (اسکیل آپ)</p>
        </td>
	</tr>

    <?php

}

function  section_save_term_fields( $term_id ) {

	update_term_meta(
		$term_id,
		'shoa-session-title',
		sanitize_text_field( $_POST[ 'shoa-session-title' ] )
	);

}

function section_fields( $fields ) {

	$fields[] = array(
 		'id'	=> 'shoaSessionTitle',
 		'taxonomy' => array( 'section' ),
 		'fields' => array(
			array(
				'id' => 'shoa-seesion-title',
				'label' => 'Text Field',
				'type' => 'text',
			),
 		)
 	);


	return $fields;

}