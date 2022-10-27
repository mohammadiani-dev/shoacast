const vue = require("./vue");

jQuery(document).ready(function ($) {

   $(".shoa-podcast-list-wrapper .guest-carousel").owlCarousel({
    nav: true,
    rtl: true,
    // loop:true,
    responsive: {
      0: {
        items: 2,
      },
      600: {
        items: 4,
      },
      1000: {
        items: 6,
      },
       },
    navText : ["<i class='shoa-slier-perv-icon'></i>","<i class='shoa-slier-next-icon'></i>"]
   }); 
    
    $(document).on("click", ".shoa-component.owl-carousel .owl-item", function () {
        $(".shoa-component.owl-carousel .owl-item.selected").removeClass("selected");
        $(this).addClass("selected");
    });
    
});


var PodcastApp = {
    el: "#shoacast-podcast-list",
    data() {
        return {
            pageLoaded: false,
            podcasts: [],
            podcast: {
                guest: {
                    fullname : '',
                }
            },
            guests: [],
            isplay: false,
            curr_track: null,
            show_music_player: false,
            show_video_player: false,
            serach_text : "",
            sort : "newest",
        };
    },
    methods: {
        filter_podcast_by_guest(term_id) {
            this.podcasts = SHOA_DATA.podcasts.filter((item) => {
                return item.guest.id == term_id
            });
        },
        reset_player() {
            this.$refs.current_time.textContent = "00:00";
            this.$refs.total_time.textContent = "00:00";
            this.$refs.slider_player.value = 0;  
        },
        seekUpdate() {
            let seekPosition = 0;
            if (!isNaN(this.curr_track.duration)) {
                seekPosition = this.curr_track.currentTime * (100 / this.curr_track.duration);
        
                this.$refs.slider_player.value = seekPosition;
        
                let currentMinutes = Math.floor(this.curr_track.currentTime / 60);
                let currentSeconds = Math.floor(this.curr_track.currentTime - currentMinutes * 60);
                let durationMinutes = Math.floor(this.curr_track.duration / 60);
                let durationSeconds = Math.floor(this.curr_track.duration - durationMinutes * 60);
        
                if (currentSeconds < 10) { currentSeconds = "0" + currentSeconds; }
                if (durationSeconds < 10) { durationSeconds = "0" + durationSeconds; }
                if (currentMinutes < 10) { currentMinutes = "0" + currentMinutes; }
                if (durationMinutes < 10) { durationMinutes = "0" + durationMinutes; }
        
                this.$refs.current_time.textContent = currentMinutes + ":" + currentSeconds;
                this.$refs.total_time.textContent = durationMinutes + ":" + durationSeconds;

                var player = this.$refs.slider_player;
                var value = (player.value - player.min) / (player.max - player.min) * 100;
                player.style.background = 'linear-gradient(to right, #000 0%, #000 ' + value + '%, #C4C4C4 ' + value + '%, #C4C4C4 100%)';
                
            }
        },
        seekTo() {
            this.curr_track.currentTime = this.curr_track.duration * (this.$refs.slider_player.value / 100);
        },
        loadTrack() {

            clearInterval(this.updateTimer);

            this.reset_player();  
            this.curr_track.src = this.podcast_url;
            this.curr_track.load();

            this.curr_track.play();

            this.updateTimer = setInterval(() => {
                this.seekUpdate();
            }, 1000);
            
            this.curr_track.addEventListener("ended", () => {
                this.reset_player();
                this.isplay = false;
                this.show_music_player = false;
            });
            
        },
        playpauseTrack(podcast) {

            this.podcast = podcast;

            if (this.podcast.id != podcast.id) {
                // this.reset_player();
                this.isplay = true;
            }

            // this.podcast_url = podcast.audio_url;
            this.show_music_player = true;
            this.show_video_player = false;


            // if (!this.isplay) this.playTrack();
            // else this.pauseTrack();

            
            var player = document.querySelector("shoacast_player");
            this.scrollToJustAbove(player);

        },

        playpauseVideo(podcast) {

            // this.pauseTrack();
            this.podcast = podcast;
            this.show_music_player = false;  
            this.show_video_player = true;

            var player = document.querySelector("shoacast_player");
            this.scrollToJustAbove(player);

            // var video = document.getElementById("shoa_video_player_wrapper");
            // this.scrollToJustAbove(video);
        },
 
        playTrack() {

            var url = this.podcast_url;
            if (this.curr_track.src != url) {
                this.loadTrack();
            }

            // this.curr_track.play();
            this.isplay = true;
        },
 
        pauseTrack() {
            // this.curr_track.pause();
            this.isplay = false;
        },

        scrollToJustAbove(element) {
            console.log(element);
          window.scrollTo({ top: element.offsetTop + 40, behavior: "smooth" });
        },
        compareValues(key, order = 'asc') {
            return function innerSort(a, b) {
                if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
                // property doesn't exist on either object
                return 0;
                }

                const varA = (typeof a[key] === 'string')
                ? a[key].toUpperCase() : a[key];
                const varB = (typeof b[key] === 'string')
                ? b[key].toUpperCase() : b[key];

                let comparison = 0;
                if (varA > varB) {
                comparison = 1;
                } else if (varA < varB) {
                comparison = -1;
                }
                return (
                (order === 'desc') ? (comparison * -1) : comparison
                );
            };
        }
    },
    watch: {        
        serach_text: function (before, value) {

            var selected_guests = document.querySelector(".guest-carousel .owl-item.selected");

            if(selected_guests !== null &&  selected_guests.classList.contains("selected")) {
                selected_guests.classList.remove("selected");
            }

            if (value.length) {
                this.podcasts = SHOA_DATA.podcasts.filter((item) => {
                    return item.title.indexOf(value) > 0 || item.part_name.indexOf(value) > 0 || item.guest.fullname.indexOf(value) > 0  || item.guest.position.indexOf(value) > 0;
                });
            } else {
                this.podcasts = SHOA_DATA.podcasts;
            }
        },
        sort: function (before, value) {
            switch (value) {
                case 'newest':
                    this.podcasts = this.podcasts.sort(this.compareValues("id", 'desc'));
                break;
                case 'oldest':
                    this.podcasts = this.podcasts.sort(this.compareValues("id", 'asc'));
                break;
                case 'popular':
                    this.podcasts = this.podcasts.sort(this.compareValues("id", 'asc'));
                break;
            }
        }
    },
    mounted() {
        this.pageLoaded = true;
        this.podcasts = SHOA_DATA.podcasts;
        this.guests = SHOA_DATA.guests;

        // document.getElementById("shoa_volume_music_slider").oninput = function() {
        //     var value = (this.value-this.min)/(this.max-this.min)*100
        //     this.style.background = 'linear-gradient(to right, #000 0%, #000 ' + value + '%, #C4C4C4 ' + value + '%, #C4C4C4 100%)'
        // };

        this.curr_track = document.createElement('audio');
    },

};
new vue(PodcastApp);