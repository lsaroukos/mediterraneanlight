import { useEffect, useState } from "react";
import APIUtils from "../utils/APIUtils";


export default function Shop(){

    const [swatches, setSwatches] = useState({});

    useEffect(()=>{
    
        const inputs  = document.querySelectorAll(".wc-block-add-to-cart-with-options-variation-selector-attribute-options__pill-input");

        let terms = {};
        inputs.forEach( input => {
            
            const taxonomy = input.name.replace(/^attribute_/, ""); // get taxonomy
            const value = input.value;  // get term value (slug)
            // Initialize array if missing
            if (!terms[taxonomy]) {
                terms[taxonomy] = [];
            }

            if( !terms[taxonomy].includes(value) ){
                terms = {
                    ...terms,
                    [taxonomy] : [...terms[taxonomy], value]  
                }; 
            }

        });

        if( Object.keys(terms).length<0 )
            return;

        // fetch term images
        APIUtils.post('wc/terms/images',{terms:terms}).then( response=>{
            if( response.status==="success" )
                setSwatches( response.terms );
        }).catch( error=>console.log(error) );

    },[]);

    useEffect(()=>{
        
        if( Object.keys(swatches)===0 )
            return;

        Object.entries( swatches ).forEach( ([taxonomy, terms])=>{

            Object.entries( terms ).forEach( ([termName, url])=>{
                
                if( url!=="" ){

                    let input = document.querySelector("input[name='attribute_"+taxonomy+"'][value='"+termName+"']"); // get relevant term input
                    let label = input?.parentNode;
                    if( label ){
                        let img = document.createElement("img");
                        img.setAttribute("src",url);
                        img.classList.add("medlight-swatch-img");
                        label.childNodes.forEach( child=>{
                            if( child.nodeName==="#text" )
                                label.removeChild(child);
                        });
                        label.classList.add('has-swatch');
                        label.appendChild(img);
                    }
                } 
                    

            });
        });

    }, [swatches] );

}