//import './view.scss';

import DOMUtils from "../../../assets/src/js/utils/DOMUtils";

document.addEventListener('DOMContentLoaded', () => {

    const searchTogglers = document.querySelectorAll(".search-toggler");
    searchTogglers.forEach( menu=>{

        DOMUtils.loadContent( menu.id, import("./lib/SearchDrawer.js") );

    });
    
});