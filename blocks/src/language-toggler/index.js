import "./index.scss"
import metadata from './block.json';
import APIUtils from '../../../assets/src/js/utils/APIUtils';
import { useEffect, useState } from "react";
import { Spinner } from '@wordpress/components';

wp.blocks.registerBlockType( metadata.name, {
    ...metadata,
    edit: EditComponent,
    save: ()=>null,
})

function EditComponent( {attributes, setAttributes} ) {

    const [links, setLinks] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [currentLink, setCurrentLink] = useState(null);

    // fetch languages from DB
    useEffect(()=>{

        APIUtils.get( 'translations/links/' ).then( (response)=>{
            if( response.status==="success" ){
                setLinks( prev => response.links );
                const currentLink = response.links.find( link=>link.is_current==true );
                setCurrentLink(prev=>  currentLink || response.links[0]  );
            }
        }).finally(( e )=>{ setIsLoading(false) });

    },[]);


    return (
        <div className="language-toggler">
            { isLoading ? (
                <Spinner />
            ):(
                <div className="language-toggler__dropdown">
                    <label className="current-language"><a href={currentLink.link} ><img src={currentLink.flag} />{currentLink.name}</a></label>
                    <ul className="submenu">{
                        links.map( (link, key)=>{
                            return (
                                <li key={key} ><a href={link.link} ><img src={link.flag} />{link.name}</a></li>)
                        })
                    }</ul>
                </div>
            ) }
        </div>

    )
    
}