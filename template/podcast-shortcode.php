<div id="shoacast-podcast-list" class="shoa-podcast-list-wrapper shoa-component">

    <div class="header-title-and-filter">
        <div class="title-section">
            <h3><span>مهمانان شعاع</span></h3>
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

</div>