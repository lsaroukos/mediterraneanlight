//import './view.scss';

import DOMUtils from "../../../assets/src/js/utils/DOMUtils";

document.addEventListener('DOMContentLoaded', () => {

    const relatedPostsBlocks = document.querySelectorAll(".related-posts-slider-block");
    relatedPostsBlocks.forEach( block=>{

        DOMUtils.loadContent( block.id, import("./lib/RelatedPostsSlider") );

    });
    
});