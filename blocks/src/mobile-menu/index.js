import "../../../assets/src/scss/constants.scss"
import "./index.scss"
import metadata from './block.json';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import Drawer from '@mui/material/Drawer';

wp.blocks.registerBlockType( metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
})

const MenuToggler = ({ isActive }) => {
    return (
        <div className={"menu-toggler" + (isActive ? " act" : "")} 
            onClick={ ()=>wp.data.dispatch('core/block-editor').selectBlock(null) }
        >
            <div className="icon-directional"></div>
        </div>
    );
};

function EditComponent( { clientId, attributes, setAttributes, isSelected } ) {

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
            <MenuToggler isActive={isSelectedOrChildSelected} />
            {
                <Drawer 
                    className={"mobile-menu-drawer"+(isSelectedOrChildSelected ? " open" : "")}
                    open={isSelectedOrChildSelected} onClose={ ()=>wp.data.dispatch('core/block-editor').selectBlock(null) }
                    variant="persistent" 
                >
                    <InnerBlocks />
                </Drawer>
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