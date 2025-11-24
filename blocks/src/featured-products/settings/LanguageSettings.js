import { PanelBody, PanelRow} from "@wordpress/components"
import { SelectControl } from '@wordpress/components';


export default function LanguageSettings( {attributes, setAttributes} ) {


    return (

    <PanelBody title="Slider Settings" initialOpen={true}>
        
        <PanelRow>
            <SelectControl
                __next40pxDefaultSize
                __nextHasNoMarginBottom
                label="Language"
                value={ attributes.lang }
                options={ [
                    { label: 'Ελληνικά', value: 'el' },
                    { label: 'English', value: 'en' },
                ] }
                onChange={ (lang)=>setAttributes({lang:lang}) }
            />  
        </PanelRow>

 
    </PanelBody>
    )
}
    