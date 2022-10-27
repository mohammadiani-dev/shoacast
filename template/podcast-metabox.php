<?php
    $sound_url = get_post_meta($post->ID , "shoa_sound_file_url" , true);
    $video_url = get_post_meta($post->ID , "shoa_video_file_url" , true);
    $part_number = get_post_meta($post->ID , "shoa_part_number" , true);
    $shoa_subtitle = get_post_meta($post->ID , "shoa_subtitle" , true);

    $parts = get_post_meta($post->ID , "shoa_parts" , true);

    $parts = isset($parts) ? $parts : [];

    $parts = json_decode($parts);

?>



<div class="shoa-parts">
    <?php foreach($parts as $part): ?>
    <div class="part">
        <div class="remove">
            <span class="dashicons dashicons-remove"></span>
            <span>حذف</span>
        </div>

        <div class="field_wrapper">
            <div class="input_wrapper">
                <div class="meta_details">

                    <div>
                        <label>شماره بخش</label>
                        <input type="text" class="shoa_part_number" placeholder="مثلا بخش اول" value="<?php echo $part->number; ?>">
                    </div>

                    <div>
                        <label>زیر عنوان</label>
                        <input type="text" class="shoa_subtitle" placeholder="زیر عنوان (اختیاری)" value="<?php echo $part->subtitle; ?>">
                    </div>

                </div>

                <div class="meta_details">

                    <div>
                        <label>لینک فایل صوتی</label>
                        <input type="text" class="shoa_sound_file_url" placeholder="https://" dir="ltr" value="<?php echo $part->sound_file_url; ?>">
                    </div>

                    <div>
                        <label>لینک فایل ویدئویی یا شناسه آپارات</label>
                        <input type="text" class="shoa_video_file_url" placeholder="https://     Or     aparat video ID" dir="ltr" value="<?php echo $part->video_file_url; ?>">
                    </div>

                </div>
            </div>
            <div class="image_select">
                <img src="<?php echo isset($part->part_thumbanil) && (int)$part->part_thumbanil > 0 ? wp_get_attachment_url($part->part_thumbanil) : '../assets/img/placeholder.png'; ?>">
                <input type="hidden" class="part_thumbanil" value="<?php echo isset($part->part_thumbanil) ? $part->part_thumbanil : "" ?>">
            </div>
        </div>

    </div>
    <?php endforeach; ?>
    
    <input type="hidden" id="shoa_parts_list" name="shoa_parts_list">
</div>

<button class="button" id="add_shoa_part">افزودن بخش</button>






<?php

wp_enqueue_style("shoa_admin_style");
wp_enqueue_script("shoa_admin_script");