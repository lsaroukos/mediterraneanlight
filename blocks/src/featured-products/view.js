//import './view.scss';

import DOMUtils from "../../../assets/src/js/utils/DOMUtils";

document.addEventListener('DOMContentLoaded', () => {

    const featuredProductsBlocks = document.querySelectorAll(".featured-products-block");
    featuredProductsBlocks.forEach( block=>{

        DOMUtils.loadContent( block.id, import("./lib/FeaturedProductsSlider") );

    });
    
});