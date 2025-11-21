import './view.scss';

import DOMUtils from "../../../assets/src/js/utils/DOMUtils";

document.addEventListener('DOMContentLoaded', () => {

    const mobileMenus = document.querySelectorAll(".mobile-menu");
    mobileMenus.forEach( menu=>{

        DOMUtils.loadContent( menu.id, import("./lib/MobileMenu.js") );

    });
    
});