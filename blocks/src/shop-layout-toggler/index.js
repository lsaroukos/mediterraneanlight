import "../../../assets/src/scss/typography.scss";
import "./index.scss"
import metadata from './block.json';
import { useBlockProps } from "@wordpress/block-editor";
import TogglerButtons from "./lib/TogglerButtons";

wp.blocks.registerBlockType(metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent({ attributes, setAttributes }) {
   
    const blockProps = useBlockProps({ className: "shop-layout-toggler-block"});

    return (
        <div { ...blockProps } >
            <TogglerButtons />
        </div>
    );
}

function SaveComponent() {
    return null;
}
