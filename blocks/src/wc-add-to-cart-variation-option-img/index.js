import "./index.scss"
import metadata from './block.json';
import {  useBlockProps } from '@wordpress/block-editor';

wp.blocks.registerBlockType( metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: ()=>null,
})

function EditComponent( props ) {

    const blockProps = useBlockProps();
  

    return (
        <div {...blockProps} >
            variation term images will be rendered here
        </div>
    );
}