//import './view.scss';

import DOMUtils from "../../../assets/src/js/utils/DOMUtils";

document.addEventListener('DOMContentLoaded', () => {

    const togglerButtons = document.querySelectorAll(".shop-layout-toggler-block");
    togglerButtons.forEach( block=>{

        DOMUtils.loadContent( block.id, import("./lib/TogglerButtons") );

    });
    
});