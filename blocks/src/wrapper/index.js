import "./index.scss"
import metadata from './block.json';
import { Inserter, useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { Button } from "@wordpress/components";
import WrapperSettings from "./lib/WrapperSettings";
import { InspectorControls } from "@wordpress/block-editor";


wp.blocks.registerBlockType( metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: SaveComponent,
})

function EditComponent( props ) {

    const blockProps = useBlockProps({ className: "wrapper-block wrapper"});    

    /**
     * define custom appender button
     */
    function ItemAppender( { rootClientId } ) {
        if(  !props.isSelected )
            return;
        return (
            <Inserter
                rootClientId={ rootClientId }
                renderToggle={ ( { onToggle, disabled } ) => (
                    <div className="button-appender" >
                        <Button
                            className="item-appender-button is-primary"
                            onClick={ ()=>{
                                onToggle();
                            } }
                            label="Add Item"
                        >+ Add Block</Button>
                    </div>
                ) } 
                isAppender
            />
        );
    }

    /** 
     * calculate style
     */
    const getStyle = ()=>{
        let style = [];
        if( props.attributes.maxWidth!==-1 ) 
            style = {...style, maxWidth:props.attributes.maxWidth };
        return style;
    }

    return (
        <div {...blockProps} style={ getStyle() }>
            <InspectorControls>
                <WrapperSettings attributes={props.attributes} setAttributes={props.setAttributes} />
            </InspectorControls>
            <InnerBlocks
				renderAppender={ ( innerBlockProps ) => (
					<ItemAppender { ...innerBlockProps } rootClientId={ props.clientId }/>
				) }
			/>
        </div>
    );
}


/**
 * Dynamic PHP
 * @returns 
 */
function SaveComponent( ){
    return <InnerBlocks.Content />
}