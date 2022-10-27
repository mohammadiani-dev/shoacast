<?php

    $pod_id = get_the_ID();

    $parts = get_post_meta($pod_id , "shoa_parts" , true);

    $parts = isset($parts) ? $parts : [];

    $parts = json_decode($parts);

    $video_embed_id = isset($parts[0]->video_file_url) ? $parts[0]->video_file_url : "";
    $podcast_embed_url = isset($parts[0]->sound_file_url) ? $parts[0]->sound_file_url : "";
    $partname = isset($parts[0]->number) ? 'بخش ' . $parts[0]->number : "";
?>

<div class="single-shoa-player-wrapper" id="shoa_player_section">

    <div class="wrapper_tabs_name">
        <div class="tabs">
            <div class="active" data-menu-type="podcast">پادکست</div>
            <div data-menu-type="videocast">ویدئوکست</div>
        </div>
        <span class="part-name"><?php echo $partname ?></span>
    </div>

    <div class="player-item active shoa-podcast-player">
        <iframe src="" frameborder="0" width="100%" height="200"></iframe>
    </div>

    <div class="player-item shoa-videocast-player">
        <style>
            .h_iframe-aparat_embed_frame{position:relative;border-radius: 12px;overflow: hidden;}
            .h_iframe-aparat_embed_frame .ratio{display:block;width:100%;height:auto;}
            .h_iframe-aparat_embed_frame iframe{position:absolute;top:0;left:0;width:100%;height:100%;}
            </style>
            <div class="h_iframe-aparat_embed_frame">
                <span style="display: block;padding-top: 57%"></span>                    
                <iframe src="" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>
            </div>
    </div>
 
</div>



<script>
    jQuery(document).ready(function($){
        var podcast_url = "<?php echo $podcast_embed_url; ?>";
        var vidcast_url = "https://www.aparat.com/video/video/embed/videohash/"+"<?php echo $video_embed_id; ?>"+"/vt/frame";
        setTimeout(function(){
            $(".shoa-podcast-player iframe").attr("src" , podcast_url);
            $(".shoa-videocast-player iframe").attr("src" , vidcast_url);
        } , 1000);
        $(".single-shoa-player-wrapper .tabs > div").click(function(){

            $(".single-shoa-player-wrapper .tabs div.active").removeClass("active");

            $(this).addClass("active");

            var type = $(this).data("menu-type");

            $('.player-item.active').removeClass("active");

            $(".shoa-" + type + "-player").addClass("active");
            
        });
    });
</script>