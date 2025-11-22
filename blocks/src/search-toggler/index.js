import "./index.scss"
import metadata from './block.json';
import { useSelect } from '@wordpress/data';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { ReactComponent as SearchIcon } from '../../../assets/static/img/search.svg';
import SearchDrawer from "./lib/SearchDrawer";

wp.blocks.registerBlockType( metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: ()=>null,
})

function EditComponent({ clientId }) {

    /**
     * check if block or child is selected
     */
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
        <div className="search-toggler">
            <span className="search-toggler__icon"><SearchIcon /></span>
            <SearchDrawer 
                className={"search-drawer"+(isSelectedOrChildSelected ? " open" : "")}
                open={isSelectedOrChildSelected} 
                onClose={ ()=>wp.data.dispatch('core/block-editor').selectBlock(null) }
                anchor="top"
            />
        </div>

    )
    
}