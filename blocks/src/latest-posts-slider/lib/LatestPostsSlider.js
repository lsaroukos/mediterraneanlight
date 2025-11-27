import { useEffect, useState } from "react";
import APIUtils from "../../../../assets/src/js/utils/APIUtils";
import { __ } from "@wordpress/i18n";
import { Spinner } from '@wordpress/components';

import { Swiper, SwiperSlide } from "swiper/react";
import { Grid } from "swiper/modules";
import "swiper/css";
import "swiper/css/grid";
import DateTimeUtils from "../../../../assets/src/js/utils/DateTimeUtils";
import SettingsUtils from "../../../../assets/src/js/utils/SettingsUtils";


export default function LatestPostsSlider({ attributes, setAttributes }) {
   
    const [latestPosts, setLatestPosts] = useState(undefined);

    /**
     * fetch posts from db
     */
    useEffect(() => {
        const url = `posts`;   // latest 6 posts from wp rest api 
        APIUtils.get( url ).then(response => {
            if( response.status==="success" ){
                setLatestPosts( response.posts );
            }
            
        });
    }, []);


    if ( latestPosts===undefined ) return <Spinner />;

    if ( latestPosts.length===0 ) return <p>{ __("no posts found") }</p>;

    return (
        <Swiper
            className="latest-posts-slider"
            modules={[Grid]}
            spaceBetween={20}
            breakpoints={{
                //  Mobile
                0: {
                    slidesPerView: 1,
                },

                //  Tablet (3 per row)
                [SettingsUtils.getBreakPoint("mobile")]: {
                    slidesPerView: 2,
                },

                //  Desktop (6 items -> 2 rows × 3 columns)
                [SettingsUtils.getBreakPoint("laptop")]: {
                    slidesPerView: 4,
                },
            }}
        >
            {latestPosts.map((post) => (
                <SwiperSlide key={post.id}>
                    <div className="post-template">
                        <a className="img-container" href={post.link} aria-label={`link to post ${post.title}`}>
                            <img src={post.thumbnail} alt={post.title} />
                        </a>
                        <div className="post-details">
                            <p className="post-date">{ DateTimeUtils.isoToLocaleDate(post.date) }</p>
                            <p><a className="post-title seemless-link" href={post.link}>{post.title}</a></p>
                            <p><a className="seemless-link" href={post.link}>{ __("Read more") } ↗</a></p>
                        </div>
                    </div>
                </SwiperSlide>
            ))}
        </Swiper>
    );
}