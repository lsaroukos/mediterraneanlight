import "./index.scss"
import metadata from './block.json';
import {  useBlockProps, InnerBlocks } from '@wordpress/block-editor';

wp.blocks.registerBlockType( metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
})

function EditComponent( props ) {

    const blockProps = useBlockProps({ className: "mobile-menu-nav"});    

    return (
        <div {...blockProps} >
            <InnerBlocks allowedBlocks={["medlight/mobile-menu-nav-item"]}/>
        </div>
    );
}


/**
 * Dynamic PHP
 * @returns 
 */
function SaveComponent( ){
    return <InnerBlocks.Content />
}