import "./index.scss"
import metadata from './block.json';
import FeaturedProductsSlider from "./lib/FeaturedProductsSlider";

wp.blocks.registerBlockType(metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent({ attributes, setAttributes }) {
   
    return (
        <FeaturedProductsSlider />
    );
}

function SaveComponent() {
    return null;
}
