import "../../../assets/src/scss/typography.scss";
import "./index.scss"
import metadata from './block.json';
import RelatedPostsSlider from "./lib/RelatedPostsSlider";

wp.blocks.registerBlockType(metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent({ attributes, setAttributes }) {
   
    return (
        <RelatedPostsSlider />
    );
}

function SaveComponent() {
    return null;
}
