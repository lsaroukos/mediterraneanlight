import { useEffect, useState } from "react";
import APIUtils from "../../../../assets/src/js/utils/APIUtils";
import SettingsUtils from "../../../../assets/src/js/utils/SettingsUtils";
import { trns } from "../../../../assets/src/js/utils/TranslationUtils";
import { Spinner } from '@wordpress/components';
import PostUtils from "../../../../assets/src/js/utils/PostUtils";

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";
import "swiper/css/grid";


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


    if ( relatedPosts===undefined ) return <Spinner />;

    if ( relatedPosts.length===0 ) return <p>{ trns("no_posts_found") }</p>;

    return (
        <Swiper
            className="related-posts-slider"
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
                    slidesPerView: 3,
                },
            }}
        >
            {relatedPosts.map((post) => (
                <SwiperSlide key={post.id}>
                    <div className="post-template">
                        <a className="img-container" href={post.link} aria-label={`link to post ${post.title}`}>
                            <img src={post.thumbnail} alt={post.title} />
                        </a>
                        <div className="post-details">
                            <p><a className="post-title seemless-link" href={post.link}>{post.title}</a></p>
                            <p className="post-excerpt">{post.excerpt}</p>
                        </div>
                    </div>
                </SwiperSlide>
            ))}
        </Swiper>
    );
}