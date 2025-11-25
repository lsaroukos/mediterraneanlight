export default class TogglerUtils{

    static layoutMap = {
        grid  : ["wc-block-product-template__responsive","columns-3","wc-block-product-template","product-items","wp-block-woocommerce-product-template","is-layout-flow","wp-block-product-template-is-layout-flow"],
        grid2 : ["wc-block-product-template__responsive","columns-2","wc-block-product-template","product-items","wp-block-woocommerce-product-template","is-layout-flow","wp-block-product-template-is-layout-flow"],
        list  : ["is-product-collection-layout-list","wc-block-product-template","product-items","wp-block-woocommerce-product-template","is-layout-flow","wp-block-product-template-is-layout-flow"]
    };

    static switchLayout = ( layout )=>{

        if( !this.layoutMap.hasOwnProperty( layout) )    // check if layout option is valid
            return;
        
        const container = document.querySelector(".wp-block-woocommerce-product-template");
        if( !container )    // exit if there is no product listing block on the page
            return;

        const newClasses = this.layoutMap[layout];

        container.classList.remove(...container.classList); // Clear existing classes
        container.classList.add(...newClasses ); // Add new classes
    }

    static getLayout = ()=>{
        let container = document.querySelector(".shop-layout-toggler .active");
        if( !container ) {
            container = document.querySelector(".wp-block-woocommerce-product-template"); 
            if( !container )
                return null;
            else{
                if( container.classList.contains("is-product-collection-layout-list") )
                    return "list";
                else if( container.classList.contains("columns-2") )
                    return "grid2";
                else
                    return "grid";
            }
                
        }
        
        return container.getAttribute('data-layout');

    }

}