import { useEffect, useState } from "react";
import APIUtils from "../../../../assets/src/js/utils/APIUtils";
import { __ } from "@wordpress/i18n";
import { Spinner } from '@wordpress/components';

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";
import "swiper/css/grid";
import DateTimeUtils from "../../../../assets/src/js/utils/DateTimeUtils";
import PostUtils from "../../../../assets/src/js/utils/PostUtils";


export default function RelatedPostsSlider({ attributes, setAttributes }) {
   
    const [relatedPosts, setRelatedPosts] = useState(undefined);

    /**
     * fetch posts from db
     */
    useEffect(() => {
        const postId = PostUtils.get_post_id();
        const url = `posts/related/${postId}`;   // related posts endpoint 
        APIUtils.get( url ).then(response => {
            if( response.status==="success" ){
                setRelatedPosts( response.posts );
            }
            
        });
    }, []);


    if ( latestPosts===undefined ) return <Spinner />;

    if ( latestPosts.length===0 ) return <p>{ __("no posts found") }</p>;

    return (
        <Swiper
            className="latest-posts-slider"
            spaceBetween={20}
            breakpoints={{
                //  Mobile
                0: {
                    slidesPerView: 1,
                },

                //  Tablet (3 per row)
                640: {
                    slidesPerView: 2,
                },

                //  Desktop (6 items -> 2 rows × 3 columns)
                1024: {
                    slidesPerView: 3,
                },
            }}
        >
            {relatedPosts.map((post) => (
                <SwiperSlide key={post.id}>
                    <div className="post-template">
                        <a className="img-container" href={post.link} aria-label={`link to post ${post.post_title}`}>
                            <img src={post.thumbnail} alt={post.post_title} />
                        </a>
                        <div className="post-details">
                            <p className="post-date">{ DateTimeUtils.isoToLocaleDate(post.post_date) }</p>
                            <p><a className="post-title seemless-link" href={post.link}>{post.post_title}</a></p>
                            <p><a className="seemless-link" href={post.link}>{ __("Read more") } ↗</a></p>
                        </div>
                    </div>
                </SwiperSlide>
            ))}
        </Swiper>
    );
}