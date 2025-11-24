import "../../../assets/src/scss/typography.scss";
import "./index.scss"
import metadata from './block.json';
import LatestPostsSlider from "./lib/LatestPostsSlider";

wp.blocks.registerBlockType(metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent({ attributes, setAttributes }) {
   
    return (
        <LatestPostsSlider />
    );
}

function SaveComponent() {
    return null;
}
