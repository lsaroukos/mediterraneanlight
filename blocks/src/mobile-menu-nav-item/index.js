import "./index.scss"
import metadata from './block.json';
import {  useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { RichText } from '@wordpress/block-editor';
import { useState } from "react";


wp.blocks.registerBlockType( metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
})

function EditComponent( { attributes, setAttributes } ) {

    
    const [isOpen, setIsOpen] = useState(false);
    
    const blockProps = useBlockProps({ className: "mobile-menu-nav-item"+(isOpen ? " selected" : "")});    

    return (
        <li {...blockProps} >
            <span>
                <RichText
                    { ...blockProps }
                    tagName="a" // The tag here is the element output and editable in the admin
                    value={ attributes.link } // Any existing content, either from the database or an attribute default
                    allowedFormats={ [ 'core/bold','core/link' ] } // Allow the content to be made bold or italic, but do not allow other formatting options
                    onChange={ ( content ) => setAttributes( { link:content } ) } // Store updated content as a block attribute
                    placeholder={  'title...' } // Display this text before any content has been added by the user
                />
            </span>
            <span className="open-button" onClick={()=>{setIsOpen(true)}}>{ ">" }</span>
            { isOpen && (
                <ul className="submenu">
                    <li className="submenu-label" onClick={()=>setIsOpen(false)}><span>{attributes.link}</span><span>⮐</span></li>
                    <InnerBlocks allowedBlocks={["medlight/mobile-menu-nav-item"]}/>
                </ul>
            )}
        </li>
    );
}


/**
 * Dynamic PHP
 * @returns 
 */
function SaveComponent( ){
    return <InnerBlocks.Content />
}