jQuery(document).ready(function ($) {
   
    
    $(document).on("click", ".guest-select-avatar", function (event) {
        
        var frame;
        var file_link_input = $(this).closest(".form-field").find(".shoa-guest-avatar");
        var avatar_show_image = $(this).closest(".form-field").find(".guest-select-avatar-img");
        event.preventDefault();

        if (frame) {
        frame.open();
        return;
        }

        frame = wp.media({
        title: "تصویر مهمان را انتخاب کنید",
        button: {
            text: "انتخاب تصویر",
        },
        multiple: false,
        });

        frame.on("select", function () {
        var attachment = frame.state().get("selection").first().toJSON();
        
        file_link_input.val(attachment.url);
        avatar_show_image.attr( "src" , attachment.url);
        // imgIdInput.val(attachment.id);
        });

        frame.open();
        
    });

    $(document).on("click", "#add_shoa_part", function (event) {
        event.preventDefault();
        $(".shoa-parts").append(`
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
                                <input type="text" class="shoa_part_number" placeholder="مثلا بخش اول" value="">
                            </div>

                            <div>
                                <label>زیر عنوان</label>
                                <input type="text" class="shoa_subtitle" placeholder="زیر عنوان (اختیاری)" value="">
                            </div>

                        </div>

                        <div class="meta_details">

                            <div>
                                <label>لینک فایل صوتی</label>
                                <input type="text" class="shoa_sound_file_url" placeholder="https://" dir="ltr" value="">
                            </div>

                            <div>
                                <label>لینک فایل ویدئویی یا شناسه آپارات</label>
                                <input type="text" class="shoa_video_file_url" placeholder="https://     Or     aparat video ID" dir="ltr" value="">
                            </div>

                        </div>
                    </div>
                    <div class="image_select">
                        <img src="../wp-content/plugins/shoacast/assets/img/placeholder.png">
                        <input type="hidden" class="part_thumbanil">
                    </div>
                </div>
            </div>
        `);
    });

    $(document).on("click", ".shoa-parts .part .remove", function (e) {
        e.preventDefault();
        $(this).closest(".part").remove();
    });

    $(document).one(
     "click",
     "div#major-publishing-actions #publish",
     function (e) {
       e.preventDefault();
       $("#shoa_parts_list").val(JSON.stringify(get_podcast_parts()));
       $(this).trigger("click");
     }
    );
    
    $(document).on("click", ".shoa-parts .part .image_select img", function (e) {
        var frame;
        var image_place_holder = $(this);
        var file_link_input = $(this).closest(".image_select").find(".part_thumbanil");
        e.preventDefault();

        if (frame) {
        frame.open();
        return;
        }

        frame = wp.media({
        title: "تصویر شاخص بخش را انتخاب کنید",
        button: {
            text: "انتخاب تصویر شاخص",
        },
        multiple: false,
        });

        frame.on("select", function () {
            var attachment = frame.state().get("selection").first().toJSON();
            
            file_link_input.val(attachment.id);
            image_place_holder.attr("src", attachment.url);
        // imgIdInput.val(attachment.id);
        });

        frame.open();
    })

    function get_podcast_parts() {
        var parts = [];
        $.each( $(".shoa-parts .part") , function (i , v) {
            var part = {};
            part.number = $(this).find(".shoa_part_number").val();
            part.subtitle = $(this).find(".shoa_subtitle").val();
            part.sound_file_url = $(this).find(".shoa_sound_file_url").val();
            part.video_file_url = $(this).find(".shoa_video_file_url").val();
            part.part_thumbanil = $(this).find(".part_thumbanil").val();
            parts.push(part);
        });
        return parts;
    }

    
});