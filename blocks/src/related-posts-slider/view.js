//import './view.scss';

import DOMUtils from "../../../assets/src/js/utils/DOMUtils";

document.addEventListener('DOMContentLoaded', () => {

    const latestPostsBlocks = document.querySelectorAll(".latest-posts-slider-block");
    latestPostsBlocks.forEach( block=>{

        DOMUtils.loadContent( block.id, import("./lib/LatestPostsSlider") );

    });
    
});