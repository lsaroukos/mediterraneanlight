import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux'


export default class DOMUtils{

    /**
     * 
     * @param {string} elId id of dom element container 
     * @param {string} component component to load e.g. ./js/Components/Core.js
     * @returns 
     */
    static loadContent = async (elId, component )=>{

        const container = document.getElementById(elId);    // get dom container
        if (!container || !component ) return;

        // 🔥 WAIT HERE for global store
        const store = await window.medlightStoreReady;

        // Convert all data attributes to props
        const props = {};
        for (const key in container.dataset) {
            let value = container.dataset[key];
            // Try to parse boolean/number values
            if (value === 'true') value = true;
            else if (value === 'false') value = false;
            else if (!isNaN(value)) value = Number(value);

            props[key] = value;
        }

        component.then(({ default: Component }) => {
            const root = createRoot(container);
            const element = <Component {...props} />;

            root.render(
                <Provider store={store}>
                    {element}
                </Provider>
            );

        }).catch(error => {
            console.error(`Error loading component "${component}":`, error);
        });
    }
}