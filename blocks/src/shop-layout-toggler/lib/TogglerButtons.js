import { ReactComponent as GridIcon } from '../../../../assets/static/img/grid.svg';
import { ReactComponent as Grid2Icon } from '../../../../assets/static/img/grid-2.svg';
import { ReactComponent as ListIcon } from '../../../../assets/static/img/list.svg';
import TogglerUtils from "./TogglerUtils";
import { useEffect, useState, useRef } from 'react';
import SettingsUtils from "../../../../assets/src/js/utils/SettingsUtils";

export default function TogglerButtons(){
 
    const [layout, setLayout] = useState("");
    const lastWidth = useRef(0);

    useEffect(()=>{
        setLayout( TogglerUtils.getLayout() );

         const handleResize = () => {
            if( !window || lastWidth.current===window.innerWidth )
                return;
            lastWidth.current = window.innerWidth;
            // Example breakpoints: you can change these freely
            if (window.innerWidth <= SettingsUtils.getBreakPoint("mobile")) {
                toggleLayout("grid2");
            } else if (window.innerWidth <= SettingsUtils.getBreakPoint("tablet")) {
                toggleLayout("grid2");
            } else {
                toggleLayout("grid");
            }
        };

        // Run once on mount
        handleResize();

        window.addEventListener("resize", handleResize);
        return () => window.removeEventListener("resize", handleResize);

    },[]);

    const toggleLayout = ( newLayout)=>{
        TogglerUtils.switchLayout(newLayout);
        setLayout( newLayout );
    }

    return (
        <div className="shop-layout-toggler">
            <GridIcon  data-layout="grid"   className={(layout==="grid" ? "grid-icon active" : "grid-icon" )}   onClick={ ()=>toggleLayout('grid') } />
            <Grid2Icon data-layout="grid2" className={(layout==="grid2" ? "grid2-icon active" : "grid2-icon" )}  onClick={ ()=>toggleLayout('grid2') } />
            <ListIcon data-layout="list"   className={(layout==="list" ? "list-icon active" : "list-icon" )}   onClick={ ()=>toggleLayout('list') } />
        </div>
    )
}