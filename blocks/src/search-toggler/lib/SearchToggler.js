import { ReactComponent as SearchIcon } from "../../../../assets/static/img/search.svg";
import SearchDrawer from "./SearchDrawer";
import { useState } from "react";


export default function SearchToggler( ) {

    const [isOpen, setIsOpen ] = useState( false );

    return (
        <div className="search-toggler">
            <span className="search-toggler__icon" onClick={()=>setIsOpen( !isOpen) }><SearchIcon /></span>
            <SearchDrawer open={isOpen} onClose={ ()=>setIsOpen(false) } />
        </div>

    )
    
}