import Drawer from "@mui/material/Drawer";
import { useState } from "react";

/**
 * renders the search drawer
 */
export default function SearchDrawer(){

    const [isOpen, setIsOpen] = useState(false);

    return (
        <Drawer
            anchor="top"
            className="search-drawer"
            open={ isOpen }
            onClose={ ()=>{setIsOpen(false)} }
        >
            
        </Drawer>
    );
}