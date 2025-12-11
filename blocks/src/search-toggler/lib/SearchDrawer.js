import Drawer from "@mui/material/Drawer";
import { useState, useEffect } from "react";
import { ReactComponent as SearchIcon } from '../../../../assets/static/img/search.svg';
import { trns } from "../../../../assets/src/js/utils/TranslationUtils";
import SearchResults from "./SearchResults";

/**
 * renders the search drawer
 */
export default function SearchDrawer( {open, onClose } ){

    const _placeholder = trns("search_by");
    const [placeholder, setPlaceholder] = useState(_placeholder);
    const [searcKeyword, setSearchKeyword] = useState("");

    useEffect(() => {
        let phase = "deleting";  // "typing" / "deleting"
        let index = _placeholder.length;
        let isPaused = false;

        const interval = setInterval(() => {

            if (isPaused) return; // freeze during pause

            if (phase === "deleting") {
                index--;
                setPlaceholder(_placeholder.substring(0, index));

                if (index === 0) {
                    phase = "typing";
                }
            }
            else { // typing
                index++;
                setPlaceholder(_placeholder.substring(0, index));

                if (index === _placeholder.length) {
                    // pause for 1 second before deleting
                    isPaused = true;
                    setTimeout(() => {
                        isPaused = false;
                        phase = "deleting";
                    }, 1000);
                }
            }

        }, 60); // speed

        return () => clearInterval(interval);
    }, [_placeholder]);

    return (
        <Drawer
            anchor="top"
            className="search-drawer"
            open={ open }
            onClose={ onClose  }
        >
            <div className="wrapper">
                <div className="search-input">
                    <SearchIcon className="search-icon"/>
                    <input 
                        className={"search-field"+(searcKeyword.length>0 ? " filled": "" )}
                        type="text" 
                        onKeyUp={ (e)=>{setSearchKeyword(e.target.value)} } 
                    />
                    <div className="placeholder">{placeholder}</div>
                    <span className="search-btn">{ trns('search') }</span>
                </div>
                <SearchResults keyword={ searcKeyword } />
 
            </div>
        </Drawer>
    );
}
