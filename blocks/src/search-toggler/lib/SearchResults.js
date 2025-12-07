import { useEffect, useState } from "react";
import APIUtils from "../../../../assets/src/js/utils/APIUtils";
import { Spinner } from "@wordpress/components";
import { useSelector } from "react-redux";
import { __ } from "@wordpress/i18n";

export default function SearchResults({ keyword }){

    const [results, setResults] = useState( {} );
    const [isSearching, setIsSearching] = useState( false );
    const [totalDBResults, setTotalDBResults] = useState(0);
    
    const coreLinks = useSelector( state=>state.medlightCore.links );

    /**
     * get search results from API
     */
    useEffect(()=>{

        if( keyword.length<4 )
            return;

        setIsSearching( true ); // show loading spinner

        APIUtils.get(`wc/products/search?s=${keyword}&limit=5&page=1`).then(( response )=>{
            if( response?.status==="success" ){
                setResults( response.products );    // store results
                setTotalDBResults( response.total_results );
            }
        }).finally(()=>setIsSearching(false));  // hide loading spinner

    },[keyword]);

    if( !isSearching && Object.keys(results).length===0 && keyword.length>=4 ){
        return (
            <div className="results-container">
            {  __("No results for")+" "+keyword }  
            </div>
        )
    }

    return (

        <div className="results-container">
            { isSearching ? (
                <Spinner />
            ):(
                <div className="results">
                {Object.values(results).map( product => {
                    return (
                        <div className="results-product">
                            <img className="product-image" src={product.image} />
                            <div className="product-details">
                                <span>{product.title}</span>
                                <span>{product.price}</span>
                            </div>
                        </div>
                    )
                })}
                </div>
            )}
            {
                totalDBResults>0 && (
                    <a className="btn btn-primary" href={coreLinks?.search+keyword} >{ __("See All Results","medlight")+` (${totalDBResults})` }</a>
                )
            }
        </div>
    )
}
