import './scss/constants.scss';
import './scss/responsive.scss';
import './scss/main.scss';
import "./scss/index.scss";
import "./scss/header.scss";
import "./scss/typography.scss";
import "./scss/footer.scss";
import store from "./js/redux/store";
import DOMUtils from "./js/utils/DOMUtils.js"


window.medlightStore = store; // stores the store instance in window clobaly

// promise resolves immediately since store is ready here
window.medlightStoreReady = Promise.resolve(store);


document.addEventListener('DOMContentLoaded', () => {

    DOMUtils.loadContent( "medlight-core", import("./js/Components/Core.js") );

});