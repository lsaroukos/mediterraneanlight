import "./index.scss"
import metadata from './block.json';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { RichText } from '@wordpress/block-editor';

wp.blocks.registerBlockType( metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
})

function EditComponent( { clientId, attributes, setAttributes } ) {

    const blockProps = useBlockProps({className:"megamenu-element" });

    const isSelectedOrChildSelected = useSelect( ( select ) => {
        const { getSelectedBlockClientId, getBlockParents } = select( blockEditorStore );
        const selectedId = getSelectedBlockClientId();

        if ( ! selectedId ) return false;

        const parents = getBlockParents( selectedId ); // array of clientIds

        return (
            selectedId === clientId ||  // parent selected
            parents.includes( clientId ) // any descendant selected
        );
    }, [ clientId ] );
    
    return (
        <div {...blockProps} >
            <RichText
                { ...blockProps }
                tagName="a" // The tag here is the element output and editable in the admin
                value={ attributes.link } // Any existing content, either from the database or an attribute default
                allowedFormats={ [ 'core/bold','core/link' ] } // Allow the content to be made bold or italic, but do not allow other formatting options
                onChange={ ( content ) => setAttributes( { link:content } ) } // Store updated content as a block attribute
                placeholder={  'title...' } // Display this text before any content has been added by the user
            />
            {
                isSelectedOrChildSelected && (
                    <InnerBlocks    />
                )
            }
        </div>
    )
    
}

/**
 * 
 * @returns 
 */
function SaveComponent( ){
    return <InnerBlocks.Content />
}