<div id="shoacast-podcast-list" class="shoa-podcast-list-wrapper shoa-component">

    <div class="header-title-and-filter">
        <div class="title-section">
            <h3><span>بشنوید و تماشا کنید</span></h3>
        </div>
        <div class="filter-section">
            <div class="search-box">
                <form>
                    <input type="text" placeholder="جستجو" v-model="serach_text">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.316298 16.1563L4.3875 12.0851C3.40734 10.7802 2.87822 9.19198 2.88 7.56C2.88 3.39144 6.27144 0 10.44 0C14.6086 0 18 3.39144 18 7.56C18 11.7286 14.6086 15.12 10.44 15.12C8.80802 15.1218 7.21975 14.5927 5.9149 13.6125L1.8437 17.6837C1.63762 17.8679 1.36883 17.9663 1.09252 17.9585C0.816206 17.9508 0.553339 17.8376 0.35788 17.6421C0.162422 17.4467 0.0492039 17.1838 0.0414715 16.9075C0.0337391 16.6312 0.132076 16.3624 0.316298 16.1563ZM15.84 7.56C15.84 6.49198 15.5233 5.44795 14.9299 4.55992C14.3366 3.6719 13.4932 2.97976 12.5065 2.57105C11.5198 2.16234 10.434 2.0554 9.38651 2.26376C8.33901 2.47212 7.37683 2.98642 6.62162 3.74162C5.86642 4.49683 5.35212 5.45901 5.14376 6.50651C4.9354 7.55401 5.04234 8.63977 5.45105 9.62649C5.85976 10.6132 6.55189 11.4566 7.43992 12.0499C8.32795 12.6433 9.37198 12.96 10.44 12.96C11.8716 12.9583 13.2442 12.3888 14.2565 11.3765C15.2688 10.3642 15.8383 8.99164 15.84 7.56Z" fill="#0B0B0B"/>
                    </svg>
                </form>
            </div>
            <div class="filter-tabs">
                <div class="label">مرتب سازی</div>
                <ul class="tabs">
                    <li @click="sort = 'newest'"  data-tab="newest" :class="{active : sort=='newest'}">جدید ترین</li>
                    <li @click="sort = 'oldest'"  data-tab="oldest" :class="{active : sort=='oldest'}" >قدیمی ترین</li>
                    <li @click="sort = 'popular'" data-tab="popular" :class="{active : sort=='popular'}">محبوب ترین</li>
                </ul>
            </div>
        </div>
    </div>


    <div class="owl-carousel shoa-component guest-carousel">

        <div class="item" v-for="(guest , index) in guests" :key="index" @click="filter_podcast_by_guest(guest.term_id)">
            <div class="avatar">
                <img :src="guest.avatar" width="128" height="128" :alt="guest.name">
            </div>
            <h5 v-text="guest.name"></h5>
            <p v-text="guest.position"></p>
        </div>

    </div>


    <template v-if="pageLoaded">
        <div class="shoa-podcasts" v-if="podcasts.length">
            <div class="podcast-card" v-for="(post , index) in podcasts" :key="index">

                <div class="image">
                    <img :src="post.image" :alt="post.title">
                </div>
                <div class="desc">

                    <strong class="part-name" v-text="post.part_name"></strong>
                    <p class="part-title" v-text="post.title"></p>

                    <p class="part-guest">
                        <span class="fullname" v-text="post.guest.fullname"></span>
                        <span class="position" v-text="'(' + post.guest.position + ')'"></span>
                    </p>

                    <div class="actions">
                        <span :class="['action-icon'  , { play : post.id != podcast.id || (post.id == podcast.id && !isplay) , pause : isplay && post.id == podcast.id }]" @click="playpauseTrack(post)"></span>
                        <span class="monitor"  @click="playpauseVideo(post)" ></span>
                        <span class="share"></span>
                    </div>

                </div>
            </div>
        </div>
    </template>


    <div class="shoa-music-player-wrapper" id="shoa_music_player_wrapper" v-show="show_music_player">

        <div class="podcast_name">
            <strong v-text="podcast.part_name"></strong>
            <p v-text="podcast.title + ' - ' + podcast.guest.fullname + ' (' + podcast.guest.position + ') '"></p>
        </div>

        <div class="shoa-music-player" >
            <span :class="['action-icon'  , { play : !isplay , pause : isplay }]" @click="playpauseTrack(podcast)" ref="playPauseButton"></span>
            <span   ref="current_time" class="current-time">00:00</span>
            <input  ref="slider_player" id="shoa_volume_music_slider" type="range" min="1" max="100"  value="0" class="seek_slider" @change="seekTo()">
            <span   ref="total_time" class="total-duration">00:00</span>
        </div>
    </div>


    <div class="shoa-video-player-wrapper" id="shoa_video_player_wrapper" v-show="show_video_player">
        <div class="podcast_name">
            <strong v-text="podcast.part_name"></strong>
            <p v-text="podcast.title + ' - ' + podcast.guest.fullname + ' (' + podcast.guest.position + ') '"></p>
        </div>
        <style>.h_iframe-aparat_embed_frame{position:relative;}.h_iframe-aparat_embed_frame .ratio{display:block;width:100%;height:auto;}.h_iframe-aparat_embed_frame iframe{position:absolute;top:0;left:0;width:100%;height:100%;}</style>
        <div class="shoa_video_player h_iframe-aparat_embed_frame">
            <span style="display: block;padding-top: 57%"></span>
            <iframe :src="'https://www.aparat.com/video/video/embed/videohash/' + podcast.video_url + '/vt/frame'" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>
        </div>
    </div>

</div>