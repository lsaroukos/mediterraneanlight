import { PanelBody, PanelRow} from "@wordpress/components"
import { __experimentalUnitControl as UnitControl } from '@wordpress/components';

export default function WrapperSettings( {attributes, setAttributes} ) {

    const units = [
        { value: 'px', label: 'px', default: 0 },
        { value: '%', label: '%', default: 10 },
        { value: 'rem', label: 'rem', default: 0 },
    ];

    return (

    <PanelBody title="Wrapper Settings" initialOpen={true}>
        
        <PanelRow>
            <UnitControl 
                __next40pxDefaultSize 
                onChange={ (value)=>setAttributes({maxWidth:value}) }
                label = "max width"
                help = "-1 for unset"
                value={ attributes.maxWidth }
                units={ units } 
            />
        </PanelRow>

 
    </PanelBody>
    )
}
    