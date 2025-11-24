import "./index.scss"
import metadata from './block.json';
import { InnerBlocks, InspectorControls, useBlockProps } from "@wordpress/block-editor";
import { useEffect, useState } from "react";
import APIUtils from "../../../assets/src/js/utils/APIUtils";
import LanguageSettings from "./settings/LanguageSettings";

wp.blocks.registerBlockType( metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
})

function EditComponent( {attributes, setAttributes} ) {

    const blockProps = useBlockProps({ className: "language-content-block"});
    const [languages, setLanguages] = useState([]);

    /**
     * 
     */
    useEffect(()=>{

        const url = "translations/languages";
        APIUtils.get( url ).then( response=>{
            if( response.status==="success" )
                setLanguages( response.languages ); 
        }).catch(e=>console.log(e));

    },[])

    return (
        <div {...blockProps} >
            <InspectorControls>
                <LanguageSettings attributes={attributes} setAttributes={setAttributes}  />
            </InspectorControls>
            <InnerBlocks />
        </div>
    );
}


function SaveComponent( ){
    return <InnerBlocks.Content />
}