import "../../../assets/src/scss/typography.scss";
import "./index.scss"
import metadata from './block.json';
import { useBlockProps } from "@wordpress/block-editor";
import ServerSideRender from "@wordpress/server-side-render";


wp.blocks.registerBlockType(metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent({ attributes, setAttributes }) {
   
    const blockProps = useBlockProps();
    
    return (
        <div {...blockProps}>
            <ServerSideRender
                block="medlight/product-item"
            />
        </div>
    );
}

function SaveComponent() {
    return null;
}
