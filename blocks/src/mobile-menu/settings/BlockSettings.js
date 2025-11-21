import { PanelBody, PanelRow} from "@wordpress/components"
import {  LinkControl } from "@wordpress/block-editor";


export default function BlockSettings( {attributes, setAttributes} ) {


    return (

    <PanelBody title="Slider Settings" initialOpen={true}>
        
        <PanelRow>
            <label for="menu-item-link">Menu Item Link</label>
        </PanelRow>
        <PanelRow>
            <LinkControl 
                id="menu-item-link"
                hasTextControl 
                value={attributes.link}
                onChange={(newLink)=>setAttributes({link:newLink})}
                />
        </PanelRow>

 
    </PanelBody>
    )
}
    